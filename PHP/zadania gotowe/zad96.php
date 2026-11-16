<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="imie">
        <input type="text" name="nazwisko">
        <input type="number" name="wiek">
        <button>Dodaj ucznia</button>
    </form>
</body>
</html>

<?php 
$imie = trim($_POST["imie"]);
$nazwisko = trim($_POST["nazwisko"]);
$wiek = $_POST["wiek"];
$dlugosc = mb_strlen($imie, "UTF-8");
$dlugoscnazwisko = mb_strlen($nazwisko, "UTF-8");

if ($imie == "" || $nazwisko == "") {
    echo "Podaj Imie i Nazwisko";
} else if ($dlugosc > 50 || $dlugoscnazwisko > 50) {
    echo "Przekroczenie długości ciągu znaków (50)";
} else if (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
    echo "Wiek nie jest liczba całkowitą";
} else if ($wiek < 14 || $wiek > 100) {
    echo "Wiek nie spełnia wymagań (od 14 do 100)";
} else {
    echo "Dane gotowe do zapisania";
}
?>