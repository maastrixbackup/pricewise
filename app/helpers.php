<?php
use App\Models\Setting;
use App\Models\WebsiteSetting;
use App\Models\Banner;

if (!function_exists('getSettings')) {
    function getSettings()
    {
        $settings = Setting::all();
        return $settings;
    }
}
if (!function_exists('getBanners')) {
    function getBanners($page)
    {
        $banners = Banner::where('page', $page)->where('type', 'banner')->get();
        return $banners;
    }
}
if (!function_exists('getSliders')) {
    function getSliders($page)
    {
        $sliders = Banner::where('page', $page)->where('type', 'slider')->get();
        return $sliders;
    }
}