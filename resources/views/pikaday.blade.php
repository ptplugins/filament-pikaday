@php
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
    $minDate = $getMinDate();
    $maxDate = $getMaxDate();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-load
        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('pikadayComponent', 'ptplugins/filament-pikaday') }}"
        x-data="pikadayComponent({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            minDate: @js($minDate),
            maxDate: @js($maxDate),
            minYear: @js($getMinYear()),
            maxYear: @js($getMaxYear()),
            firstDay: @js($getFirstDayOfWeek()),
            displayFormat: @js($getDisplayFormat()),
            i18n: @js($getI18n()),
            isDisabled: @js($isDisabled),
        })"
        wire:ignore
        wire:key="{{ $this->getId() }}.{{ $statePath }}.{{ $field::class }}.{{
            substr(md5(serialize([
                'isDisabled' => $isDisabled,
                'maxDate' => $maxDate,
                'minDate' => $minDate,
            ])), 0, 64)
        }}"
    >
        <x-filament::input.wrapper
            :disabled="$isDisabled"
            :inline-prefix="$isPrefixInline"
            :inline-suffix="$isSuffixInline"
            :prefix="$prefixLabel"
            :prefix-actions="$prefixActions"
            :prefix-icon="$prefixIcon"
            :prefix-icon-color="$getPrefixIconColor()"
            :suffix="$suffixLabel"
            :suffix-actions="$suffixActions"
            :suffix-icon="$suffixIcon"
            :suffix-icon-color="$getSuffixIconColor()"
            :valid="! $errors->has($statePath)"
        >
            <div class="flex min-w-0 flex-1 items-center">
                <x-filament::input
                    x-ref="input"
                    type="text"
                    :placeholder="$getPlaceholder() ?? ''"
                    autocomplete="off"
                    :disabled="$isDisabled"
                    readonly
                />
                @if (! $isDisabled)
                    <button
                        x-show="state"
                        x-on:click.prevent="clearDate()"
                        type="button"
                        tabindex="-1"
                        style="display: none;"
                        class="flex shrink-0 items-center justify-center px-2 mr-1 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none"
                    >
                        <x-filament::icon icon="heroicon-m-x-mark" class="h-4 w-4" />
                    </button>
                @endif
            </div>
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
