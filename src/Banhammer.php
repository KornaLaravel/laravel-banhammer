<?php

namespace Mchev\Banhammer;

use Illuminate\Support\Facades\Cache;
use Mchev\Banhammer\Models\Ban;

class Banhammer
{
    public static function unbanExpired(): void
    {
        config('ban.model')::expired()->delete();
        Cache::put('banned-ips', IP::banned()->pluck('ip')->toArray());
    }

    public static function clear(): void
    {
        config('ban.model')::onlyTrashed()->forceDelete();
    }
}
