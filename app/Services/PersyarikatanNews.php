<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

class PersyarikatanNews
{
    private const CACHE_KEY = 'persyarikatan.news';

    private const BACKUP_CACHE_KEY = 'persyarikatan.news.backup';

    /**
     * Ambil berita terkini dari feed resmi IMM UINSA.
     *
     * @return array<int, array{title: string, url: string, source: string, date: string, excerpt: string, category: string, image: string|null}>
     */
    public function latest(): array
    {
        $cached = Cache::get(self::CACHE_KEY);

        if (is_array($cached)) {
            return $cached;
        }

        try {
            $items = $this->fetch();
        } catch (Throwable $exception) {
            Log::warning('Gagal mengambil feed berita persyarikatan.', [
                'message' => $exception->getMessage(),
            ]);

            $items = [];
        }

        if ($items !== []) {
            $ttl = now()->addMinutes((int) config('news.cache_minutes', 30));

            Cache::put(self::CACHE_KEY, $items, $ttl);
            Cache::put(self::BACKUP_CACHE_KEY, $items, now()->addDay());

            return $items;
        }

        $backup = Cache::get(self::BACKUP_CACHE_KEY);

        return is_array($backup) ? $backup : [];
    }

    /**
     * @return array<int, array{title: string, url: string, source: string, date: string, excerpt: string, category: string, image: string|null}>
     */
    private function fetch(): array
    {
        $response = Http::timeout(4)
            ->accept('application/rss+xml, application/xml, text/xml')
            ->get((string) config('news.feed_url'));

        if (! $response->successful()) {
            return [];
        }

        $xml = simplexml_load_string($response->body());

        if (! $xml instanceof SimpleXMLElement || ! isset($xml->channel->item)) {
            return [];
        }

        $excluded = array_map('strtolower', (array) config('news.exclude_categories', []));
        $limit = max(1, (int) config('news.item_limit', 3));
        $items = [];

        foreach ($xml->channel->item as $item) {
            $categories = [];

            foreach ($item->category as $category) {
                $categories[] = trim((string) $category);
            }

            if (array_intersect(array_map('strtolower', $categories), $excluded) !== []) {
                continue;
            }

            $media = $item->children('media', true);
            $image = isset($media->thumbnail)
                ? (string) $media->thumbnail->attributes()->url
                : null;

            $items[] = [
                'title' => $this->cleanText((string) $item->title),
                'url' => trim((string) $item->link),
                'source' => 'immuinsasby.com',
                'date' => $this->formatDate((string) $item->pubDate),
                'excerpt' => Str::limit($this->cleanText((string) $item->description), 140),
                'category' => $this->pickCategory($categories),
                'image' => $image !== null
                    ? str_replace('/s72-c/', '/s400/', $image)
                    : null,
            ];

            if (count($items) === $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * @param  array<int, string>  $categories
     */
    private function pickCategory(array $categories): string
    {
        $preferred = array_map('strtolower', (array) config('news.preferred_categories', []));

        foreach ($categories as $category) {
            if (in_array(strtolower($category), $preferred, true)) {
                return $category;
            }
        }

        return $categories[0] ?? 'Berita';
    }

    private function cleanText(string $value): string
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stripped = strip_tags($decoded);
        $decoded = html_entity_decode($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $decoded) ?? '');
    }

    private function formatDate(string $value): string
    {
        try {
            return CarbonImmutable::parse($value)
                ->timezone('Asia/Jakarta')
                ->translatedFormat('d M Y');
        } catch (Throwable) {
            return '';
        }
    }
}
