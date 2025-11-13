<?php

session_start();

$uri = $_SERVER["REQUEST_URI"];
$parsed_uri = parse_url($uri);

define("main_url", "http://localhost/myblog/");
define("base_url", $parsed_uri["path"]);

$server_name = "localhost";
$server_username = "root";
$server_pass = "";
$server_db = "myblog";

$dsn = "mysql:host=$server_name;dbname=$server_db";

try {
  $conn = new PDO($dsn, $server_username, $server_pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);
} catch (PDOException $err) {
  die("Connection error:" . $err->getMessage());
}

function base_path(string $path)
{
  return __DIR__ . "/" . $path;
}

function assets(string $path)
{
  return main_url . "assets/" . $path;
}

function showError($error = "")
{
  require base_path("components/error.php");
  die;
}

function ishomepage()
{
  return base_url === "/myblog/" || base_url === "/myblog/index.php";
}
