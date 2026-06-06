# Changelog

All notable changes to `ptplugins/filament-pikaday` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.2] - 2026-06-07

### Added
- README section documenting the ISO `Y-m-d` storage guarantee: the bound model value is always a locale-independent ISO date, while `displayFormat()` and the calendar i18n localize only what the user sees.

## [1.1.1] - 2026-06-07

### Added
- `README.md` with usage, configuration API, and i18n docs. Hero image carries the `filament-hidden` class so it doesn't duplicate the listing banner on filamentphp.com.

## [1.1.0] - 2026-06-07

### Changed
- Widened the `filament/filament` constraint to `^3.0 || ^4.0 || ^5.0`. The single codebase now installs on Filament 3, 4, and 5. Verified against Filament v5.6.6 (`view:cache` compile + field runtime).

## [1.0.0] - 2026-05-29

### Added
- Initial public release under `ptplugins/filament-pikaday` (MIT).
- `PikadayDatePicker` form field for FilamentPHP, backed by [Pikaday](https://github.com/Pikaday/Pikaday) — no jQuery, no moment.js.
- Configuration API:
  - `minDate()` / `maxDate()` — accept `Carbon`, date string, or closure.
  - `minYear()` / `maxYear()` — bound the year dropdown.
  - `firstDayOfWeek()` — 0 (Sunday) … 6.
  - `displayFormat()` — display pattern (default `DD/MM/YYYY`, UK style).
  - `locale()` — calendar locale (defaults to the application locale). Month and weekday names are pulled from Carbon — every locale Carbon ships works out of the box, so the picker is multilingual with **English as the default**.
  - `i18n()` — override individual navigation strings or labels.
- Navigation labels (`Previous Month` / `Next Month`) are translatable via the published `filament-pikaday::pikaday` translations (English + Croatian bundled; other locales fall back to English).
- State is normalized to `Y-m-d` on hydration; display value follows `displayFormat()`.
- Clear button and full prefix / suffix affix support (icons, labels, actions).
