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
        <button name="akcja" value="dodaj">Dodaj ucznia</button>
    </form>    
    <br>    
    <form method="POST">
        <input type="text" name="fragment">
        <button name="akacja" value="szukaj">Szukaj</button>
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
        $bind = mysqli_stmt_bind_param($stmt, "ssi" , $imie, $nazwisko, $wiek);
        $wykonaj = mysqli_stmt_execute($stmt);
        $wynik = mysqli_stmt_get_result($stmt);
        $liczba = mysqli_stmt_num_rows($stmt);
        if ($liczba > 0) {
            while ($w = mysqli_fetch_assoc($wynik)) {
                echo $w["imie"] . " " . $w["nazwisko"];
                echo "<br>";
            }
        } else {
            echo "Brak Wyników";
        }

        mysqli_stmt_close($stmt);

        $fragment = $_POST["fragment"];
        $schemat = "%" . $fragment . "%";
        $sqlf = "SELECT * FROM uczniowie WHERE nazwisko LIKE ? ORDER BY nazwisko ASC";
        $stmtf = mysqli_prepare($polaczenie, $sqlf);
        $bindf = mysqli_stmt_bind_param($stmtf, "s", $fragment);
        $wykonajf = mysqli_stmt_execute($stmtf);
        $wynikf = mysqli_stmt_get_result($stmtf);
        $liczbaf = mysqli_stmt_num_rows($stmtf);
        if ($liczbaf > 0) {
            while ($wf = mysqli_fetch_assoc($wynikf)) {
                echo $wf["imie"] . " " . $wf["nazwisko"];
                echo "<br>";
            }
        } else {
            echo "Brak Wyników";
        }
        mysqli_stmt_close($stmtf);

    $sqlc = "SELECT COUNT(*) AS liczba FROM uczniowie";
    $stmtc = mysqli_prepare($polaczenie, $sqlc);
    $wykonajc = mysqli_stmt_execute($stmtc);
    $wynikc = mysqli_stmt_get_result($stmtc);
    echo "Liczba Uczniów: " . $wynikc;
    mysqli_stmt_close($stmtc);
    mysqli_close($polaczenie);
}
?>