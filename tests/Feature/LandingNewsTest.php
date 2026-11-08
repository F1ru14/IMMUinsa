<?php

use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

function fakeNewsFeed(): string
{
    $item = function (string $title, string $slug, string $date, array $categories, string $description): string {
        $categoryTags = implode('', array_map(
            fn (string $category): string => "<category>{$category}</category>",
            $categories,
        ));

        return <<<XML
        <item>
            <title>{$title}</title>
            <link>https://www.immuinsasby.com/2026/11/{$slug}.html</link>
            <pubDate>{$date}</pubDate>
            {$categoryTags}
            <description>{$description}</description>
            <media:thumbnail url="https://blogger.googleusercontent.com/img/{$slug}/s72-c/foto.jpg" />
        </item>
        XML;
    };

    return <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
            <channel>
                {$item('Berita Pertama', 'pertama', 'Wed, 04 Nov 2026 03:48:57 +0000', ['Al-Farabi', 'Kabar'], '&lt;p&gt;Ringkasan &lt;b&gt;berita&lt;/b&gt; pertama.&lt;/p&gt;')}
                {$item('Berita Kedua', 'kedua', 'Tue, 03 Nov 2026 03:48:57 +0000', ['Artikel'], '&lt;p&gt;Ringkasan berita kedua.&lt;/p&gt;')}
                {$item('Cerita Fiksi', 'fiksi', 'Mon, 02 Nov 2026 03:48:57 +0000', ['Cerpen'], '&lt;p&gt;Konten fiksi.&lt;/p&gt;')}
                {$item('Berita Ketiga', 'ketiga', 'Sun, 01 Nov 2026 03:48:57 +0000', ['Opini/Esai'], '&lt;p&gt;Ringkasan berita ketiga.&lt;/p&gt;')}
                {$item('Berita Keempat', 'keempat', 'Sat, 31 Oct 2026 03:48:57 +0000', ['Kabar'], '&lt;p&gt;Ringkasan berita keempat.&lt;/p&gt;')}
            </channel>
        </rss>
        XML;
}

test('landing page menampilkan berita terkini dari feed persyarikatan', function () {
    Http::fake([
        'immuinsasby.com/*' => Http::response(fakeNewsFeed(), 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('welcome')
        ->has('news', 3)
        ->where('news.0.title', 'Berita Pertama')
        ->where('news.0.url', 'https://www.immuinsasby.com/2026/11/pertama.html')
        ->where('news.0.category', 'Kabar')
        ->where('news.0.excerpt', 'Ringkasan berita pertama.')
        ->where('news.0.image', 'https://blogger.googleusercontent.com/img/pertama/s400/foto.jpg')
        ->where('news.1.title', 'Berita Kedua')
        ->where('news.2.title', 'Berita Ketiga')
    );
});

test('konten fiksi tidak ditampilkan pada berita terkini', function () {
    Http::fake([
        'immuinsasby.com/*' => Http::response(fakeNewsFeed(), 200),
    ]);

    $response = $this->get(route('home'));

    $response->assertInertia(fn (Assert $page) => $page
        ->has('news', 3)
        ->where('news', fn ($news) => collect($news)->pluck('title')->doesntContain('Cerita Fiksi'))
    );
});

test('landing page tetap tampil saat feed berita gagal diakses', function () {
    Http::fake([
        'immuinsasby.com/*' => Http::response('Server Error', 500),
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('welcome')
        ->has('news', 0)
    );
});

test('feed berita hanya diambil sekali selama cache masih hangat', function () {
    Http::fake([
        'immuinsasby.com/*' => Http::response(fakeNewsFeed(), 200),
    ]);

    $this->get(route('home'))->assertOk();
    $this->get(route('home'))->assertOk();

    $feedRequests = Http::recorded(
        fn ($request): bool => str_contains($request->url(), 'immuinsasby.com'),
    );

    expect($feedRequests)->toHaveCount(1);
});
