<?php

require "../config.php";

if (isset($_GET["created"]) && $_GET["created"] === "success") {
  header("Location: " . main_url . "auth/login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="dark light" />
  <title>Create account</title>
  <link rel="stylesheet" href="<?= assets("styles/tailwind.css") ?>">
  <link rel="stylesheet" href="<?= assets("styles/style.css") ?>">
  <link rel="shortcut icon" href="<?= assets("images/logo.png") ?>" type="image/x-icon" />
  <script src="<?= assets("scripts/main.js?v=" . time()) ?>" defer></script>
</head>

<body class="bg-background text-gray-200 relative w-full flex flex-col items-center justify-center min-h-screen py-12 px-6">
  <div class="absolute inset-0 [background-size:40px_40px] [background-image:linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] dark:[background-image:linear-gradient(to_right,#262626_1px,transparent_1px),linear-gradient(to_bottom,#262626_1px,transparent_1px)]"></div>
  <div class="pointer-events-none absolute inset-0 flex items-center justify-center [mask-image:radial-gradient(ellipse_at_center,transparent_20%,black)] bg-background"></div>

  <form action="./create-account-process.php" method="post" enctype="multipart/form-data" class="w-full max-w-xl py-10 px-8 bg-zinc-950 rounded-sm relative">
    <img src="<?= assets("images/logo.svg") ?>" width="50" alt="logo" class="mb-7">
    <h1 class="text-4xl font-bold mb-10">Create your account</h1>
    <div class="flex flex-col gap-5">
      <div class="flex items-center gap-3 w-full">
        <div class="flex flex-col gap-3 flex-1">
          <label>Name</label>
          <input type="text" name="name" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="Name" requierd>
        </div>
        <div class="flex flex-col gap-3 flex-1">
          <label>Email</label>
          <input type="email" name="email" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="Email" requierd>
        </div>
      </div>
      <div class="flex flex-col gap-3">
        <label>Password</label>
        <input type="password" name="password" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="Password" requierd>
      </div>

      <div class="flex flex-col gap-3">
        <label>Bio</label>
        <textarea name="bio" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="Bio"></textarea>
      </div>

      <div class="flex flex-col gap-3">
        <label>Upload your profile</label>
        <input type="file" name="profile" class="border border-gray-400 w-full py-2 px-4 rounded-sm" accept="image/*">
      </div>
      <button class="bg-gray-800 py-3 px-4 text-xl" name="submit">Create Account</button>
    </div>

  </form>

</body>

</html>