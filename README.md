# Filament Pikaday

<p align="center">
  <img src="./screenshot.png" alt="Filament Pikaday — lightweight date picker field for a Filament form" width="100%" class="filament-hidden">
</p>

> A lightweight [Pikaday](https://github.com/Pikaday/Pikaday) date picker field for [FilamentPHP](https://filamentphp.com/) **v3, v4, and v5**. No jQuery, no moment.js — just a clean, fast calendar input with full i18n.

Single codebase across all three Filament major versions — same field, same API.

**🎯 [Try it live · ptplugins.com/demo/pikaday](https://ptplugins.com/demo/pikaday)** — no signup, just open the calendar.

## Why

Filament's built-in date picker is great, but it pulls in a heavier JS footprint. When all you need is a fast, dependency-light calendar — no jQuery, no moment.js — `PikadayDatePicker` is a drop-in field that just works, with min/max dates, year bounds, first-day-of-week, and locale-aware month/weekday names out of the box.

The calendar ships with **light and dark mode** styling that follows Filament's theme automatically — no extra setup.

## Installation

```bash
composer require ptplugins/filament-pikaday
```

The package auto-discovers its service provider and registers its assets. No manual registration needed.

## Quick Start

Use the field anywhere you'd use a Filament form field:

```php
use PtPlugins\FilamentPikaday\Fields\PikadayDatePicker;

PikadayDatePicker::make('published_at')
    ->label('Published at')
    ->minDate('2020-01-01')
    ->maxDate(now())
    ->firstDayOfWeek(1)          // 1 = Monday (default), 0 = Sunday
    ->displayFormat('DD/MM/YYYY');
```

The stored value is normalized to `Y-m-d`; the visible input follows `displayFormat()`.

## ISO storage, localized display

This is the key difference from most Filament date fields. **The value bound to your model is always an ISO `Y-m-d` string** (`2026-06-15`) — locale-independent, sortable, and safe to cast to `date` / compare in SQL. The *display* is localized purely on the front end via `displayFormat()` and the calendar i18n.

```php
PikadayDatePicker::make('published_at')
    ->displayFormat('DD/MM/YYYY')   // user sees 15/06/2026
    ->locale('hr');                 // calendar in Croatian
// → model / database always stores "2026-06-15"
```

Why it matters: many date pickers persist whatever the *display* format is (`15/06/2026` vs `06/15/2026`), which then breaks parsing, sorting, and cross-locale data the moment two users have different formats. Here the storage format never changes — only what the user *sees* does. No ambiguity, no per-locale migration headaches, no `d/m` vs `m/d` bugs.

## Configuration API

| Method | Description |
|---|---|
| `minDate($date)` | Earliest selectable date. Accepts `Carbon`, a date string, or a closure. |
| `maxDate($date)` | Latest selectable date. Accepts `Carbon`, a date string, or a closure. |
| `minYear($year)` | Lower bound of the year dropdown (default `2024`). |
| `maxYear($year)` | Upper bound of the year dropdown (default current year + 1). |
| `firstDayOfWeek($day)` | First day of the week — `0` (Sunday) … `6`. Default `1` (Monday). |
| `displayFormat($format)` | Display pattern. Default `DD/MM/YYYY` (UK style). |
| `locale($locale)` | Calendar locale for month / weekday names. Defaults to the application locale. |
| `i18n($overrides)` | Override individual navigation strings or labels (merged over locale defaults). |

Prefix / suffix affixes (icons, labels, actions) and a clear button are supported out of the box, just like Filament's native fields.

## Internationalization

Month and weekday names are pulled from Carbon, so **every locale Carbon ships works automatically** — the picker follows your application locale with **English as the default**:

```php
PikadayDatePicker::make('starts_on')
    ->locale('hr');   // Croatian month/weekday names
```

Navigation labels (`Previous Month` / `Next Month`) are translatable via the published translations (English + Croatian bundled; other locales fall back to English):

```bash
php artisan vendor:publish --tag=filament-pikaday-translations
```

Need a one-off tweak? Override individual strings without redefining the whole set:

```php
PikadayDatePicker::make('starts_on')
    ->i18n([
        'previousMonth' => 'Prethodni',
        'nextMonth'     => 'Sledeći',
    ]);
```

## Requirements

- PHP 8.1+
- FilamentPHP **3.x, 4.x, or 5.x** (single codebase across all three)

## License

MIT
