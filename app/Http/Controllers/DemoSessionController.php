<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoSessionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $demo = User::query()->where('is_demo', true)->firstOrFail();

        Auth::login($demo);
        $request->session()->regenerate();

        return redirect()->route('journalists.page')->with('status', 'Welcome to the read-only demo desk.');
    }
}
