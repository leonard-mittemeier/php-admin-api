<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

echo "Добро пожаловать в админку!";
echo "<br>";
echo $_SESSION['user'];

echo "<br><br><a href='logout.php'>Выход</a>";
?>