<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="od">
        <input type="text" name="do">
        <button>Filtruj</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $od = (int) $_POST["od"];
    $do = (int) $_POST["do"];
    if ($od <= $do) {
        $sql = "SELECT * FROM uczniowie WHERE wiek BETWEEN ? AND ? ORDER BY wiek ASC";
        $stmt = mysqli_prepare($polaczenie, $sql);
        $powiazanie = mysqli_stmt_bind_param($stmt, "ii", $od, $do);
        $wykonaj = mysqli_stmt_execute($stmt);
        $wynik = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($wynik) > 0) {
            while ($w = mysqli_fetch_assoc($wynik)) {
                echo $w["imie"] . " " . $w["nazwisko"];
                echo "<br>";
            }
        } else {
            echo "Brak uczniów w podanym przedziale";
        }

    } else {
        echo "Nieprawidłowy powdział wieku";
    }
}
?>