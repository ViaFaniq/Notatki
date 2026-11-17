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
        <button>Pokaż</button>
    </form>
</body>
</html>

<?php 
$imie = trim($_POST["imie"]);
echo htmlspecialchars($imie, ENT_QUOTES, "UTF-8");
?>