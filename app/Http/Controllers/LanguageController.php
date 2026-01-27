<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'lo'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
