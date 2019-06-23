<?php
    $passFromUser="admin";

    $hashedPass=password_hash($passFromUser, PASSWORD_DEFAULT);

    echo $hashedPass;
?>