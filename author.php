<?php

require "./config.php";
require base_path("components/header.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
  showError("Author not founded.");
}

$id = (int) $_GET["id"];

$author_query = $conn->query("select * from users where id = $id");

if ($author_query->rowCount() === 0) {
  showError("Author not founded.");
}

$author = $author_query->fetch();

$author_posts_query = $conn->query("select posts.id, title, body, image, category_id, author_id, posts.created_at, users.name as author from posts join users on users.id = posts.author_id where author_id = {$author["id"]}");
$categories_query = $conn->query("select * from categories");
$categories = $categories_query->fetchAll();

?>
<div class="pt-12 flex flex-col">
  <div class="flex items-center flex-col justify-center gap-10">
    <button id="profile-image-btn" onclick="
      document.getElementById('profile-show-modal').classList.add('active')">
      <img src="<?= assets("images/profiles/" . $author["profile"]) ?>" alt="<?= $author["name"] ?>" class="w-[250px] h-[250px] object-cover rounded-full border-3 border-zinc-900" id="profile-image">
    </button>
    <div class="flex flex-col items-center gap-3">
      <h1 class="md:text-5xl sm:text-4xl text-3xl font-bold"><?= $author["name"] ?></h1>
      <p class="text-lg text-gray-400"><?= $author["bio"] ?? "No bio available..." ?></p>
    </div>
  </div>
  <div class="mt-24">
    <?php
    if ($author_posts_query->rowCount() > 0) {
      $posts = $author_posts_query->fetchAll();
    ?>
      <h1 class="text-4xl sm:text-3xl mb-6">Posts</h1>
    <?php
      require base_path("components/posts-list.php");
    } else {
    ?>
      <h1 class="text-4xl sm:text-3xl">This user has not posted anything yet.</h1>
    <?php
    }
    ?>
  </div>
</div>
<div class="fixed inset-0 bg-zinc-950/70 backdrop-blur-lg hidden [&.active]:flex items-center justify-center p-10 z-20 oveflow-hidden" id="profile-show-modal" onclick="this.classList.remove('active')">
  <img src="<?= assets("images/profiles/") . $author["profile"] ?>" alt="<?= $author["name"] ?>" class="w-full max-w-full">
</div>

<?php require base_path("components/footer.php"); ?>