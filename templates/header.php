<?php
include_once("helpers/url.php");
include("process/conection.php");
$msg = "";
if(isset($_SESSION["msg"])){
  $msg = $_SESSION["msg"];
  $status = $_SESSION["status"];

  $_SESSION["msg"] = "";
  $_SESSION["status"] = "";
}
?>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $BASE_URL ?>/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  </head>
<body>
    <header>
<div class="main-header">
    <img src="<?= $BASE_URL ?>/images/logo.png">
    <ul>
        <a href="<?= $BASE_URL ?>"><li>FAÇA SEU PEDIDO</li></a>
        <a href="https://scontent.fjdf2-2.fna.fbcdn.net/v/t39.30808-6/334059062_741095477380045_3048258528573380950_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=e3f864&_nc_eui2=AeGCM_FY48HTHv17TDViM2lAZEGdumr--pNkQZ26av76k9oMd2iN2yfFZd4_RTi5mRtqQi4A5cIA_REcjYZjc00s&_nc_ohc=YPujiXmKpc0AX8mieJ5&_nc_ht=scontent.fjdf2-2.fna&oh=00_AfC_5Eh5Kac80seW4I3vTMNa9hlBA62mlez_6Q6KyuE0JA&oe=645AB46B"><li>CONTATO</li></a>
        <a href="<?= $BASE_URL ?>/dashboard.php"><li>STATUS</li></a>
    </ul>
</div>
</header>
<?php if($msg != ""): ?>
<div class="alert alert-<?= $status ?>">
    <p><?= $msg ?></p>
</div>
<?php endif;?>