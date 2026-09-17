<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" id="LiczbaA" name="a" >Liczba A</input>
        <input type="number" id="liczbaB" name="b" >Liczba B</input>
        <button onclick="liczenie()">Policz</button>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $a = $_POST["a"];
        $b = $_POST["b"];
        $wynik = $a + $b;
        echo("Twój wynik równa się " . $wynik);
    }



?>