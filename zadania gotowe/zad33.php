<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="wiek">
        <button>Wyślij</button>
    </form>
</body>
</html>

<?php
    $wiek = $_POST["wiek"];
    if ($wiek >= 18) {
        echo ("Jesteś pełnoletni");
    } else {
        echo ("Nie jesteś pełnoletni");
    }

?>