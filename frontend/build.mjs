/**
 * Stylesheet build: Sass (Dart Sass) -> Autoprefixer (PostCSS) -> ../public/assets/css
 *
 * Every top level *.scss file in src/sass (partials starting with "_" excluded) is an entry point
 * and ends up as <name>.css, e.g. sfb.scss -> public/assets/css/sfb.css.
 *
 *   node build.mjs           build once
 *   node build.mjs --watch   build and rebuild on every change in src/sass
 *
 * The browsers for the vendor prefixes are configured via "browserslist" in package.json.
 */
import { mkdir, readdir, writeFile } from 'node:fs/promises';
import { watch } from 'node:fs';
import { basename, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';

const root = fileURLToPath(new URL('.', import.meta.url));
const srcDir = join(root, 'src/sass');
const outDir = join(root, '../public/assets/css');

const prefixer = postcss([autoprefixer]);

async function entryPoints() {
  const files = await readdir(srcDir);
  return files.filter((file) => file.endsWith('.scss') && !file.startsWith('_'));
}

async function buildFile(file) {
  const { css } = sass.compile(join(srcDir, file), {
    style: 'compressed',
    sourceMap: false,
    loadPaths: [join(root, 'node_modules')],
    // Bootstrap 4 is written in the legacy Sass syntax, its deprecation warnings are not ours to fix
    quietDeps: true,
    // @import cannot be replaced by @use as long as Bootstrap 4 is imported
    silenceDeprecations: ['import'],
  });
  const result = await prefixer.process(css, { from: undefined, map: false });
  await writeFile(join(outDir, `${basename(file, '.scss')}.css`), result.css);
}

async function build() {
  const started = performance.now();
  await mkdir(outDir, { recursive: true });
  const files = await entryPoints();
  await Promise.all(files.map(buildFile));
  const seconds = ((performance.now() - started) / 1000).toFixed(2);
  console.log(`Built ${files.map((file) => basename(file, '.scss') + '.css').join(', ')} in ${seconds}s`);
}

async function safeBuild() {
  try {
    await build();
  } catch (error) {
    console.error(error instanceof sass.Exception ? error.message : error);
    return false;
  }
  return true;
}

if (process.argv.includes('--watch')) {
  await safeBuild();
  console.log(`Watching ${srcDir} for changes ...`);

  let timer;
  watch(srcDir, { recursive: true }, (event, filename) => {
    if (filename && !filename.endsWith('.scss')) {
      return;
    }
    clearTimeout(timer);
    timer = setTimeout(safeBuild, 100);
  });
} else if (!(await safeBuild())) {
  process.exitCode = 1;
}
