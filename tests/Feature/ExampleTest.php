<?php

use Illuminate\Support\Facades\Http;

test('returns a successful response', function () {
    Http::fake();

    $response = $this->get(route('home'));

    $response->assertOk();
});
