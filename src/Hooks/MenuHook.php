<?php

namespace mbakgor\ExportData\Hooks;

use App\Plugins\Hooks\MenuEntryHook;

class MenuHook extends MenuEntryHook
{
    public string $view = 'export-data::menu.main';
}
