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
        <button>Test</button>
    </form>
</body>
</html>

<?php 
$imie = trim($_POST["imie"]);
$dlugosc = mb_strlen($imie, "UTF-8");
if ($imie == "") {
    echo "Podaj Imie!";
} else if ($dlugosc > 50) {
    echo "Imie jest za długie";
} else {
    echo "Poprawna długość imienia";
}
?>