<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="number" name="id">
        <input type="number" name="wiek">
        <button>Zmień wiek</button>
    </form>
</body>
</html>

<?php 
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $wiek = (int) $_POST["wiek"];

    $sql = "UPDATE uczniowie SET wiek=? WHERE id=?";
    $stmt = mysqli_prepare($polaczenie, $sql);
    $bind = mysqli_stmt_bind_param($stmt, "ii", $wiek, $id);
    $wykonaj = mysqli_stmt_execute($stmt);
    $liczba = mysqli_stmt_affected_rows($stmt);

    if ($liczba > 0) {
        echo "Zmieniono dane ucznia.";
    } else {
        echo "Nie zmieniono żadnego rekordu";
    }
}
?>