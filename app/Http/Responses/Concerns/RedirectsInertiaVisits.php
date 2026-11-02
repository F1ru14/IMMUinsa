<?php

namespace App\Http\Responses\Concerns;

use Inertia\Inertia;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

trait RedirectsInertiaVisits
{
    /**
     * The Fortify redirect name used to resolve the default destination.
     */
    protected string $redirectName;

    /**
     * Create an HTTP response that represents the object.
     *
     * Halaman tujuan (dashboard, biodata, dsb.) masih dirender dengan Blade,
     * sehingga kunjungan Inertia harus diubah menjadi full page load. Tanpa
     * ini Inertia akan menampilkan respons HTML tersebut di dalam dialog error.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toResponse($request): Response
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(
                redirect()->intended(Fortify::redirects($this->redirectName))->getTargetUrl()
            );
        }

        return parent::toResponse($request);
    }
}
