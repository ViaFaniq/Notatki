<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $polaczenie = mysqli_connect("localhost", "root", "", "szkola");
    mysqli_set_charset($polaczenie, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Nie można połączyć się z bazą danych: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"));
}

session_start();
if (empty($_SESSION["csrf"])) {
    $_SESSION["csrf"] = bin2hex(random_bytes(32));
}

$uczenDoEdycji = null;
$blad = "";
$komunikat = "";
$self = basename($_SERVER["SCRIPT_NAME"] ?? "zad115.php");

function przekieruj(string $status): void
{
    $self = basename($_SERVER["SCRIPT_NAME"] ?? "zad115.php");
    header("Location: " . $self . "?status=" . urlencode($status));
    exit;
}

/**
 * @return array{0: string, 1: string, 2: int}|string Dane ucznia albo komunikat o błędzie.
 */
function walidujUcznia(string $imie, string $nazwisko, mixed $wiek): array|string
{
    $imie = trim($imie);
    $nazwisko = trim($nazwisko);

    if ($imie === "" || $nazwisko === "") {
        return "Podaj imię i nazwisko.";
    }
    if (mb_strlen($imie, "UTF-8") > 50 || mb_strlen($nazwisko, "UTF-8") > 50) {
        return "Imię i nazwisko mogą mieć maksymalnie 50 znaków.";
    }
    if (filter_var($wiek, FILTER_VALIDATE_INT) === false) {
        return "Podany wiek nie jest liczbą całkowitą.";
    }
    $wiek = (int)$wiek;
    if ($wiek < 14 || $wiek > 100) {
        return "Wiek musi być między 14 a 100.";
    }

    return [$imie, $nazwisko, $wiek];
}

if (isset($_GET["edytuj"])) {
    $idEdycji = filter_var($_GET["edytuj"], FILTER_VALIDATE_INT);
    if ($idEdycji !== false && $idEdycji > 0) {
        $stmt = mysqli_prepare($polaczenie, "SELECT id, imie, nazwisko, wiek FROM uczniowie WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $idEdycji);
        mysqli_stmt_execute($stmt);
        $uczenDoEdycji = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION["csrf"], (string)($_POST["csrf"] ?? ""))) {
        die("Nieprawidłowy token CSRF.");
    }

    $akcja = $_POST["akcja"] ?? "";

    try {
        if ($akcja === "usun") {
            $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                przekieruj("brak");
            }

            $stmt = mysqli_prepare($polaczenie, "DELETE FROM uczniowie WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $status = mysqli_stmt_affected_rows($stmt) > 0 ? "usunieto" : "brak";
            mysqli_stmt_close($stmt);
            przekieruj($status);
        }

        if ($akcja === "zapisz") {
            $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
            if ($id === false || $id <= 0) {
                przekieruj("brak");
            }

            $dane = walidujUcznia(
                $_POST["imieEdytuj"] ?? "",
                $_POST["nazwiskoEdytuj"] ?? "",
                $_POST["wiekEdytuj"] ?? ""
            );

            if (is_string($dane)) {
                $blad = $dane;
                $uczenDoEdycji = [
                    "id" => $id,
                    "imie" => $_POST["imieEdytuj"] ?? "",
                    "nazwisko" => $_POST["nazwiskoEdytuj"] ?? "",
                    "wiek" => $_POST["wiekEdytuj"] ?? "",
                ];
            } else {
                [$imie, $nazwisko, $wiek] = $dane;
                $stmt = mysqli_prepare(
                    $polaczenie,
                    "UPDATE uczniowie SET imie = ?, nazwisko = ?, wiek = ? WHERE id = ?"
                );
                mysqli_stmt_bind_param($stmt, "ssii", $imie, $nazwisko, $wiek, $id);
                mysqli_stmt_execute($stmt);
                $zmienione = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);

                if ($zmienione > 0) {
                    $komunikat = "Zmieniono dane ucznia.";
                } else {
                    $komunikat = "Nie zmieniono danych. Może to oznaczać wpisanie takich samych wartości "
                        . "lub brak rekordu.";
                }

                $uczenDoEdycji = [
                    "id" => $id,
                    "imie" => $imie,
                    "nazwisko" => $nazwisko,
                    "wiek" => $wiek,
                ];
            }
        } elseif ($akcja === "dodaj") {
            $dane = walidujUcznia($_POST["imie"] ?? "", $_POST["nazwisko"] ?? "", $_POST["wiek"] ?? "");
            if (is_string($dane)) {
                $blad = $dane;
            } else {
                [$imie, $nazwisko, $wiek] = $dane;
                $stmt = mysqli_prepare(
                    $polaczenie,
                    "INSERT INTO uczniowie (imie, nazwisko, wiek) VALUES (?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "ssi", $imie, $nazwisko, $wiek);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                przekieruj("dodano");
            }
        }
    } catch (mysqli_sql_exception $e) {
        $blad = "Błąd bazy danych: " . $e->getMessage();
    }
}

$uczniowie = [];
try {
    $wynik = mysqli_query($polaczenie, "SELECT id, imie, nazwisko, wiek FROM uczniowie ORDER BY nazwisko ASC");
    while ($wiersz = mysqli_fetch_assoc($wynik)) {
        $uczniowie[] = $wiersz;
    }
    mysqli_free_result($wynik);
} catch (mysqli_sql_exception $e) {
    $blad = "Błąd bazy danych: " . $e->getMessage();
}

mysqli_close($polaczenie);
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zadanie 115 - aktualizacja ucznia</title>
</head>
<body>
    <?php
    $status = $_GET["status"] ?? "";
    if ($status === "dodano") {
        echo "Dodano ucznia<br>";
    } elseif ($status === "usunieto") {
        echo "Usunięto ucznia<br>";
    } elseif ($status === "brak") {
        echo "Nie znaleziono ucznia<br>";
    }

    if ($komunikat !== "") {
        echo htmlspecialchars($komunikat, ENT_QUOTES, "UTF-8") . "<br>";
    }

    if ($blad !== "") {
        echo htmlspecialchars($blad, ENT_QUOTES, "UTF-8") . "<br>";
    }
    ?>

    <form method="post">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>">
        <input type="hidden" name="akcja" value="dodaj">
        <input type="text" name="imie" placeholder="Imię" maxlength="50" required>
        <input type="text" name="nazwisko" placeholder="Nazwisko" maxlength="50" required>
        <input type="number" name="wiek" placeholder="Wiek" min="14" max="100" required>
        <button type="submit">Dodaj ucznia</button>
    </form>

    <?php foreach ($uczniowie as $wiersz) : ?>
        <div>
            <?= (int)$wiersz["id"] ?>
            <?= htmlspecialchars($wiersz["imie"], ENT_QUOTES, "UTF-8") ?>
            <?= htmlspecialchars($wiersz["nazwisko"], ENT_QUOTES, "UTF-8") ?>
            <?= (int)$wiersz["wiek"] ?>

            <a href="<?= htmlspecialchars($self, ENT_QUOTES, "UTF-8") ?>?edytuj=<?= (int)$wiersz["id"] ?>">Edytuj</a>

            <form method="post">
                <input
                    type="hidden"
                    name="csrf"
                    value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>"
                >
                <input type="hidden" name="akcja" value="usun">
                <input type="hidden" name="id" value="<?= (int)$wiersz["id"] ?>">
                <button type="submit" onclick="return confirm('Czy na pewno usunąć ucznia?')">Usuń</button>
            </form>
        </div>
    <?php endforeach; ?>

    <br>

    <?php if ($uczenDoEdycji !== null) : ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrf"], ENT_QUOTES, "UTF-8") ?>">
            <input type="hidden" name="akcja" value="zapisz">
            <input type="hidden" name="id" value="<?= (int)$uczenDoEdycji["id"] ?>">
            <input
                type="text"
                name="imieEdytuj"
                maxlength="50"
                required
                value="<?= htmlspecialchars((string)$uczenDoEdycji["imie"], ENT_QUOTES, "UTF-8") ?>"
            >
            <input
                type="text"
                name="nazwiskoEdytuj"
                maxlength="50"
                required
                value="<?= htmlspecialchars((string)$uczenDoEdycji["nazwisko"], ENT_QUOTES, "UTF-8") ?>"
            >
            <input
                type="number"
                name="wiekEdytuj"
                min="14"
                max="100"
                required
                value="<?= (int)$uczenDoEdycji["wiek"] ?>"
            >
            <button type="submit">Zapisz zmiany</button>
        </form>
    <?php endif; ?>
</body>
</html>
