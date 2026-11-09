---
sessionId: session-261109-143554-6069
---

# Requirements

### Überblick & Ziele
**Antwort auf die Frage: Ja, mit Kirby geht das, aber nicht allein mit Bordmitteln.** Kirby kennt die Seitenrechte `access`, `list` und `read` (`read` blendet eine Seite im Panel komplett aus). Sie gelten aber nur **pro Seitenvorlage** und werden nicht an Unterseiten vererbt. Vorlagen wie `default` oder `textOnly` werden in allen Abteilungen genutzt, deshalb reicht die Konfiguration in den Blueprints nicht aus.

Ziel ist ein kleines Plugin `sfb-roles`. Admins legen im Panel pro Rolle fest, welche Seiten (samt **allen** Unterseiten) für diese Rolle **ausgeblendet** oder **nur ansehbar** sind. Alles andere verhält sich wie bisher.

### Scope
#### In Scope
- Plugin `site/plugins/sfb-roles` mit der Rechtelogik pro Seite.
- Konfiguration im Panel: neuer Tab "Seitenrechte" auf der Site-Seite (Strukturfeld, nur für Admins änderbar).
- Stufe **Ausblenden**: Die Seite ist im Panel unsichtbar (Seitenbaum, Listen, Suche, Seitenauswahl) und beim direkten Aufruf der Panel-URL "nicht gefunden". Das gilt auch für die Dateien der Seite.
- Stufe **Nur ansehen**: Die Seite ist im Panel sichtbar, aber alle Schreibaktionen sind serverseitig gesperrt.
- Eine Beispielrolle `editor` ("Redakteur") als Ausgangspunkt, weitere Rollen als eigene YAML-Dateien.
- README-Abschnitt.

#### Out of Scope
- Die öffentliche Website: Seiten bleiben für Besucher unverändert sichtbar.
- Ein Freigabelisten-Modus ("Rolle sieht nur ..."), entschieden wurde "nur Gesperrtes ausblenden".
- Rechte pro einzelnem Benutzer; Ausnahmen innerhalb eines gesperrten Zweigs (z. B. Unterseite wieder bearbeitbar).
- Rechte für Benutzer, System und Sprachen (bleiben in der Rollen-Blueprint).

### User Stories
- Als Admin möchte ich pro Rolle Seiten samt Unterseiten im Panel ausblenden, damit ein Abteilungsredakteur nur seinen Bereich sieht.
- Als Admin möchte ich Seiten für eine Rolle auf "nur ansehen" setzen, damit sie gelesen, aber nicht verändert werden können.
- Als Redakteur sehe ich gesperrte Bereiche nicht und kann schreibgeschützte nicht versehentlich ändern.

### Functional Requirements
- Eine Regel besteht aus **Rolle**, **Stufe** (Ausblenden / Nur ansehen) und **Seiten** (Mehrfachauswahl). Sie gilt für die Seite und alle Unterseiten, auch für später angelegte.
- Pro Rolle sind mehrere Regeln möglich. Bei Überschneidung gilt die strengere Stufe (Ausblenden vor Nur ansehen).
- Nicht aufgeführte Seiten behalten die Rechte aus der Rollen-Blueprint.
- Der Admin ist nie eingeschränkt und kann sich nicht aussperren. Nicht eingeloggte Besucher und das Frontend sind unberührt.
- Seiten werden per UUID referenziert, die Regeln bleiben beim Umbenennen oder Verschieben gültig.
- **Nur ansehen** sperrt serverseitig:
  - Seite: ändern, Titel/Slug/Status/Vorlage ändern, Unterseiten anlegen, löschen, duplizieren, verschieben, sortieren.
  - Dateien der Seite: hochladen, ersetzen, umbenennen, ändern, sortieren, löschen.
  - Andere Seiten dürfen nicht in einen solchen Zweig verschoben werden.
  - Im Panel erscheint die Seite schreibgeschützt (Formular und Aktionen deaktiviert).
- Das Strukturfeld "Seitenrechte" auf der Site-Seite können nur Admins ändern.

### Non-Functional Requirements
- Kompatibel mit Kirby 5.6.3. Die Regeln werden pro Request einmal aufgelöst und zwischengespeichert, damit Panel-Listen nicht langsamer werden.
- Die Texte im Panel sind auf Deutsch, passend zu den vorhandenen Blueprints.

# Technical Design

### Current Implementation
- Kirby **5.6.3**. Es gibt nur die Rolle `admin` (`site/blueprints/users/admin.yml` mit dem Feld `alias`) und einen Account.
- `site/blueprints/site.yml` ist nur `preset: pages`. `content/site.txt` enthält nur den Titel. Seiten haben UUIDs (z. B. `content/6_darts/default.txt`).
- Die 11 Seitenmodelle in `site/models` erben direkt von `Page` (`LinkPage`, `SvzPage`, `BeitraegePage`, `HomePage` usw.). Es gibt **kein** `default`-Modell. Plugin-Muster: `site/plugins/sfb-svz/index.php` mit `require_once` auf `src/*.php`.
- Kirby-Core:
  - `Page::isAccessible()` und `Page::isListable()` rufen `isReadable()` auf. `isReadable()` und `PagePermissions::canFromCache()` speichern pro **Rolle und Vorlage** zwischen. Blueprint-Optionen sind daher nie seitenspezifisch und werden nicht vererbt.
  - `Find::page()` wirft "nicht gefunden", wenn `isAccessible()` falsch liefert. Das gilt auch für Dateien (über `Find::parent`). Seitenbaum, Collectors, Suche und Picker filtern über `isListable()`.
  - Schreibaktionen prüft `PageRules` über `$page->permissions()->can(...)` **ohne** Cache.
  - `HasModels::model()` nimmt für Vorlagen ohne eigenes Modell `Page::$models['default']`.
  - `Role`/`UserBlueprint` verwerfen eigene Schlüssel, die Zuordnung kann also nicht in der Rollen-YAML stehen.

### Key Decisions
1. **Plugin mit Basis-Seitenmodell** `RoleAwarePage extends Page`, registriert als `pageModels['default']`. Die 11 vorhandenen Modelle erben künftig von `RoleAwarePage` statt `Page`. Das ist die einzige Stelle, an der Kirby eine Entscheidung pro Seite zulässt, und es passt zum Modellmuster des Projekts.
2. **Konfiguration in `content/site.txt`** (Strukturfeld `rolePermissions`, Panel-Tab auf der Site-Seite). Gewählt, weil Änderungen ohne Deployment möglich sind und die Seitenauswahl Tippfehler bei IDs vermeidet.
3. **Nur-Sperrliste, zwei Stufen** (`hidden`, `readonly`). Es gilt die strengere Stufe. Der Zweig wird über die Seiten-ID-Vorfahren erkannt, auch für noch nicht angelegte Unterseiten.
4. **Ausblenden** über Überschreiben von `isAccessible()` und `isListable()` (stabile API, unabhängig vom in v6 auslaufenden `read`). **Nur ansehen** über Überschreiben von `permissions()` mit `RolePagePermissions`. Dessen `can()` liefert für Schreibaktionen `false`. Die gecachten Aktionen `access`/`list` werden darin nie angefasst.
5. **Dateien und Verschieben über Hooks**, weil Kirby keine Datei-Modelle kennt: `file.create/replace/update/delete/changeName/changeSort/changeTemplate:before` und `page.move:before` im Plugin.

### Architecture Diagram
``` mermaid
graph TD
A[Admin: Tab Seitenrechte] --> B[content/site.txt rolePermissions]
B --> C[RolePageAccess]
D[Rolle des Benutzers] --> C
C --> E[RoleAwarePage isAccessible isListable]
C --> F[RolePagePermissions can]
C --> G[Hooks file.* und page.move]
E --> H[Panel: Baum, Listen, Suche, URL]
F --> I[PageRules: update delete create]
G --> I
```

### Data Models / Contracts
Strukturfeld in `site/blueprints/site.yml` (Feldname `rolePermissions`, je Zeile):
```yaml
role:  { type: select, required: true }   # Optionen aus kirby.roles ohne admin
level: { type: select, required: true, default: readonly }  # readonly = Nur ansehen | hidden = Ausblenden
pages: { type: pages, multiple: true, required: true }      # speichert UUIDs
```
Plugin-Klassen (global, wie die vorhandenen Modelle):
```php
final class RolePageAccess {
    const HIDDEN = 'hidden'; const READONLY = 'readonly';
    // null = unbeschränkt; admin und nicht eingeloggt => immer null
    public static function levelFor(Page|string $page, ?string $roleId = null): ?string;
    // [pageId => level], einmal pro Request und Rolle aufgelöst (toPages() -> id)
    public static function rules(string $roleId): array;
}
class RolePagePermissions extends PagePermissions {
    // WRITE = changeSlug, changeStatus, changeTemplate, changeTitle,
    //         create, delete, duplicate, move, sort, update
    public function can(string $action, bool $default = false): bool;
}
class RoleAwarePage extends Page {
    public function isAccessible(): bool;  // false bei HIDDEN
    public function isListable(): bool;    // false bei HIDDEN
    public function permissions(): PagePermissions; // RolePagePermissions
}
```
Hooks: Bei `READONLY` am `$file->parent()` werfen die `file.*:before`-Hooks eine `PermissionException`. `page.move:before` sperrt, wenn das Zielelternteil `READONLY` oder `HIDDEN` ist.

### File Structure
- Neu: `site/plugins/sfb-roles/index.php` (Plugin, `require_once`, `pageModels`, Hooks).
- Neu: `site/plugins/sfb-roles/src/RolePageAccess.php`, `RoleAwarePage.php`, `RolePagePermissions.php`.
- Neu: `../../site/blueprints/users/editor.yml` (Beispielrolle "Redakteur" mit `extends: users/admin` für das Feld `alias`, eingeschränkte `permissions`: kein Zugriff auf System und Benutzerverwaltung).
- Geändert: `site/blueprints/site.yml` (Tab "Seiten" mit den Preset-Sektionen, Tab "Seitenrechte", `options.update` nur für admin), die 11 Modelle in `site/models/*.php` (`extends RoleAwarePage`), `README.md` (Abschnitt "Role based page permissions").

### Risks
- **Kirby-Update auf v6**: `read` ist dort zur Abschaffung vorgesehen. Wir überschreiben `isAccessible()`/`isListable()` statt `isReadable()`, im README steht der Hinweis, die Überschreibungen nach dem Update zu prüfen.
- **Ladereihenfolge**: Plugins laden vor `site/models`. `RoleAwarePage` wird per `require_once` im Plugin geladen, das wird getestet.
- **Keine Ausnahmen in Zweigen**: Eine editierbare Unterseite unterhalb einer nur ansehbaren Seite ist nicht möglich. Das ist dokumentiert.
- **Verschieben**: Ohne Hook könnte man Seiten in schreibgeschützte Zweige verschieben, daher `page.move:before`.
- **Site-Tab sichtbar**: Redakteure sehen den Tab "Seitenrechte" schreibgeschützt (`options.update`: nur Admin).
- **Ausgeblendete Home-/Fehlerseite**: erlaubt, aber im README erwähnt. Regeln auf gelöschte Seiten oder nicht mehr vorhandene Rollen werden ignoriert.

# Testing

### Validation Approach
Ein temporäres Kirby-Skript außerhalb des Projekts (wie bei den externen Links), das die Kirby-Instanz mit **In-Memory-Rollen, -Benutzern und Seitenrechten** aufbaut. `content/` und `storage/accounts` bleiben unberührt. Zusätzlich `php -l` für alle neuen und geänderten PHP-Dateien und das Laden der Blueprints (`site`, `users/editor`).

Die Panel-Oberfläche wird nicht im Browser geprüft. Geprüft werden die serverseitigen Entscheidungen, auf die das Panel sich stützt (`Find::page`, `isListable`, `PageRules`, Hooks).

### Key Scenarios
- Regel "editor: Ausblenden -> Darts": `isAccessible()` und `isListable()` sind für `6_darts` und alle Unterseiten (z. B. `darts/spielbetrieb/erste-mannschaft`) falsch. `Find::page()` wirft `NotFoundException`. Andere Abteilungen bleiben sichtbar.
- Regel "editor: Nur ansehen -> Gesamtverein": Die Seite ist sichtbar. `PageRules::update`, `delete`, `changeTitle`, `create` (neue Unterseite), `move` und `duplicate` werfen `PermissionException`. `permissions()->toArray()` zeigt `update=false`.
- Dateien einer Nur-ansehen-Seite: `file.create:before` und `file.delete:before` werfen eine Ausnahme. Dateien einer freien Seite funktionieren wie bisher.
- `page.move:before`: Verschieben unter eine Nur-ansehen- oder ausgeblendete Seite wird verweigert.
- Der Admin bleibt trotz Regeln unbeschränkt. Ohne eingeloggten Benutzer (Frontend) liefert `levelFor()` immer `null`, und die öffentliche Seite rendert unverändert.
- Der Blueprint `site` lädt mit beiden Tabs. Eine Strukturzeile mit UUID-Auswahl wird zu den richtigen Seiten-IDs aufgelöst.

### Edge Cases
- Überschneidung (Seite in zwei Regeln mit verschiedenen Stufen): die strengere gewinnt.
- Regel auf eine gelöschte Seite oder eine nicht vorhandene Rolle: kein Fehler, wird ignoriert.
- Seite wird umbenannt oder verschoben: Die UUID-Auflösung liefert die neue ID, die Regel greift weiter.
- Leere Konfiguration (`site.txt` ohne `rolePermissions`): keine Einschränkung, keine Fehler.
- Vorhandene Modelle (`LinkPage`, `SvzPage`, ...) verhalten sich nach der Umstellung auf `RoleAwarePage` unverändert (der Test der externen Links wird wiederholt).

### Test Changes
Das Projekt hat keine automatisierte Testsuite. Es wird kein dauerhafter Test ergänzt, das Prüfskript liegt im Scratch-Verzeichnis und wird nicht committet.

# Delivery Steps

### ✓ Step 1: Plugin sfb-roles: Seiten pro Rolle ausblenden
Seiten, die in `rolePermissions` für eine Rolle auf "Ausblenden" stehen, sind samt Unterseiten für diese Rolle im Panel unsichtbar und gesperrt.

- Neues Plugin `site/plugins/sfb-roles/index.php` nach dem Muster von `sfb-svz` (`require_once` der Klassen, `Kirby::plugin('sfb/roles', ...)`).
- `RolePageAccess`: liest das Strukturfeld `rolePermissions` aus `site()->content()`, löst die Seiten per `toPages()` zu IDs auf, speichert pro Request und Rolle zwischen. `levelFor()` prüft den Zweig über ID-Präfixe. Es gilt die strengere Stufe. Admin und nicht eingeloggte Benutzer sind ausgenommen.
- `RoleAwarePage extends Page` mit überschriebenen `isAccessible()` und `isListable()`. Registrierung als `pageModels['default']`.
- Die 11 Modelle in `site/models/*.php` erben von `RoleAwarePage` statt `Page`.
- Prüfung mit einem temporären Kirby-Skript: Rollen, Benutzer und Site-Inhalt im Speicher. Ausgeblendete Seiten und Unterseiten, `Find::page()` mit `NotFoundException`, Admin und Frontend unbeschränkt, `php -l`, vorhandene Modelle unverändert.

### ✓ Step 2: Stufe "Nur ansehen": Seiten, Dateien und Verschieben absichern
Seiten mit der Stufe "Nur ansehen" sind im Panel lesbar, aber alle Schreibaktionen auf der Seite, ihren Dateien und beim Verschieben sind serverseitig gesperrt.

- `RolePagePermissions extends PagePermissions`: `can()` liefert für Schreibaktionen (`changeSlug`, `changeStatus`, `changeTemplate`, `changeTitle`, `create`, `delete`, `duplicate`, `move`, `sort`, `update`) `false`, wenn die Seite für die aktuelle Rolle `readonly` ist. Sonst `parent::can()`.
- `RoleAwarePage::permissions()` gibt `RolePagePermissions` zurück.
- Hooks im Plugin: `file.create/replace/update/delete/changeName/changeSort/changeTemplate:before` werfen eine `PermissionException`, wenn die Elternseite `readonly` ist. `page.move:before` sperrt Ziele, die `readonly` oder `hidden` sind.
- Prüfung mit dem Skript: `PageRules::update/delete/create/changeTitle/move/duplicate` werfen eine Ausnahme, `permissions()->toArray()` zeigt `update=false`, Datei-Hooks sperren, Dateien freier Seiten und der Admin sind nicht betroffen.

### ✓ Step 3: Panel-Konfiguration, Beispielrolle und Dokumentation
Admins pflegen die Seitenrechte im neuen Tab "Seitenrechte" der Site-Seite, eine Beispielrolle "Redakteur" ist angelegt, und das Ganze ist im README beschrieben.

- `site/blueprints/site.yml`: Tab "Seiten" (Preset-Sektionen unverändert) und Tab "Seitenrechte" mit dem Strukturfeld `rolePermissions` (`role` aus `kirby.roles` ohne admin, `level` readonly/hidden, `pages` mit Mehrfachauswahl). `options.update` nur für `admin`. Das Laden des Blueprints wird per Skript geprüft.
- `../../site/blueprints/users/editor.yml`: Rolle "Redakteur" (`extends: users/admin`, Seiten und Dateien bearbeitbar, kein Zugriff auf System und Benutzerverwaltung).
- Gesamtprüfung mit Konfiguration aus echtem Strukturfeld-YAML: UUID-Auswahl wird aufgelöst, leere Konfiguration bleibt ohne Wirkung, Überschneidungen und gelöschte Seiten verhalten sich wie beschrieben.
- `README.md`: Abschnitt "Role based page permissions" mit Bedienung, Stufen, Grenzen (keine Ausnahmen im Zweig) und dem Hinweis auf Kirby v6 (`read`).
