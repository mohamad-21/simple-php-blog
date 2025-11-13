<?php

require "../config.php";


$name = $email = $password = $bio = null;
$profile = "default-profile.jpg";

if (!isset($_POST["submit"])) die("not set");
if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["bio"])) die("not set");

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$bio = $_POST["bio"];
if (isset($_FILES["profile"])) {
  $upload = $_FILES["profile"];
  $upload_name = time() . $upload["name"];

  if (getimagesize($upload["tmp_name"])) {
    if (move_uploaded_file($upload["tmp_name"], base_path("assets/images/profiles/") . $upload_name)) {
      $profile = $upload_name;
    }
  }
}

$createaccount_query = $conn->prepare("insert into users (name, email, password, bio, profile) values (:name, :email, :password, :bio, :profile)");

$insert = $createaccount_query->execute(["name" => $name, "email" => $email, "password" => $password, "bio" => $bio, "profile" => $profile]);

if ($insert) {
  header("Location: create-account.php?created=success");
} else {
  header("Location: create-account.php?created=failed");
}
