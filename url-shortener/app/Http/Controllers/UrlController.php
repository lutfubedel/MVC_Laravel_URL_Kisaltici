<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function shorten(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $originalUrl = $request->input('url');
        $url = Url::where('original_url', $originalUrl)->first();

        if ($url) {
            return redirect('/')->with('shortened_url', url('/' . $url->short_code));
        }

        $shortCode = Str::random(12);

        while (Url::where('short_code', $shortCode)->exists()) {
            $shortCode = Str::random(12);
        }

        $url = Url::create([
            'original_url' => $originalUrl,
            'short_code' => $shortCode
        ]);

        return redirect('/')->with('shortened_url', url('/' . $shortCode));
    }

    public function redirect($code)
    {
        $url = Url::where('short_code', $code)->firstOrFail();
        return redirect($url->original_url);
    }
}
