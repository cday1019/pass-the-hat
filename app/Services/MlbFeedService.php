<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MlbFeedService
{
    /**
     * Fetch the latest plays for a given MLB game PK.
     *
     * @param string $gamePk
     * @return array
     */
    public function getLatestPlays(string $gamePk): array
    {
        $timestampCacheKey = "mlb_feed_timestamp_{$gamePk}";
        $playsCacheKey = "mlb_plays_{$gamePk}";

        $lastTimestamp = Cache::get($timestampCacheKey);

        // Efficiency Logic: Check /timestamps first
        $timestampUrl = "https://statsapi.mlb.com/api/v1.1/game/{$gamePk}/feed/live/timestamps";
        $response = Http::get($timestampUrl);

        if ($response->successful()) {
            $timestamps = $response->json();
            $latestTimestamp = empty($timestamps) ? null : end($timestamps);

            // Only fetch the full feed if the timestamp has changed
            if ($latestTimestamp && $lastTimestamp === $latestTimestamp) {
                return Cache::get($playsCacheKey, []);
            }

            // Fetch full feed with field filtering
            $fields = 'liveData,plays,allPlays,result,description,about,inning,halfInning,atBatIndex';
            $feedUrl = "https://statsapi.mlb.com/api/v1.1/game/{$gamePk}/feed/live?fields={$fields}";

            $feedResponse = Http::get($feedUrl);

            if ($feedResponse->successful()) {
                $data = $feedResponse->json();
                $allPlays = $data['liveData']['plays']['allPlays'] ?? [];

                // Sorting: most recent play first
                // Deduplication: using atBatIndex to ensure unique entries
                $sortedPlays = collect($allPlays)
                    ->filter(fn($play) => !empty($play['result']['description']))
                    ->unique(fn($play) => $play['about']['atBatIndex'] ?? null)
                    ->sortByDesc(fn($play) => $play['about']['atBatIndex'] ?? 0)
                    ->values()
                    ->toArray();

                Cache::put($timestampCacheKey, $latestTimestamp);
                Cache::put($playsCacheKey, $sortedPlays);

                return $sortedPlays;
            }
        }

        // Return cached plays if API calls fail
        return Cache::get($playsCacheKey, []);
    }
}
