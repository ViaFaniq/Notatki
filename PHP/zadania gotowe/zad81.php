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
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
$id = 5;
$sql = "SELECT * FROM uczniowie WHERE id=?";
$stmt = mysqli_prepare($polaczenie, $sql);
$zapytanie = mysqli_stmt_bind_param($stmt, "i", $id);
$wykonaj = mysqli_stmt_execute($stmt);
$wynik = mysqli_stmt_get_result($stmt);
$wiersz = mysqli_fetch_assoc($wynik);
if ($wiersz) {
    echo $wiersz["imie"] . " " . $wiersz["nazwisko"];
} else {
    echo "Nie znaleziono ucznia";
};
?>