<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = ''): mixed
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
    }
}

if (! function_exists('flushSettings')) {
    function flushSettings(): void
    {
        Cache::flush();
    }
}
