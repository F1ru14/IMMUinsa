<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sumber Berita Terkini
    |--------------------------------------------------------------------------
    |
    | Landing page menampilkan berita terkini yang diambil dari situs resmi
    | IMM UIN Sunan Ampel Surabaya (Blogger). Feed RSS ini tidak memerlukan
    | kredensial apa pun.
    |
    */

    'feed_url' => env('NEWS_FEED_URL', 'https://www.immuinsasby.com/feeds/posts/default?alt=rss'),

    'item_limit' => (int) env('NEWS_ITEM_LIMIT', 3),

    /*
    |--------------------------------------------------------------------------
    | Kategori yang Tidak Ditampilkan
    |--------------------------------------------------------------------------
    |
    | Konten fiksi (cerpen/puisi) tetap tersedia di situs sumber, tetapi tidak
    | ditampilkan pada kartu "Berita Terkini".
    |
    */

    'exclude_categories' => ['Cerpen', 'Puisi'],

    /*
    |--------------------------------------------------------------------------
    | Kategori yang Diprioritaskan sebagai Badge
    |--------------------------------------------------------------------------
    |
    | Item feed bisa punya beberapa kategori sekaligus (mis. nama komisariat
    | dan jenis konten). Badge kartu memakai jenis konten lebih dulu.
    |
    */

    'preferred_categories' => ['Kabar', 'Artikel', 'Opini/Esai', 'Respon', 'Agama', 'Tarikh', 'BULETIN'],

    'cache_minutes' => (int) env('NEWS_CACHE_MINUTES', 30),

];
