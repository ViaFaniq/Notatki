<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="wiek">
        <button>Szukaj</button>
    </form>
</body>
</html>

<?php
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $wiek = (int) $_POST["wiek"];
    $sql = "SELECT * FROM uczniowie WHERE wiek=$wiek";
    $wynik = mysqli_query($polaczenie, $sql);

    while ($wiersz = mysqli_fetch_assoc($wynik)) {
        echo $wiersz["imie"] . " " . $wiersz["nazwisko"];
        echo "<br>";
    }
}
?>