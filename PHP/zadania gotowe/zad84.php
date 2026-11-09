<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input name="fragment">
        <button>Szukaj</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $fragment = $_POST["fragment"];
   $wzorzec = "%" . $fragment . "%";
   $sql = "SELECT * FROM uczniowie WHERE nazwisko LIKE ? ORDER BY nazwisko ASC";
   $stmt = mysqli_prepare($polaczenie, $sql);
   $powiazanie = mysqli_stmt_bind_param($stmt, "s", $wzorzec);
   $wykonaj = mysqli_stmt_execute($stmt);
   $wynik = mysqli_stmt_get_result($stmt);

   if (mysqli_num_rows($wynik) > 0) {
        while ($w = mysqli_fetch_assoc($wynik)) {
            echo $w["imie"] . " " . $w["nazwisko"];
            echo "<br>";
        }
   } else {
        echo "Brak uczniów";
   }
}
?>