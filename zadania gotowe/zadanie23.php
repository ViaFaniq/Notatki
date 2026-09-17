<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="imie" >Imie</input>
        <input type="number" name="wiek" >Wiek</input>
        <button>Przekarz dane</button>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $imie = $_POST["imie"];
        $wiek = $_POST["wiek"];
        echo($imie . " ma " . $wiek . " lat");
    }



?>