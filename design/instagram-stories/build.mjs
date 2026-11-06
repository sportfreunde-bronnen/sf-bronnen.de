#!/usr/bin/env node
/**
 * Exports the Instagram stories (src/*.svg) as
 *
 *   export/svg/<name>.svg  self-contained SVG (photos, crest and fonts embedded), e.g. for Figma, Affinity, Inkscape, Illustrator
 *   export/png/<name>.png  1080 x 1920 px PNG, ready for the upload to Instagram
 *
 * Usage (Node.js 20 or newer, Google Chrome / Chromium installed, no npm packages needed):
 *
 *   node design/instagram-stories/build.mjs            # all stories
 *   node design/instagram-stories/build.mjs preise     # only stories whose file name contains "preise"
 *
 * Another browser can be selected with the environment variable CHROME, e.g.
 *   CHROME="/Applications/Chromium.app/Contents/MacOS/Chromium" node design/instagram-stories/build.mjs
 */
import { existsSync, mkdirSync, readFileSync, readdirSync, writeFileSync } from 'node:fs';
import { spawnSync } from 'node:child_process';
import { basename, dirname, extname, join, resolve } from 'node:path';
import { fileURLToPath, pathToFileURL } from 'node:url';

const root = dirname(fileURLToPath(import.meta.url));
const srcDir = join(root, 'src');
const svgDir = join(root, 'export', 'svg');
const pngDir = join(root, 'export', 'png');

const WIDTH = 1080;
const HEIGHT = 1920;

const MIME = {
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.png': 'image/png',
  '.svg': 'image/svg+xml',
  '.woff2': 'font/woff2',
  '.woff': 'font/woff',
  '.ttf': 'font/ttf',
};

const CHROME_CANDIDATES = [
  process.env.CHROME,
  '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  '/Applications/Chromium.app/Contents/MacOS/Chromium',
  '/Applications/Microsoft Edge.app/Contents/MacOS/Microsoft Edge',
  'google-chrome',
  'google-chrome-stable',
  'chromium',
  'chromium-browser',
].filter(Boolean);

function findChrome() {
  for (const candidate of CHROME_CANDIDATES) {
    if (candidate.includes('/') ? existsSync(candidate) : spawnSync(candidate, ['--version']).status === 0) {
      return candidate;
    }
  }
  throw new Error('Chrome/Chromium not found. Set the environment variable CHROME to the browser executable.');
}

/**
 * Replaces every relative file reference (href="..." and url('...')) with a data URI.
 */
function embedFiles(svg) {
  const toDataUri = (reference) => {
    if (/^(data:|#|https?:|\/\/)/.test(reference)) {
      return reference;
    }

    const file = resolve(srcDir, reference);
    const mime = MIME[extname(file).toLowerCase()];

    if (!mime) {
      throw new Error(`Unsupported file type: ${reference}`);
    }

    return `data:${mime};base64,${readFileSync(file).toString('base64')}`;
  };

  return svg
    .replace(/(\s(?:xlink:)?href=)"([^"]+)"/g, (match, attribute, reference) => `${attribute}"${toDataUri(reference)}"`)
    .replace(/url\((['"]?)([^'")]+)\1\)/g, (match, quote, reference) => `url(${quote}${toDataUri(reference)}${quote})`);
}

function pngSize(file) {
  const buffer = readFileSync(file);
  return { width: buffer.readUInt32BE(16), height: buffer.readUInt32BE(20) };
}

const filter = process.argv[2];
const stories = readdirSync(srcDir)
  .filter((file) => file.endsWith('.svg') && (!filter || file.includes(filter)))
  .sort();

if (stories.length === 0) {
  console.error(`No story found${filter ? ` for "${filter}"` : ''} in ${srcDir}`);
  process.exit(1);
}

mkdirSync(svgDir, { recursive: true });
mkdirSync(pngDir, { recursive: true });

const chrome = findChrome();

for (const file of stories) {
  const name = basename(file, '.svg');
  const svgFile = join(svgDir, `${name}.svg`);
  const pngFile = join(pngDir, `${name}.png`);

  writeFileSync(svgFile, embedFiles(readFileSync(join(srcDir, file), 'utf8')));

  const result = spawnSync(chrome, [
    '--headless=new',
    '--disable-gpu',
    '--hide-scrollbars',
    '--force-device-scale-factor=1',
    `--window-size=${WIDTH},${HEIGHT}`,
    '--virtual-time-budget=5000',
    `--screenshot=${pngFile}`,
    pathToFileURL(svgFile).href,
  ], { encoding: 'utf8', timeout: 120000 });

  if (result.status !== 0 || !existsSync(pngFile)) {
    console.error(`Export of ${name} failed:\n${result.stderr}`);
    process.exit(1);
  }

  const { width, height } = pngSize(pngFile);
  const hint = width === WIDTH && height === HEIGHT ? '' : `  (WARNING: expected ${WIDTH} x ${HEIGHT})`;
  console.log(`${name}: ${width} x ${height}${hint}`);
}
