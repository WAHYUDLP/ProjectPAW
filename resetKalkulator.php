<?php
session_start();
unset($_SESSION['form_data'], $_SESSION['kalori'], $_SESSION['diet']);
header("Location: kalkulator.php");
exit;
