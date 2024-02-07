<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function showCurrencies()
    {
        $response = Http::get('https://api.nbp.pl/api/exchangerates/tables/C/');
        $currencies = $response->json()[0]['rates'];

        $currencyNames = collect($currencies)->map(function ($currency) {
            return $currency['currency'];
        });

        return view('currencies', compact('currencyNames'));
    }
}
