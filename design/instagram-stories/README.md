# Instagram Stories – SVZ Vermietung

Werbe-Stories für die Vermietung des Sportvereinszentrums (SVZ) im Format **1080 × 1920 px (9:16)**, gestaltet in den Farben und Schriften der Webseite.

| Story | Datei | Inhalt |
| --- | --- | --- |
| 1 | `src/story-1-feier.svg` | „Ihre Feier. Unser SVZ." – Foto, Kennzahlen, Button |
| 2 | `src/story-2-preise.svg` | „Das passende Paket für Ihre Feier." – Preisübersicht |
| 3 | `src/story-3-familie.svg` | „Feiern mit der ganzen Familie." – Spielplatz, Terrasse, Küche, barrierefrei |

## Ordner

```
design/instagram-stories/
├── src/        Quelldateien (SVG), hier wird gearbeitet
├── images/     Fotos und Vereinswappen, die in den Stories verwendet werden
├── export/
│   ├── png/    fertige Bilder für Instagram (1080 × 1920 px)
│   └── svg/    eigenständige SVGs (Fotos, Wappen und Schriften eingebettet)
└── build.mjs   Export-Skript
```

## Stories anpassen

Es gibt zwei Wege:

1. **Im Grafikprogramm (ohne Code):** `export/svg/<name>.svg` in Figma, Affinity Designer, Inkscape oder Illustrator öffnen. Alle Elemente haben sprechende Namen (z. B. `titel-zeile-1`, `button-text`, `foto`) und bleiben einzeln editierbar. Texte sind echter Text, keine Pfade.
   Hinweis: Die Schriften (Saira Semi Condensed, Lato) sind in der SVG eingebettet. Wenn ein Programm sie nicht übernimmt, die Schriften aus `public/assets/fonts/gf/` installieren (`.ttf`-Dateien).
2. **Im Texteditor:** `src/<name>.svg` bearbeiten (reiner Text, Texte und Zahlen stehen in den `<text>`-Elementen) und danach neu exportieren.

### Neu exportieren

Voraussetzungen: Node.js 20+ und Google Chrome (oder Chromium). Es werden keine npm-Pakete benötigt.

```bash
node design/instagram-stories/build.mjs            # alle Stories
node design/instagram-stories/build.mjs preise     # nur Stories, deren Dateiname "preise" enthält
```

Anderer Browser: `CHROME="/Pfad/zum/Browser" node design/instagram-stories/build.mjs`

### Foto austauschen

In der SVG-Datei das Element `<image id="foto" … href="../images/….jpg"/>` auf eine andere Datei in `images/` zeigen lassen (oder die Datei in `images/` ersetzen). Der Ausschnitt wird automatisch zugeschnitten (`preserveAspectRatio="xMidYMid slice"`).

### Preise ändern

Die Preise in Story 2 sind feste Texte (Stand der Preisübersicht auf der SVZ-Seite, Tarif „Externe"). Bei Preisänderungen in `src/story-2-preise.svg` die Zahlen in den Elementen der Gruppen `karte-1-klein`, `karte-1-gross`, `karte-2-klein` und `karte-2-gross` anpassen. Die Quelle der Preise ist `content/2_gesamtverein/1_allgemeines/2_svz/svz.txt`.

## Design-Hinweise

- **Farben** (aus `frontend/src/sass/_variables.scss`): Dunkelblau `#00314a` / `#00222f` / `#004869`, Blau `#007cb5` / `#0088df`, Gelb `#ffb703`, Hellblau `#e8f3f9`.
- **Schriften:** Überschriften und Zahlen in „Saira Semi Condensed" (900), Fließtext in „Lato".
- **Sicherer Bereich:** Instagram blendet oben (Profil, Fortschrittsbalken) und unten (Antwortfeld, Link-Sticker) Elemente ein. Wichtige Inhalte deshalb zwischen ca. y = 270 und y = 1580 halten.
- **Link:** In Instagram kann ein Link nur über den Link-Sticker gesetzt werden. Ziel: `https://vermietung.sf-bronnen.de`.
