<?php

namespace PtPlugins\FilamentPikaday;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPikadayServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-pikaday';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        FilamentAsset::register([
            AlpineComponent::make('pikadayComponent', __DIR__.'/../dist/pikaday-component.js'),
            Css::make('pikaday-styles', __DIR__.'/../dist/pikaday.css'),
        ], 'ptplugins/filament-pikaday');
    }
}
