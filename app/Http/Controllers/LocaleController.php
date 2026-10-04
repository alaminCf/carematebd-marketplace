<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch application locale between English (en) and Bangla (bn).
     */
    public function switch(Request $request, string $lang): RedirectResponse
    {
        if (in_array($lang, ['en', 'bn'], true)) {
            session(['locale' => $lang]);
            cookie()->queue(cookie()->forever('caremate_locale', $lang));
        }

        return redirect()->back();
    }
}
