<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY_PREFIX = 'setting:';

    private const CACHE_TTL_SECONDS = 3600;

    /**
     * Get a setting's value, cast to its declared type.
     *
     * Cached rather than queried on every read (Step 7, Performance) —
     * Labor Rate specifically gets read on every Production cost
     * calculation from Sprint 5 onward, so this matters beyond Phase 1's
     * current small scale.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(
            self::CACHE_KEY_PREFIX.$key,
            self::CACHE_TTL_SECONDS,
            function () use ($key, $default) {
                $setting = Setting::where('key', $key)->first();

                if ($setting === null) {
                    return $default;
                }

                return $this->cast($setting->value, $setting->type);
            }
        );
    }

    /**
     * Set a setting's value, creating it if it doesn't already exist.
     *
     * Clears the cached value immediately so the next read reflects the
     * change right away, not after the cache TTL expires.
     */
    public function set(string $key, mixed $value, string $type = 'string', ?string $group = null): Setting
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group]
        );

        Cache::forget(self::CACHE_KEY_PREFIX.$key);

        return $setting;
    }

    /**
     * Cast a raw stored value to its declared type.
     */
    private function cast(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'integer' => (int) $value,
            'decimal' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
