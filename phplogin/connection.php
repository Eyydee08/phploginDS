<?php
$host = "localhost";
$database = "logindb_ds";
$dbUsername = "root";
$dbPassword = "";

$connectionString = "mysql:host=$host;dbname=$database;charset=utf8mb4";
$pdo = new PDO($connectionString, $dbUsername, $dbPassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);