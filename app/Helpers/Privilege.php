<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Privilege
{
    private static ?array $cache = null;

    public static function get(?string $key = null): mixed
    {
        if (static::$cache === null) {
            $uid = session('uid');
            if (!$uid) {
                static::$cache = [];
            } else {
                $raw = DB::table('users')->where('uid', $uid)->value('privilege');
                static::$cache = is_string($raw) ? (json_decode($raw, true) ?? []) : ($raw ?? []);
            }
        }

        return $key === null ? static::$cache : data_get(static::$cache, $key);
    }

    /**
     * Unlisted stock data — the dashboard, the editor and every stock endpoint.
     * Routes also sit behind privilege:unlisted, so this narrows that down.
     */
    public static function canManageStocks(): bool
    {
        return !empty(static::get('user_master')) || !empty(static::get('unlisted.unlisted_stocks'));
    }
}
