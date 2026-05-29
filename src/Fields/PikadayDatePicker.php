<?php

namespace PtPlugins\FilamentPikaday\Fields;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Closure;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasPlaceholder;
use Filament\Forms\Components\Field;

class PikadayDatePicker extends Field
{
    use HasAffixes;
    use HasPlaceholder;

    protected string $view = 'filament-pikaday::pikaday';

    protected CarbonInterface|string|Closure|null $minDate = null;

    protected CarbonInterface|string|Closure|null $maxDate = null;

    protected int|Closure|null $minYear = null;

    protected int|Closure|null $maxYear = null;

    /** First day of week: 0 = Sunday, 1 = Monday (default, UK/ISO). */
    protected int|Closure $firstDayOfWeek = 1;

    /** Display format pattern. Default is UK style (DD/MM/YYYY). */
    protected string|Closure $displayFormat = 'DD/MM/YYYY';

    /** Calendar locale. Null = the application locale at render time. */
    protected string|Closure|null $locale = null;

    /**
     * Per-instance i18n overrides merged over the locale-derived defaults.
     *
     * @var array<string, mixed>
     */
    protected array $i18nOverrides = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (self $component, $state): void {
            if ($state === null) {
                return;
            }

            // Normalize any date format to Y-m-d for Alpine/Pikaday
            try {
                $component->state(Carbon::parse($state)->toDateString());
            } catch (\Exception) {
                // Already in correct format or unparseable
            }
        });
    }

    public function minDate(CarbonInterface|string|Closure|null $date): static
    {
        $this->minDate = $date;

        return $this;
    }

    public function maxDate(CarbonInterface|string|Closure|null $date): static
    {
        $this->maxDate = $date;

        return $this;
    }

    public function minYear(int|Closure|null $year): static
    {
        $this->minYear = $year;

        return $this;
    }

    public function maxYear(int|Closure|null $year): static
    {
        $this->maxYear = $year;

        return $this;
    }

    public function firstDayOfWeek(int|Closure $day): static
    {
        $this->firstDayOfWeek = $day;

        return $this;
    }

    public function displayFormat(string|Closure $format): static
    {
        $this->displayFormat = $format;

        return $this;
    }

    /**
     * Override the calendar locale (month / weekday names). Defaults to the
     * application locale. Accepts any locale Carbon understands ('en', 'hr', …).
     */
    public function locale(string|Closure|null $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Override individual i18n strings (e.g. ['previousMonth' => '…']). Merged
     * over the locale-derived defaults, so you can localize a single string
     * without redefining the whole month/weekday set.
     *
     * @param  array{months?: string[], weekdays?: string[], weekdaysShort?: string[], previousMonth?: string, nextMonth?: string}  $i18n
     */
    public function i18n(array $i18n): static
    {
        $this->i18nOverrides = array_merge($this->i18nOverrides, $i18n);

        return $this;
    }

    public function getMinDate(): ?string
    {
        return $this->resolveDate($this->minDate);
    }

    public function getMaxDate(): ?string
    {
        return $this->resolveDate($this->maxDate);
    }

    public function getMinYear(): int
    {
        return $this->evaluate($this->minYear) ?? 2024;
    }

    public function getMaxYear(): int
    {
        return $this->evaluate($this->maxYear) ?? ((int) date('Y') + 1);
    }

    public function getFirstDayOfWeek(): int
    {
        return $this->evaluate($this->firstDayOfWeek);
    }

    public function getDisplayFormat(): string
    {
        return $this->evaluate($this->displayFormat);
    }

    public function getLocale(): string
    {
        return $this->evaluate($this->locale) ?? app()->getLocale();
    }

    /**
     * Build the Pikaday i18n payload for the resolved locale. Month and weekday
     * names come from Carbon (works for every locale Carbon ships); the
     * navigation labels come from the package translations (English fallback).
     * Per-instance overrides from i18n() win last.
     *
     * @return array{previousMonth: string, nextMonth: string, months: string[], weekdays: string[], weekdaysShort: string[]}
     */
    public function getI18n(): array
    {
        $locale = $this->getLocale();

        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::create(2024, $month, 1)->locale($locale)->translatedFormat('F');
        }

        // Pikaday expects Sunday-first weekday arrays (index 0 = Sunday);
        // 2024-01-07 is a Sunday. firstDayOfWeek only rotates the display.
        $weekdays = [];
        $weekdaysShort = [];
        for ($offset = 0; $offset <= 6; $offset++) {
            $day = Carbon::create(2024, 1, 7)->addDays($offset)->locale($locale);
            $weekdays[] = $day->translatedFormat('l');
            $weekdaysShort[] = $day->translatedFormat('D');
        }

        $defaults = [
            'previousMonth' => __('filament-pikaday::pikaday.previous_month', [], $locale),
            'nextMonth' => __('filament-pikaday::pikaday.next_month', [], $locale),
            'months' => $months,
            'weekdays' => $weekdays,
            'weekdaysShort' => $weekdaysShort,
        ];

        return array_merge($defaults, $this->i18nOverrides);
    }

    /**
     * Resolve date value to Y-m-d string, handling Carbon, string, and Closure.
     */
    protected function resolveDate(CarbonInterface|string|Closure|null $date): ?string
    {
        $date = $this->evaluate($date);

        if ($date instanceof CarbonInterface) {
            return $date->toDateString();
        }

        if (is_string($date) && $date !== '') {
            try {
                return Carbon::parse($date)->toDateString();
            } catch (\Exception) {
                return $date;
            }
        }

        return null;
    }
}
