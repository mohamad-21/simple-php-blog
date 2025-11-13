<?php

require "./config.php";
require base_path("components/header.php");

if (!isset($_GET["id"]) || empty($_GET["id"])) {
  showError("Post not founded.");
}

$id = (int) $_GET["id"];

$post_prepare = $conn->prepare("select * from posts where id = :id");
$post_prepare->execute(["id" => $id]);

if ($post_prepare->rowCount() === 0) {
  showError("Post not founded.");
}

$post = $post_prepare->fetch();
$post_author_query = $conn->query("select * from users where id = {$post["author_id"]}");
$post_author = $post_author_query->fetch();

$related_posts_prepare = $conn->prepare("select posts.id, title, body, image, category_id, author_id, posts.created_at, users.name as author from posts join users on users.id = posts.author_id where category_id = {$post["category_id"]} and not posts.id = :id order by created_at desc limit 5");
$related_posts_prepare->execute(["id" => $id]);
$posts = $related_posts_prepare->fetchAll();
$categories = $conn->query("select * from categories")->fetchAll();


?>

<div class="flex flex-col gap-6 max-w-4xl">
  <div>
    <img src="<?= assets("images/posts/{$post['image']}") ?>" class="mb-12" alt="<?= $post["title"] ?>">
  </div>

  <a href="<?= main_url . "author.php?id=" . $post["author_id"] ?>" class="flex items-center gap-3 mb-5 max-w-max">
    <div>
      <img src="<?= assets("images/profiles/" . $post_author["profile"]) ?>" alt="<?= $post_author["name"] ?>" class="rounded-full aspect-square object-cover" width="60">
    </div>
    <div>
      <h2 class="text-xl text-left"><?= $post_author["name"] ?></h2>
      <p class="text-zinc-400 text-sm">Posted on <?= date("M j, Y", strtotime($post["created_at"])) ?></p>
    </div>
  </a>

  <h1 class="md:text-4xl sm:text-3xl text-2xl max-w-2xl leading-12 font-extrabold"><?= $post["title"] ?></h1>
  <p class="md:text-lg leading-loose whitespace-pre-wrap wrap-anywhere"><?= $post["body"] ?></p>
</div>

<div class="mt-20">
  <h2 class="text-2xl mb-6">Related Posts</h2>

  <?php require base_path("components/posts-list.php") ?>

</div>

<?php require base_path("components/footer.php");
