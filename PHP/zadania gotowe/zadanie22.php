<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" id="LiczbaA" name="cena" >Cena</input>
        <input type="number" id="liczbaB" name="ilosc" >Ilość</input>
        <button>Policz</button>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cena = $_POST["cena"];
        $ilosc = $_POST["ilosc"];
        $wynik = $cena * $ilosc;
        echo("Zapłasisz " . $wynik . " zł");
    }



?>