<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <input type="number" name="punkty">
        <button>wyslij</button>
    </form>
</body>
</html>

<?php 

$punkty = $_POST["punkty"];
if ($punkty >= 50) {
    echo ("Zaliczone");
} else {
    echo ("Nie Zaliczone");
}

?>