<?php

require "./config.php";

if (!isset($_SESSION["user"])) {
  showError("You're not logged in to see this page.");
}

$id = $_SESSION["user"]["id"];

$user_query = $conn->query("select * from users where id = $id");

if ($user_query->rowCount() === 0) {
  showError("You're not logged in to see this page.");
}

$user = $user_query->fetch();

$categories_query = $conn->query("select * from categories");
$categories = $categories_query->fetchAll();

$posts_query = $conn->query("select * from posts where author_id = $id");

if (isset($_POST["submit"])) {
  if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["bio"])) die("fields has not set");
  $name = $_POST["name"];
  $email = $_POST["email"];
  $bio = $_POST["bio"];
  $profile = $user["profile"];

  if (isset($_FILES["profile"]) && !$_FILES["profile"]["error"]) {
    $upload = $_FILES["profile"];
    $upload_name = time() . $upload["name"];

    if (getimagesize($upload["tmp_name"])) {
      if (move_uploaded_file($upload["tmp_name"], base_path("assets/images/profiles/") . $upload_name)) {
        $profile = $upload_name;
      }
    }
  }

  $update_query = $conn->prepare("update users set name = :name, email = :email, bio = :bio, profile = :profile where id = :id");

  $result = $update_query->execute([
    "id" => $id,
    "name" => $name,
    "email" => $email,
    "bio" => $bio,
    "profile" => $profile,
  ]);

  if ($result) {
    $_SESSION["user"] = [
      "id" => $id,
      "name" => $name,
      "email" => $email,
      "bio" => $bio,
      "profile" => $profile,
    ];
    header("Refresh:0");
  } else {
    die("error: $result");
  }
}

require base_path("components/header.php");

?>
<div class="pt-12 flex flex-col items-center gap-14">

  <div class="flex items-center flex-col justify-center gap-6">
    <button id="profile-image-btn" onclick="
        document.getElementById('profile-show-modal').classList.add('active')">
      <img src="<?= assets("images/profiles/" . $user["profile"]) ?>" alt="<?= $user["name"] ?>" class="w-[300px] h-[300px] object-cover border-3 border-zinc-900" id="profile-image">
    </button>
    <div class="flex flex-col items-center gap-3">
      <h1 class="md:text-5xl text-4xl font-bold"><?= $user["name"] ?></h1>
      <p class="text-lg text-gray-400 white-space-pre-wrap"><?= $user["bio"] ?? "No bio available..." ?></p>
    </div>
    <a href="?update_profile#update-account-form" class="py-2 px-4 bg-gray-700 rounded-lg block">Update profile</a>
  </div>
  <?php

  if (isset($_GET["update_profile"])) {
  ?>
    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" enctype="multipart/form-data" class="w-full max-w-xl py-10 pt-20 px-8 bg-zinc-950 rounded-sm relative" id="update-account-form">
      <h1 class="sm:text-4xl text-3xl mb-6">Update your profile</h1>
      <div class="flex flex-col gap-5">
        <div class="flex flex-col gap-3">
          <label>Name</label>
          <input type="text" name="name" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="Name" value="<?= $user["name"] ?>" requierd>
        </div>
        <div class="flex flex-col gap-3">
          <label>Email</label>
          <input type="email" name="email" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="email" value="<?= $user["email"] ?>" requierd>
        </div>
        <div class="flex flex-col gap-3">
          <label>Bio</label>
          <textarea type="text" name="bio" class="border border-gray-400 w-full py-2 px-4 rounded-sm" placeholder="bio"><?= $user["bio"] ?></textarea>
        </div>
        <div class="flex flex-col gap-3">
          <label>Profile image</label>
          <div class="flex gap-3 text-center">
            <img src="<?= assets("images/profiles/") . $user["profile"] ?>" width="80" class="object-cover rounded-full aspect-square" alt="<?= $user["name"] ?>">
            <input type="file" name="profile" class="border border-gray-400 w-full py-2 px-4 rounded-sm" accept="image/*">
          </div>
        </div>
        <button class="bg-gray-800 py-3 px-4 text-xl" name="submit">Update</button>
      </div>

    </form>
  <?php
  }

  ?>

</div>

<div class="fixed inset-0 bg-zinc-950/70 backdrop-blur-lg hidden [&.active]:flex items-center justify-center p-10 z-20 oveflow-hidden" id="profile-show-modal" onclick="this.classList.remove('active')">
  <img src="<?= assets("images/profiles/") . $user["profile"] ?>" alt="<?= $user["name"] ?>" class="w-full max-w-full">
</div>
<div class="mt-24">
  <?php
  if ($posts_query->rowCount() > 0) {

    $posts = $posts_query->fetchAll();

    foreach ($posts as $idx => $post) {
      $posts[$idx]["author"] = $user["name"];
    }

  ?>
    <h1 class="text-4xl sm:text-3xl mb-6">Posts</h1>
  <?php
    require base_path("components/posts-list.php");
  } else {
  ?>
    <h1 class="text-4xl sm:text-3xl">You've not posted anything yet.</h1>
  <?php
  }
  ?>
</div>

<?php require base_path("components/footer.php"); ?>