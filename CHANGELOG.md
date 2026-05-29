# Changelog

All notable changes to `ptplugins/filament-pikaday` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and this project adheres to [Semantic Versioning](https://semver.org/).

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
