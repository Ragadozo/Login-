<?php
function load_users($fajlnev, $adat = array())
{
    $s = @file_get_contents($fajlnev);
    return $s == false
        ? $adat
        : json_decode($s, true);
}
$registered = load_users('users.txt');
$hibak = array();
session_start();
if ($_POST) {
    $mail = trim($_POST['Mail']);
    $pass = trim($_POST['Pass']);

    if ($mail == '') {
        $hibak[] = 'Kérem adja meg email címét!';
    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $hibak[] = 'Hibás email cím!';
    } else {
        $thereIs = 0;
        foreach ($registered as $user => $u) :
            if ($u["mail"] == $mail) {
                $thereIs = 1;

                if (password_verify($pass, $u["pass"])) {

                    if ($mail == "admin@nemkovid.hu") {
                        $_SESSION["user"] = $u;
                        header('Location: index.php');
                        exit();
                    } else {
                        $_SESSION["user"] = $u;
                        header('Location: index.php');
                        //header('Location: User.php?Name='. $u["name"] . "&Mail=" . $u["mail"] . "&Pass=" . $u["pass"]);
                        exit();
                    }
                } else {
                    $hibak[] = 'Hibás jelszó!';
                }
            }
        endforeach;
        if ($thereIs == 0) {
            $hibak[] = 'Nem létező email cím!';
        }
    }
}
?>

<html lang="en">

<head>
    <style>
        #log {
            text-align: center;
        }
        body {
            background-color: grey;
            font-size:20px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="log">
        <h1>Bejelentkezés</h1>
        <br>
        <form action="" method="post">
            <label for="Mail"> Kérem adja meg e-mail címét:</label><br>
            <input type="text" name="Mail" id="Mail">
            <br><br>
            <label for="Pass"> Kérem adja meg jelszavát:</label><br>
            <input type="password" name="Pass" id="Pass">
            <br><?php
                if ($hibak) {
                    print_r($hibak);
                }
                ?><br>
            <input type="submit" value="Bejelentkezés">
        </form>
        <br>
        <br>
        <a href="Registration.php">Regisztráció</a><br><br>
        <a href="index.php">Vissza a főoldalra</a>
    </div>
</body>

</html>