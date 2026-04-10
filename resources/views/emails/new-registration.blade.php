<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Új jelentkezés</title>
</head>
<body>
    <h1>Új jelentkezés érkezett!</h1>

    <p>Kedves Szervező!</p>

    <p>Új jelentkezés érkezett az egyik eseményedre:</p>

    <ul>
        <li><strong>Esemény neve:</strong> {{ $event->name }}</li>
        <li><strong>Jelentkező neve:</strong> {{ $registrant->name }}</li>
        <li><strong>Jelentkező e-mail címe:</strong> {{ $registrant->email }}</li>
        <li><strong>Jelentkezés időpontja:</strong> {{ now()->format('Y.m.d. H:i') }}</li>
    </ul>

    <p>Üdvözlettel,<br>{{ config('app.name') }}</p>
</body>
</html>
