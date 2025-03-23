<?php

namespace App\Services;

use App\Models\SettingsConfig;

class SettingService
{
    public function getAllSettings()
    {
        return SettingsConfig::all()->pluck('value', 'key');
    }

    public function updateSetting($key, $value)
    {
        $setting = SettingsConfig::where('key', $key)->firstOrFail();
        $setting->value = $value;
        $setting->save();
        return $setting;
    }

    public function getSetting($key)
    {
        return SettingsConfig::where('key', $key)->firstOrFail()->value;
    }
}