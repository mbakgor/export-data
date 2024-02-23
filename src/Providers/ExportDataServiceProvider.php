<?php

namespace mbakgor\ExportData\Providers;

use Illuminate\Support\ServiceProvider;

use App\Plugins\Hooks\MenuEntryHook;
use App\Plugins\PluginManager;
use Illuminate\Support\Facades\Route;
use mbakgor\ExportData\Hooks\MenuHook;


class ExportDataServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    public function boot(PluginManager $manager)
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/export-data.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'export-data');
        $this->publishes([__DIR__.'/../resources/assets' => public_path('mbakgor/export-data'),], 'public');

        $name = 'export-data';
        $manager->publishHook($name,MenuEntryHook::class, MenuHook::class);
    }
}
