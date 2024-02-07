<?php

namespace App\Http\Controllers;

use App\Models\FavoriteCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FavoriteCurrencyController extends Controller
{
    public function index()
    {
        $userCurrencies = Auth::user()->favoriteCurrencies;

        $response = Http::get('https://api.nbp.pl/api/exchangerates/tables/C/');
        $currencies = collect($response->json()[0]['rates']);

        //$favoriteCurrencies = $currencies->whereIn('code', $userCurrencies->pluck('currency_code'));

        $favoriteCurrencies = $currencies->filter(function ($currency) use ($userCurrencies) {
            return in_array($currency['code'], $userCurrencies);
        })->map(function ($currency) {
            return [
                'code' => $currency['code'],
                'name' => $currency['currency'],
                'ask' => $currency['ask'],
                'bid' => $currency['bid'],
            ];
        });

        $currencyNames = collect($currencies)->map(function ($currency) {
            return [
                'code' => $currency['code'],
                'name' => $currency['currency']
            ];
        });


        return view('favoriteCurrencies.index', compact('favoriteCurrencies', 'currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|max:3',
        ]);

        $user = auth()->user();
        $exists = $user->favoriteCurrencies()->where('currency_code', $request->currency_code)->exists();

        if (!$exists) {
            $user->favoriteCurrencies()->create([
                'currency_code' => $request->currency_code,
            ]);
        }

        return back();
    }


    public function destroy($currencyCode)
    {
        Auth::user()->favoriteCurrencies()->where('currency_code', $currencyCode)->delete();
        return back()->with('success', 'Usunięto ulubioną walutę.');
    }
    public function destroyAll()
    {
        try {
            $user = auth()->user();
            $user->favoriteCurrencies()->delete();
            Log::info('Usunięto wszystkie ulubione waluty użytkownika.', ['user_id' => $user->id]);
            return back()->with('success', 'Usunięto wszystkie ulubione waluty2.');
        } catch (\Exception $e) {
            Log::error('Błąd podczas usuwania ulubionych walut.', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->withErrors('Wystąpił błąd podczas usuwania ulubionych walut.');
        }
    }
}
