<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsRedirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(): View
    {
        return view('admin.redirects.index', [
            'redirects' => BdgsRedirect::query()->orderBy('from_url')->paginate(30),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_url' => ['required', 'string', 'max:500', 'unique:bdgs_redirects,from_url'],
            'to_url' => ['required', 'string', 'max:500'],
            'status_code' => ['required', 'in:301,302,307,308'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        BdgsRedirect::query()->create([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('status', 'Redirect created.');
    }

    public function destroy(BdgsRedirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return back()->with('status', 'Redirect deleted.');
    }
}
