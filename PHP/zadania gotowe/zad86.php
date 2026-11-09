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
        <input type="text" name="nazwisko">
        <input type="number" name="wiek">
        <button>Dodaj Ucznia</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $wiek = (int) $_POST["wiek"];
    $sql = "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($polaczenie, $sql);
    $bind = mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
    $wykonaj = mysqli_stmt_execute($stmt);

    if ($wykonaj) {
        echo "Dodano ucznia";
    }
}
?>