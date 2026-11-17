<?php 
$check = $_GET["dodano"];
if ($check == 1) {
    echo "Dodano ucznia";
} else {
    $imie = trim($_POST["imie"]);
    $nazwisko = trim($_POST["nazwisko"]);
    $wiek = $_POST["wiek"];
    $dlugosc = mb_strlen($imie, "UTF-8");
    $dlugoscnazwisko = mb_strlen($nazwisko, "UTF-8");

    $polaczenie = mysqli_connect("localhost", "root", "", "szkola");
    $sql = "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($polaczenie, $sql);


    if ($imie == "" || $nazwisko == "") {
        echo "Podaj Imie i Nazwisko";
    } else if ($dlugosc > 50 || $dlugoscnazwisko > 50) {
        echo "Przekroczenie długości ciągu znaków (50)";
    } else if (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
        echo "Wiek nie jest liczba całkowitą";
    } else if ($wiek < 14 || $wiek > 100) {
        echo "Wiek nie spełnia wymagań (od 14 do 100)";
    } else {
        $bind = mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
        $wykonaj = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: zad106.php?dodano=1");
        exit;
    }
}
?>

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
