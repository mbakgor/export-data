<?php

namespace mbakgor\ExportData\Hooks;

use App\Plugins\Hooks\MenuEntryHook;

class MenuHook extends MenuEntryHook
{
    protected $attributes = [];

    public function __get($name)
    {
        if ($name == 'view') {
            return $this->attributes[$name] ?? null;
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($name == 'view') {
            $this->attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    public function __isset($name)
    {
        if ($name == 'view') {
            return isset($this->attributes[$name]);
        }

        return parent::__isset($name);
    }

    public function __unset($name)
    {
        if ($name == 'view') {
            unset($this->attributes[$name]);
        } else {
            parent::__unset($name);
        }
    }
}
