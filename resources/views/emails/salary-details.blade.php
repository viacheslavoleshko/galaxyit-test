<!DOCTYPE html>
<html>

<head>
    <title>Розрахунок заробітної плати</title>
</head>

<body>
    <h1>{{ $user->firstname }} {{ $user->lastname }},</h1>
    <p>Ваша поточна зарплата становить {{ $user->salary }} грн, не забудьте отримати її 1-го {{ now()->addMonth()->locale('uk')->getTranslatedMonthName('Do MMMM') }}</p>
</body>

</html>