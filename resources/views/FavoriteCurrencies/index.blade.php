@section('content')
<div class="container">

    <div>
        <h1>Nazwy Walut</h1>
    </div>
    <div>
        <form action="{{ route('favorite-currencies.store') }}" method="POST">
            @csrf
            <select name="currency_code">
                @foreach($currencies as $currency)
                <option value="{{ $currency['code'] }}">{{ $currency['currency'] }} ({{ $currency['code'] }})</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Dodaj walutę</button>
        </form>
    </div>

    <hr />

    <div>
        <h3>Ulubione waluty</h3>
    </div>

    <div class="mt-5">
        @if($favoriteCurrencies->count() > 0)
        <table class="tabel table-striped table-hover table-responsive  ">
            <thead>
                <tr>
                    <th class="col-5">Nazwa</th>
                    <th class="col-2">Kod</th>
                    <th class="col-2">Kupno</th>
                    <th class="col-2">Sprzedaż</th>
                    <th class="col-3">Akcja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($favoriteCurrencies as $currency)
                <tr>
                    <td>{{ $currency['name'] }}</td>
                    <td>{{ $currency['code'] }}</td>
                    <td>{{ $currency['bid'] }}</td>
                    <td>{{ $currency['ask'] }}</td>
                    <td>
                        <form action="{{ route('favorite-currencies.destroy', $currency['code']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-warning" type="submit">Usuń</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr />
        <div class="mt-3">
            <form action="{{ route('favorite-currencies.destroy-all') }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Usuń wszystkie waluty</button>
            </form>

            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger mt-3">
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

        </div>
        @else
        <div class="alert alert-info" role="alert">
            Brak ulubionych walut
        </div>
        @endif
    </div>



</div>
@endsection
