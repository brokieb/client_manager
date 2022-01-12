<?php
session_start();
unset($_SESSION['alerts'][$_POST['id']]);
?>