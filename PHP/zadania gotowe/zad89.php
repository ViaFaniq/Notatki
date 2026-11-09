<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="id">
        <button>Szukaj</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
$sql = "SELECT * FROM uczniowie WHERE id=?";
$stmt = mysqli_prepare($polaczenie, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $zapytanie = mysqli_stmt_bind_param($stmt, "i", $id);
        
    $wykonaj = mysqli_stmt_execute($stmt);
    $wynik = mysqli_stmt_get_result($stmt);
    $wiersz = mysqli_fetch_assoc($wynik);

    if ($wiersz) {
        echo $wiersz["imie"] . " " . $wiersz["nazwisko"];
        
    } else {
        echo "Nie znaleziono ucznia";
    };

    mysqli_stmt_close($stmt);
    mysqli_close($polaczenie);
};
?>