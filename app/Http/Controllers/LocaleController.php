<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    /**
     * Update the active locale for the session.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['fi', 'en']);
        $locale = $request->string('locale')->toString();

        if (! in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
        }

        session()->put('locale', $locale);
        App::setLocale($locale);

        return back();
    }
}
