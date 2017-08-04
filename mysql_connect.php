<?php
    $dbc=mysqli_connect('127.0.0.1','root','','movierentaldb');

    if (!$dbc) {
    die('Could not connect: '.mysql_error());
    }
?>
