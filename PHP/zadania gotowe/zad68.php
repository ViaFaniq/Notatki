<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <a>Podaj ID</a>
        <input name="id">
        <br>
        <a>Podaj Nowe Nazwisko</a>
        <input name="nazwisko">
        <br>
        <button>Zmień Nazwisko</button>
    </form>    
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $nazwisko = $_POST["nazwisko"];
    $sql = "UPDATE uczniowie SET nazwisko='$nazwisko' WHERE id=$id";
    $wynik = mysqli_query($polaczenie, $sql);
    
    echo "Wykonano Zapytanie!";
}
?>