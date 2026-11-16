<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="text" name="imie">
        <input type="text" name="nazwisko">
        <input type="number" name="wiek">
        <button>Dodaj ucznia</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if (!$polaczenie) {
    echo "Nie można połaczyć się z bazą danych";
}

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
    $sql = "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($polaczenie, $sql);
    $bind = mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
    $execute = mysqli_stmt_execute($stmt);
    
    if ($execute) {
        echo "Dodano ucznia";
    } else {
        echo "Nie dodano ucznia";
    }

    mysqli_stmt_close($stmt);
}

$sql1 = "SELECT * FROM uczniowie ORDER BY nazwisko ASC";
$stmt1 = mysqli_prepare($polaczenie, $sql1);
$execute1 = mysqli_stmt_execute($stmt1);
$wynik1 = mysqli_stmt_get_result($stmt1);

if ($execute1) {
    while ($wiersz = mysqli_fetch_assoc($wynik1)) {
        echo "<br>";
        echo $wiersz["imie"] . " " . $wiersz["nazwisko"] . " " . $wiersz["wiek"];
    }
}

mysqli_stmt_close($stmt1);
mysqli_close($polaczenie);  
?>