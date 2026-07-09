<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>body { font-family: sans-serif; line-height: 1.6; color: #333; } .label { font-weight: bold; }</style>
</head>
<body>
    <p>Nova poruka sa kontakt forme sajta {{ config('app.name') }}.</p>
    <p><span class="label">Ime:</span> {{ $senderName }}</p>
    <p><span class="label">Email:</span> {{ $senderEmail }}</p>
    <p><span class="label">Poruka:</span></p>
    <p>{{ nl2br(e($messageBody)) }}</p>
</body>
</html>
