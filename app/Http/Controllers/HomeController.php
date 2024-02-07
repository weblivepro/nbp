<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $userCurrencies = Auth::user()->favoriteCurrencies->pluck('currency_code')->toArray();
        $response = Http::get('https://api.nbp.pl/api/exchangerates/tables/C/');
        $currencies = collect($response->json()[0]['rates']);


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

        return view('home', ['favoriteCurrencies' => $favoriteCurrencies, 'currencies' => $currencies]);

    }

    public function home()
    {
        $user = auth()->user();
        $favoriteCurrencies = $user->favoriteCurrencies; // Pobieranie ulubionych walut użytkownika

        return view('home', ['favoriteCurrencies' => $favoriteCurrencies]);
    }
}
