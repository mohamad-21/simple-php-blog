<?php

require "../config.php";


$email = $password = null;

if (!isset($_POST["submit"])) die("not set");
if (!isset($_POST["email"]) || !isset($_POST["password"])) die("not set");

$email = $_POST["email"];
$password = $_POST["password"];

$check_user = $conn->prepare("select * from users where email = :email and password = :password");

$check_user->execute(["email" => $email, "password" => $password]);

if ($check_user->rowCount() === 0) {
  header("Location: login.php?status=failed");
  die;
}

$user = $check_user->fetch();

$_SESSION["user"] = [
  "id" => $user["id"],
  "name" => $user["name"],
  "email" => $user["email"],
  "profile" => $user["profile"],
  "bio" => $user["bio"]
];

header("Location: " . main_url . "profile.php");
