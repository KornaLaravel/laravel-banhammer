<?php

namespace Mchev\Banhammer;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class IP
{
    public static function ban(string|array $ips, array $metas = [], ?string $date = null): void
    {
        $bannedIps = self::getBannedIPsFromCache();

        foreach ((array) $ips as $ip) {
            if (! in_array($ip, $bannedIps)) {
                config('ban.model')::create([
                    'ip' => $ip,
                    'metas' => count($metas) ? $metas : null,
                    'expired_at' => $date,
                ]);
            }
        }
    }

    public static function unban(string|array $ips): void
    {
        $ips = (array) $ips;
        config('ban.model')::whereIn('ip', $ips)->delete();
        Cache::put('banned-ips', self::banned()->pluck('ip')->toArray());
    }

    public static function isBanned(string $ip): bool
    {
        return config('ban.model')::where('ip', $ip)
            ->notExpired()
            ->exists();
    }

    public static function banned(): Builder
    {
        return config('ban.model')::whereNotNull('ip')
            ->with('createdBy')
            ->notExpired();
    }

    public static function getBannedIPsFromCache(): array
    {
        return Cache::has('banned-ips')
            ? Cache::get('banned-ips')
            : self::banned()->pluck('ip')->unique()->toArray();
    }
}
