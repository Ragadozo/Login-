<?php

function load_dates($fajlnev, $adat = array())
{
    $s = @file_get_contents($fajlnev);
    return $s == false
        ? $adat
        : json_decode($s, true);
}
$appointments = load_dates('dates.txt');

function save_Date($fajlnev, $adat)
{
    $s = json_encode($adat, JSON_PRETTY_PRINT);
    file_put_contents($fajlnev, $s);
}
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}


$hibak = array();
if ($_POST) {
    $date = trim($_POST['newDate']);
    $hour = trim($_POST['hour']);

    if ($date == '') {
        $hibak[] = 'Dátum kotelezo!';
    } else if (!validateDate($date)) {
        $hibak[] = 'Dátum formátum hibás';
    }
    if ($hour == '') {
        $hibak[] = 'Időpont kötelező!';
    } else if (!preg_match("/^(?:2[0-3]|[01][0-9]):[0-6][0-9]$/", $hour)) {
        $hibak[] = 'Időpont formátum hibás';
    }
    $currentDate = 20 . date("y-m-d");
    if ($currentDate >= $date) {
        $hibak[] = 'Múltbéli dátum, nem adható meg!';
    }
    if (!$hibak) {
        $appointments = load_dates('dates.txt');
        $size = sizeof($appointments);
        $appointments[] = array(

            'id'            => 'appid' . $size,
            'time'            => $date . " " . $hour,

        );
        save_Date('dates.txt', $appointments);
        header('Location: Succes.php');
        exit();
    }
}
session_start();
if (isset($_SESSION["user"])) : ?>
    <?php
    if ($_SESSION["user"]["mail"] != "admin@nemkovid.hu") {
        header('Location: index.php');
        exit();
    }
    ?>
<?php else :
    header('Location: index.php');
    exit();
endif; ?>

<html lang="en">

<head>
<style>
    body {
            text-align: center;
            font-size:20px;
            background-color: grey;
        }
  </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Adjon hozzá új időpontot:</h1>
    <br><br>
    <form action="" method="post">
        Dátum
        <input type="text" id="newDate" , name="newDate" value="2021-01-01">
        <br><br>
        Időpont:
        <input type="text" id="hour" , name="hour" value="12:00">
        <br><br>
        <input type="submit" value="Hozzáad">
    </form>
    <?php if ($hibak) {
        print_r($hibak);
      }
      ?>
      <br>
      <a href="index.php">Vissza a főoldalra oldalra</a>
</body>

</html>