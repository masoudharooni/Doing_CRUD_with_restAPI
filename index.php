<?php
require_once "authoload.php";

use App\Services\TokenGenerator;

$token = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = new TokenGenerator;
    $token = $token->generate($_POST['email']);
}


?>


<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" style="width: 50%;margin:150px auto;border:2px solid gray;padding:30px 10px;">
    <input type="text" name="email" placeholder="Email . . . " style="width: 70%;padding:10px 20px">
    <input type="submit" name="btn" value="Genarate Token" style="width: 29%;padding:10px 20px">

    <?php
    if (is_string($token)) {
    ?>
        <textarea name="" id="" cols="30" rows="10" style="width: 100%;margin-top: 20px;"><?= $token ?></textarea>
    <?php } else {
        echo "User is not exist!";
    } ?>
</form>