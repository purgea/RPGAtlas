<p align="center">
  <img src="public/atlas/img/system/rpgatlas-logo.svg" width="96" alt="RPGAtlas logo">
</p>

<h1 align="center">RPGAtlas Laravel</h1>

<p align="center"><i>Chart your world. Tell your story.</i></p>

<p align="center">
  <a href="public/atlas/LICENSE"><img alt="License: GPL v3" src="https://img.shields.io/badge/License-GPLv3-blue.svg"></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-13-red.svg">
  <img alt="Inertia" src="https://img.shields.io/badge/Inertia-Vue_3-purple.svg">
  <img alt="NativePHP" src="https://img.shields.io/badge/NativePHP-Desktop-green.svg">
</p>

**RPGAtlas Laravel** is a Laravel, Inertia, Vue, and NativePHP desktop-ready port of
[DriftwoodGaming/RPGAtlas](https://github.com/DriftwoodGaming/RPGAtlas), a complete free and open
source 2D RPG creator. The original RPGAtlas editor and player logic are preserved as browser
runtime assets, while Laravel provides routing, build tooling, application structure, and a
NativePHP desktop shell.

The editor is available at `/`; the game player is available at `/play`.

## Quick start

Install PHP and Node dependencies, prepare the Laravel app, then run the web version:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
composer run dev
```

Open the Laravel URL printed by `php artisan serve` and use the editor at `/`. Click **Playtest** in
the editor, or visit `/play`, to run the bundled sample game and any project saved in the browser.

For the desktop version:

```bash
composer run native:dev
```

NativePHP opens the Laravel app in a desktop window while Vite serves the frontend during
development.

## What changed from upstream RPGAtlas

The upstream project is a static HTML/JavaScript app with `index.html` for the editor and
`play.html` for the player. This port keeps the engine code intact under `public/atlas`, but mounts
it through Inertia pages:

| Upstream RPGAtlas | Laravel port |
|---|---|
| `index.html` | `/` via `resources/js/Pages/AtlasEditor.vue` |
| `play.html` | `/play` via `resources/js/Pages/AtlasPlayer.vue` |
| `css/`, `js/`, `img/` | `public/atlas/css`, `public/atlas/js`, `public/atlas/img` |
| Python static server | Laravel app server or NativePHP desktop window |
| Relative asset paths | Resolved under `/atlas/` |

Project autosaves and game save slots still use browser storage. In the NativePHP desktop app, that
storage belongs to the embedded desktop browser runtime.

## The editor

A classic RPG-maker layout: menu bar plus an icon toolbar with the original RPGAtlas editor tools.

| Area | What it does |
|---|---|
| **Map mode** | Paint tiles on 4 layers with Pen, Eraser, Rectangle, Circle, Fill, and Shadow Pen tools |
| **Event mode** | Double-click cells to create or edit events; drag events to move them |
| **Passability mode** | Override passability per tile with auto, blocked, or passable values |
| **Cut / Copy / Paste** | Shift-drag tile regions and copy or paste events |
| **Undo / Redo** | Full-map history for tiles, shadows, passability, and events |
| **Database** | Actors, Classes, Skills, Items, Weapons, Armors, Enemies, Troops, States, Switches, Variables, System |
| **System tab** | Screen size, UI area, fonts, sound/music mapping, battle view, and player start settings |
| **Plugin Manager** | Project-embedded JavaScript with boot, map-load, and per-frame hooks |
| **Resource Manager** | Browse generated resources and export PNGs |
| **Character Generator** | Compose original walking sprites usable throughout a project |
| **Open / Export** | Save to browser storage, import/export project JSON, or export a standalone game |

Shortcuts: `B/E/R/O/F/S` tools, `0` auto layer, `1-4` layers, `+/-` and `Ctrl` + wheel zoom,
`Ctrl+0` 1:1, right-click to pick a tile, `Ctrl+Z/Y` undo/redo, `Ctrl+X/C/V` clipboard, and `Del`
to delete a selected event.

## The player

- Grid movement with smooth scrolling camera: arrows or WASD, `Shift` to dash
- `Z`/`Enter` confirm and interact, `X`/`Esc` menu or cancel
- Message windows with typewriter text and RPGAtlas text codes
- Pause menu with Items, Skills, Equip, Status, Save/Load, and Return to Title
- Turn-based battles in side-view or front-view mode
- Procedural enemies, music, sound effects, particles, shops, states, EXP, levels, and random encounters
- Presentation driven by the Database System tab

## Custom assets

Custom images live under `public/atlas/img`:

```text
public/atlas/img/characters   walking sprite sheets
public/atlas/img/facesets     actor portraits matched by filename
public/atlas/img/enemies      enemy battle images
public/atlas/img/tilesets     individual map tiles
public/atlas/img/system       shared UI graphics, including icon_set.png
```

Copy files into the appropriate folder and reload the editor. Custom tile filenames control
passability:

- `stone.png` is blocked.
- `bridge.pass.png` is passable.
- `meadow.terrain.png` is passable and selected as terrain by Auto Layer.

If your hosting setup does not expose directory listings, update the asset manifest with the
upstream helper in `public/atlas/tools/update-assets.ps1`. Replace
`public/atlas/img/system/icon_set.png` with a transparent 256x256, 8x8 icon sheet to reskin the
database icon picker.

## Plugins

Projects embed plain JavaScript plugins that run at game boot. Each plugin receives the `atlas`
engine bridge and the `game` script API. The bundled default plugins are:

- **Atlas_Core**: shared plugin registry and helpers
- **Atlas_TextCodes**: color codes and BBCode in messages
- **Atlas_Transitions**: fade, iris, curtain, and slide transfer effects
- **Atlas_Weather**: rain, storm, snow, and fog overlays

## Publishing

This Laravel port gives you two publishing paths:

- **Web app**: deploy the Laravel app normally with built Vite assets and the `public/atlas` runtime
  assets.
- **Desktop app**: use NativePHP packaging for a desktop build after configuring
  `config/nativephp.php` and your platform-specific signing/updater settings.

Inside RPGAtlas itself, **File > Export Standalone Game** still exports the current project as a
standalone HTML game or Windows launcher, matching upstream behavior.

## Project format

RPGAtlas projects are saved as one JSON document and autosaved to local browser storage:

```text
system      - title, start position, party, gold, screen/UI settings, sounds, music, battle view
states      - battle states, duration, colors, icons, and turn effects
assets      - stable references for shared custom assets
actors      - name, class, level, sprite, and starting equipment
classes     - stats, growth, traits, equipment permissions, and skill learnings
skills      - icon, type, power, MP cost, and scope
items       - consumables, prices, and effects
weapons     - equipment parameters, prices, and traits
armors      - equipment parameters, prices, and traits
enemies     - stats, rewards, weighted actions, sprite, and tint
troops      - enemy groups for battles
maps        - tile layers, shadows, passability overrides, and events
plugins     - name, JavaScript code, enabled flag, and load order
customChars - sprites built in the Character Generator
```

Projects from the pre-rebrand Driftwood Engine release migrate automatically in the RPGAtlas runtime.

## Repository layout

```text
app/Http, routes/web.php              Laravel routes and application shell
resources/js/Pages/AtlasEditor.vue    Inertia wrapper for the RPGAtlas editor
resources/js/Pages/AtlasPlayer.vue    Inertia wrapper for the RPGAtlas player
public/atlas/css                      Editor and player styles from RPGAtlas
public/atlas/js                       RPGAtlas engine, editor, data, assets, plugins, and SFX
public/atlas/img                      Shared RPGAtlas image assets and custom asset folders
config/nativephp.php                  NativePHP desktop configuration
app/Providers/NativeAppServiceProvider.php
```

## Development commands

```bash
npm run dev            # Vite development server
npm run build          # Production frontend build
composer run dev       # Laravel server, queue listener, logs, and Vite
composer run native:dev # NativePHP desktop app plus Vite
php artisan test       # PHP test suite
```

## License

The RPGAtlas engine runtime in `public/atlas` is free software licensed under the
**GNU General Public License v3.0 or later**; see `public/atlas/LICENSE`.

The Laravel application scaffold and integration code in this repository follows the license declared
by this project. Games created with RPGAtlas remain yours. Exported games bundle the GPL-licensed
engine runtime, and the exported HTML/JavaScript remains readable source.
