# Filament Pikaday — Listing Submission

Submit via **https://filamentphp.com/author** dashboard (not a PR).
Banner image to upload is `screenshot.png` (2560×1440) in this same folder.

---

## Fields

| Field | Value |
|---|---|
| **Name** | Filament Pikaday |
| **Package (Packagist)** | `ptplugins/filament-pikaday` |
| **Tagline** | A date picker that just works. No jQuery, no moment.js. |
| **Repository** | https://github.com/ptplugins/filament-pikaday |
| **Packagist** | https://packagist.org/packages/ptplugins/filament-pikaday |
| **License** | MIT (Free) |
| **Categories** | Form Field / Form Component |
| **Keywords** | filament, datepicker, pikaday, form-field, i18n |
| **Thumbnail / Banner** | `screenshot.png` (this folder) |
| **Compatible with** | Filament 3, 4, 5 |

---

## Description

A lightweight, dependency-light Pikaday date picker field for FilamentPHP. Drop-in `PikadayDatePicker` form field with min/max dates, configurable year range, first-day-of-week, and full i18n (English + Croatian out of the box). No jQuery, no moment.js — just a clean, fast calendar input. Light & dark mode included. Single package for Filament 3, 4, and 5.

Unlike most date fields, the value is **always stored as an ISO `Y-m-d` string** (locale-independent, sortable, SQL-safe) while `displayFormat()` and the calendar localize only what the user sees — no `d/m` vs `m/d` ambiguity, no per-locale data bugs.

---

## Install (for the listing instructions)

```bash
composer require ptplugins/filament-pikaday
```

```php
use PtPlugins\FilamentPikaday\Fields\PikadayDatePicker;

PikadayDatePicker::make('published_at')
    ->minDate('2020-01-01')
    ->maxDate(now())
    ->firstDayOfWeek(1)
    ->displayFormat('DD/MM/YYYY');
```

---

## Status

- ✅ Packagist: live, versions `1.0.0` + `1.1.0` (`1.1.0` = Filament 3/4/5).
- ✅ GitHub release: https://github.com/ptplugins/filament-pikaday/releases/tag/1.1.0
- ✅ Banner rendered: `screenshot.png` (2560×1440).
