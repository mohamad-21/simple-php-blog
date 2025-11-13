<?php

require "./config.php";

if (!isset($_SESSION["user"])) {
  header("Location: " . main_url . "auth/login.php");
  die;
}

$categories = $conn->query("select * from categories")->fetchAll();

if (isset($_POST["submit"])) {
  extract($_POST);
  $cover = null;

  if (empty($title) || empty($category) || empty($body) || !isset($_FILES["cover"])) die("fields not set all");

  $upload = $_FILES["cover"];
  $upload_name = time() . $upload["name"];

  if (getimagesize($upload["tmp_name"])) {
    if (move_uploaded_file($upload["tmp_name"], base_path("assets/images/posts/") . $upload_name)) {
      $cover = $upload_name;
    }
  }

  $insert_post = $conn->prepare("insert into posts (title,body,image,category_id, author_id) values (:title, :body, :image, :category_id, :author_id)");

  $inserted = $insert_post->execute([
    "title" => $title,
    "body" => $body,
    "image" => $cover,
    "category_id" => $category,
    "author_id" => $_SESSION["user"]["id"],
  ]);

  if ($inserted) {
    $post = $conn->query("select id from posts where author_id = {$_SESSION['user']['id']} order by created_at desc limit 1")->fetch();

    header("Location: " . main_url . "view-post.php?id=" . $post["id"]);
    die;
  }
}

require base_path("components/header.php");

?>

<form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="flex flex-col max-w-2xl" enctype="multipart/form-data" id="post-form">
  <h1 class="md:text-4xl text-3xl mb-14 font-bold">Create Post</h1>
  <div class="mb-4 flex flex-col gap-10">
    <div>
      <button type="button" class="py-2 px-4 bg-gray-700 rounded-lg" onclick="document.getElementById('upload_input').click()">Add cover</button>
      <input name="cover" type="file" id="upload_input" hidden required />
      <img id="image_view" class="image_view hidden mt-3 max-h-[400px] object-cover" src="#" alt="view" />
    </div>
    <textarea type="text" name="title" class="text-2xl outline-none resize-none" placeholder="New post title here..." rows="3" required></textarea>
  </div>
  <div id="editor" class="bg-background [&>.ql-editor]:text-xl [&>.ql-editor]:before:!text-zinc-500 [&>.ql-editor]:before:text-xl [&>.ql-editor]:before:!not-italic min-h-[200px]">
  </div>
  <div>
    <h3 class="text-lg mt-6 mb-2">Category</h3>
    <select name="category" class="py-2 px-4 bg-zinc-950 rounded-lg" required>
      <?php
      foreach ($categories as $cat) {
      ?>
        <option value="<?= $cat["id"] ?>"><?= $cat["title"] ?></option>
      <?php
      }
      ?>
    </select>
  </div>
  <textarea type="text" name="body" id="body" hidden></textarea>
  <button name="submit" class="py-2 px-4 bg-gray-700 text-lg rounded-lg mt-7">Post</button>
</form>

<?php
require base_path("components/footer.php");
?>