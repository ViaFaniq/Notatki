<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="imie">
        <input type="number" name="a">
        <input type="number" name="b">
        <button>✅</button>
    </form>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $imie = $_POST["imie"];
        $a = $_POST["a"];
        $b = $_POST["b"];
        $wynik = $a + $b;
        echo ($imie . ", wyni to " . $wynik);
        if ($wynik == 10) {
            echo ("Wynik jest równy 10");
        }
    }
?>
