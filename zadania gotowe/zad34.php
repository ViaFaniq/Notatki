<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="a">
        <input type="number" name="b">
        <button>Wyślij</button>
    </form>
</body>
</html>

<?php 
    $a = $_POST["a"];
    $b = $_POST["b"];

    if ($a > $b) {
        echo ("Pierwsza liczba jest większa");
    } else {
        echo ("Pierwsza liczba nie jest większa");
    }

?>