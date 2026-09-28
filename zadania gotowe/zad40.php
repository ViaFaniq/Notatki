<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="imie" type="text">
        <input name="wiek" type="number">
        <input name="punkty" type="number">
        <button>Wyslij</button>
    </form>    
</body>
</html>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $_POST["imie"];
    $wiek = $_POST["wiek"];
    $punty = $_POST["punkty"];

    echo ($imie . " ma " . $wiek . " lat i zdobył " . $punty);
    if ($wiek >= 18) {
        echo (" Osoba pełnoletnia");
    } else {
        echo (" Osoba niepełnoletnia");
    }

    if ($punty >= 50) {
        echo (" wynik zaliczony");
    } else {
        echo (" wynik nie zaliczony");
    }
}

?>
