<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Sikeres jelentkezés</title>
</head>
<body>
    <h1>Sikeres jelentkezés!</h1>

    <p>Kedves Felhasználó!</p>

    <p>Sikeresen jelentkeztél a következő eseményre:</p>

    <ul>
        <li><strong>Esemény neve:</strong> {{ $event->name }}</li>
        <li><strong>Dátum:</strong> {{ $event->date->format('Y.m.d. H:i') }}</li>
        <li><strong>Leírás:</strong> {{ $event->description ?? 'Nincs leírás.' }}</li>
    </ul>

    <p>Köszönjük a jelentkezésedet!</p>

    <p>Üdvözlettel,<br>{{ config('app.name') }}</p>
</body>
</html>
