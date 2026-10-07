<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <p>Wpisz Imie</p>
        <input name="imie" type="text">
        <p>Wpisz Nazwisko</p>
        <input name="nazwisko" type="text">
        <p>Wpisz Wiek</p>
        <input name="wiek" type="number">
        <button>Dodaj Ucznia</button>
        <br>
    </form>
</body>
</html>

<?php
$polaczenie = mysqli_connect("127.0.0.1", "root", "", "szkola", 3306);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $wiek = $_POST["wiek"];

    $sql = "INSERT INTO uczniowie (imie, nazwisko, wiek)
    VALUES ('$imie', '$nazwisko', '$wiek')";

    $wyslij = mysqli_query($polaczenie, $sql);
}

$sqlWyswietl = "SELECT * FROM uczniowie";
$wynikWyswietl = mysqli_query($polaczenie, $sqlWyswietl);
while ($wiersz = mysqli_fetch_assoc($wynikWyswietl)) {
    echo " <br> ";
    echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . " - " . $wiersz["wiek"] . " lat";
    echo " <br> ";
}

?>