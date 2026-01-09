<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        // Fetch the exchange rate, defaulting to 4000 if not found
        // Assumes a 'settings' table with 'key' and 'value' columns
        $exchangeRate = Setting::where('key', 'exchange_rate')->value('value') ?? 4000;

        return view('settings.edit', compact('exchangeRate'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'exchange_rate' => 'required|numeric|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'exchange_rate'],
            ['value' => $request->exchange_rate]
        );

        return redirect()->route('settings.edit')->with('success', __('Settings updated successfully.'));
    }

    public function switchCurrency($currency)
    {
        if (in_array($currency, ['USD', 'KHR'])) {
            session(['currency' => $currency]);
        }
        return back();
    }
}