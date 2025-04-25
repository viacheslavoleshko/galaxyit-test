<!DOCTYPE html>
<html>

<head>
    <title>Водій вийшов на пенсію</title>
</head>

<body>
    <p>Водій {{ $user->firstname }} {{ $user->lastname }} сьогодні вийшов не пенсію,
        @if ($user->bus)
            автобус, номерний знак {{ $user->bus->license_plate }} залишився без водія.
        @else
            але у нього не було закріпленого автобуса.
        @endif
</body>

</html>