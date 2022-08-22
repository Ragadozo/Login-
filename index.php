<?php
$adminMode = 0;
function load_dates($fajlnev, $adat = array())
{
  $s = @file_get_contents($fajlnev);
  return $s == false
    ? $adat
    : json_decode($s, true);
}
$appointments = load_dates('dates.txt');

session_start();
if (isset($_SESSION["user"])) : ?>
  <strong>
    <a href="LogOut.php">
      Log out (<?= $_SESSION["user"]["name"] ?>)
    </a>
  </strong>
  <?php 
  if($_SESSION["user"]["mail"] == "admin@nemkovid.hu"){
    $adminMode = 1;
  }
  ?>
<?php else : ?>
  <strong><a href="Login.php">Log in</a></strong>
<?php endif; ?>

<html lang="en">

<head>
  <style>
    #text {
      text-align: center;
    }
    body {
            font-size:20px;
            background-color: grey;
        }
  </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div id="text">
    <h1>Nemzeti Koronavírus Depó</h1>
    <br>
    <br>
    <?php
    if(!$adminMode){
      echo "<p>A Nemzeti Koronavírus Depó (NemKoViD - Mondj nemet a koronavírusra!) központi épületében különböző időpontokban oltásokat szervez.</p>";
    }
    
    ?>
    
    <br>

    <label for="dates">Elérhető dátumok:</label>
    <select name="dates" id="dates">
      <?php
      foreach ($appointments as $date => $d) :
        $fill = $d["time"];
        echo "<option value='$fill'>$fill</option>";
      endforeach;
      ?>
    </select>
      <br><br>
      <?php if ($adminMode) : ?>
    <button onClick="window.location='newDate.php';">Új időpont hozzáadása </button>
    <?php endif; ?>
  </div>
</body>

</html>