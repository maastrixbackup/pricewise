<?php
use App\Models\Setting;
use App\Models\WebsiteSetting;


if (!function_exists('getSettings')) {
    function getSettings()
    {
        $settings = Setting::all();
        return $settings;
    }
}
