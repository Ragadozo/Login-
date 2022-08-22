<?php
function load_users($fajlnev, $adat = array())
{
    $s = @file_get_contents($fajlnev);
    return $s == false
        ? $adat
        : json_decode($s, true);
}
$registered = load_users('users.txt');
function save_User($fajlnev, $adat)
{
    $s = json_encode($adat, JSON_PRETTY_PRINT);
    file_put_contents($fajlnev, $s);
}

$hibak = array();
if ($_POST) {
    $name = trim($_POST['Name']);
    $taj = trim($_POST['Taj']);
    $mail = trim($_POST['Mail']);
    $pass = trim($_POST['Pass']);
    $repass = trim($_POST['rePass']);

    if ($name == '') {
        $hibak[] = 'Név kötelező!';
    } else if (substr_count($name, ' ') < 1) {
        $hibak[] = 'Kérem adja meg teljes nevét!';
    } else if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $hibak[] = 'Hibás név, Csak betűket és szóközt tartalmazhat!';
    }
    $_SESSION["tempName"] = $name;
    if ($taj == '') {
        $hibak[] = 'Tajszám kötelező!';
    }
    $_SESSION["tempTaj"] = $taj;
    if ($mail == '') {
        $hibak[] = 'Kérem adja meg email címét!';
    } else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $hibak[] = 'Hibás email cím!';
    } else {
        foreach ($registered as $user => $u) :
            if ($u["mail"] == $mail) {
                $hibak[] = 'Létező email cím!';
            }
        endforeach;
    }
    $_SESSION["tempMail"] = $mail;
    if ($pass == '') {
        $hibak[] = 'Kérem adja meg jelszavát!';
    } else if (strlen($pass) < 5) {
        $hibak[] = 'Hibás jelszó, legalább 6 karakter legyen, tartalmazzon számot és betűt!';
    }
    if ($repass == '') {
        $hibak[] = 'Kérem ismételje meg jelszavát!';
    } else if ($repass != $pass) {
        $hibak[] = 'A jelszavak nem eggyeznek!';
    }
    if (!$hibak) {
        $registered = load_Users('users.txt');
        $registered[] = array(

            'name'            => $name,
            'taj'            => $taj,
            'mail'            => $mail,
            'pass'            => password_hash($pass, PASSWORD_DEFAULT),

        );
        unset($_SESSION["tempName"]);
        unset($_SESSION["tempTaj"]);
        unset($_SESSION["tempMail"]);
        save_User('users.txt', $registered);
        header('Location: Login.php');
        exit();
    }
}
?>

<html lang="en">

<head>
    <style>
        #reg {
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
    <div id="reg">
        <h1>Regisztráció</h1>
        <br>
        <form action="" method="post">
            <label for="Name"> Kérem adja meg a teljes nevét:</label><br>

            <input type="text" name="Name" id="Name" value="<?php if (isset($_SESSION["tempName"])) : echo $_SESSION["tempName"];
                                                                                            else : echo "";
                                                                                            endif; ?>">
            <br><br>
            <label for="Taj"> Kérem adja meg a TAJ számát:</label><br>
            <input type="number" name="Taj" id="Taj" min="111111111" max="999999999" value="<?php if (isset($_SESSION["tempTaj"])) : echo $_SESSION["tempTaj"];
                                                                                            else : echo "";
                                                                                            endif; ?>">
            <br><br>
            <label for="Mail"> Kérem adja meg e-mail címét:</label><br>
                <input type="text" name="Mail" id="Mail" value="<?php if (isset($_SESSION["tempMail"])) : echo $_SESSION["tempMail"];
                                                                                            else : echo "";
                                                                                            endif; ?>">
            <br><br>
            <label for="Pass"> Kérem adjon meg jelszót fiókjához:</label><br>
            <input type="password" name="Pass" id="Pass">
            <br><br>
            <label for="rePass"> Kérem ismételje meg a jelszót:</label><br>
            <input type="password" name="rePass" id="rePass">
            <br><?php
                if ($hibak) {
                    print_r($hibak);
                }
                ?><br>
            <input type="submit" value="Regisztráció">
        </form>
        <br>
        <br>
        <a href="index.php">Vissza a főoldalra</a>
    </div>
</body>

</html>