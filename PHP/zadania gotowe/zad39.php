<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="a" type="number">
        <input name="b" type="number">
        <input name="dzialanie">
        <button>Wyśij</button>
    </form>
</body>
</html>

<?php 
$a = $_POST["a"];
$b = $_POST["b"];
$dzialanie = $_POST["dzialanie"];

    if ($dzialanie == "+") {
        $wynik = $a + $b;
        echo ($wynik);
    } elseif ($dzialanie == "-") {
        $wynik = $a - $b;
        echo ($wynik);
    }
?>