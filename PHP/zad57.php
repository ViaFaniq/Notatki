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

if (isset($_GET["usun"])) {
    $id = $_GET["usun"];
    $sql = "DELETE FROM uczniowie WHERE id=$id";
    mysqli_query($polaczenie, $sql);
    echo ("Usunięto Ucznia o " . $id . " id");
}

?>