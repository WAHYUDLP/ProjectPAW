<?php
session_start();
unset($_SESSION['gender'], $_SESSION['age'], $_SESSION['weight'], $_SESSION['height'], $_SESSION['body_fat'], $_SESSION['activity'], $_SESSION['kalori'], $_SESSION['diet']);
header("Location: kalkulator.php");
exit;
