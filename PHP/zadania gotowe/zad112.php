
<?php
$polaczenie = mysqli_connect("localhost", "root", "", "szkola");
if (!$polaczenie) {
    die("Nie można połączyć się z bazą danych.");
}

$uczenDoEdycji = null;

if (isset($_GET["edytuj"])) {
    $idEdycji = filter_var($_GET["edytuj"], FILTER_VALIDATE_INT);

    if ($idEdycji !== false && $idEdycji > 0) {
        $uczenDoEdycji = $idEdycji;
    }
}

if ($uczenDoEdycji !== null) {
  $sql = "SELECT * FROM uczniowie WHERE id=?";
  $stmt = mysqli_prepare($polaczenie, $sql);
  
  mysqli_stmt_bind_param($stmt, "i", $uczenDoEdycji);
  mysqli_stmt_execute($stmt);
  
  $wynik = mysqli_stmt_get_result($stmt);
  $wierszEdutyj = mysqli_fetch_assoc($wynik);


}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"])) {
        $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);

        if ($id === false || $id <= 0) {
            header("Location: zad110.php?status=brak");
            exit;
        }

        $stmt = mysqli_prepare($polaczenie, "DELETE FROM uczniowie WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $status = mysqli_stmt_affected_rows($stmt) > 0 ? "usunieto" : "brak";
        mysqli_stmt_close($stmt);

        header("Location: zad110.php?status=" . $status);
        exit;
    }

    $imie = trim($_POST["imie"] ?? "");
    $nazwisko = trim($_POST["nazwisko"] ?? "");
    $wiek = $_POST["wiek"] ?? "";

    if ($imie === "" || $nazwisko === "") {
        $blad = "Podaj imię i nazwisko.";
    } elseif (mb_strlen($imie, "UTF-8") > 50 || mb_strlen($nazwisko, "UTF-8") > 50) {
        $blad = "Imię i nazwisko mogą mieć maksymalnie 50 znaków.";
    } elseif (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
        $blad = "Podany wiek nie jest liczbą całkowitą.";
    } elseif ((int)$wiek < 14 || (int)$wiek > 100) {
        $blad = "Wiek musi być między 14 a 100.";
    } else {
        $wiek = (int)$wiek;
        $stmt = mysqli_prepare(
            $polaczenie,
            "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: zad110.php?status=dodano");
        exit;
    }
}

$wynik = mysqli_query(
    $polaczenie,
    "SELECT id, imie, nazwisko, wiek FROM uczniowie ORDER BY nazwisko ASC"
);
?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Uczniowie</title>
</head>
<body>
    <?php
    $status = $_GET["status"] ?? "";
    if ($status === "dodano") echo "Dodano ucznia<br>";
    elseif ($status === "usunieto") echo "Usunięto ucznia<br>";
    elseif ($status === "brak") echo "Nie znaleziono ucznia do usunięcia<br>";

    if (!empty($blad)) echo htmlspecialchars($blad, ENT_QUOTES, "UTF-8");
    ?>

    <form method="post">
        <input type="text" name="imie" placeholder="Imię" required>
        <input type="text" name="nazwisko" placeholder="Nazwisko" required>
        <input type="number" name="wiek" placeholder="Wiek" required>
        <button type="submit">Dodaj ucznia</button>
    </form>

    <?php while ($wiersz = mysqli_fetch_assoc($wynik)): ?>
        <div>
            <?= (int)$wiersz["id"] ?>
            <?= htmlspecialchars($wiersz["imie"], ENT_QUOTES, "UTF-8") ?>
            <?= htmlspecialchars($wiersz["nazwisko"], ENT_QUOTES, "UTF-8") ?>
            <?= (int)$wiersz["wiek"] ?>
            <a href="zad111.php?edytuj=<?= (int)$wiersz["id"];?>">Edytuj</a>

            <form method="post">
                <input type="hidden" name="id" value="<?= (int)$wiersz["id"] ?>">
                <button type="submit"onclick="return confirm('Czy na pewno usunąć ucznia?')">Usuń</button>
            </form>
        </div>
    <?php endwhile; ?>

    <br>

    <?php
      if ($wierszEdutyj !== false && $wierszEdutyj !== 0) {
        echo (int)$wierszEdutyj["id"];
        echo " ";
        echo htmlspecialchars($wierszEdutyj["imie"], ENT_QUOTES, "UTF-8");
        echo " ";
        echo htmlspecialchars($wierszEdutyj["nazwisko"], ENT_QUOTES, "UTF-8");
        echo " ";
        echo (int)$wierszEdutyj["id"];
      } else {
        echo "Nie znaleziono ucznia";
      }
      
      mysqli_stmt_close($stmt);
    ?>
</body>
</html>
