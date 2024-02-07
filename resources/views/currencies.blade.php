<!DOCTYPE html>
<html>
<head>
    <title>Ulubione Waluty</title>
</head>
<body>
    <h1>Nazwy Walut</h1>
    <ul>
        @foreach($currencyNames as $name)
            <li>{{ $name }}</li>
        @endforeach
    </ul>
</body>
</html>
