<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function edit()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'អ្នកមិនមានសិទ្ធិចូលប្រើផ្នែកនេះទេ។');
        }

        $exchangeRate = Setting::getExchangeRate();
        return view('settings.edit', compact('exchangeRate'));
    }

    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'អ្នកមិនមានសិទ្ធិចូលប្រើផ្នែកនេះទេ។');
        }

        $request->validate([
            'exchange_rate' => 'required|numeric|min:1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'exchange_rate'],
            ['value' => $request->exchange_rate]
        );

        // Clear the cache so the new value is used immediately
        Cache::forget('exchange_rate');

        return redirect()->route('settings.edit')->with('success', 'អត្រាប្តូរប្រាក់ត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ');
    }

    public function switchCurrency($currency)
    {
        if (in_array($currency, ['USD', 'KHR'])) {
            session(['currency' => $currency]);
        }
        return back();
    }
}