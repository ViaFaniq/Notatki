<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

</body>
</html>

<?php
    if (isset($_GET["liczba"])) {
        $liczba = $_GET["liczba"];
        if ($liczba == 10) {
            echo ("Wynik wynosi 10");
        }
    } 
?>