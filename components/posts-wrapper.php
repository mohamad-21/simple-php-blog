<?php

$condition = "";
$sort = $_GET["sort"] ?? "order by created_at desc";
$posts_title = "Recent Posts";
$categories_query = $conn->query("select * from categories");
$categories = $categories_query->fetchAll();
$params = [];

if (count($_GET) > 0) {

  if (isset($_GET["filter"]) && !empty($_GET["filter"])) {
    $condition .= "WHERE posts.category_id = :category_id";
    array_push($params, ["category_id" => $_GET['filter']]);
    $filter_id = (int) $_GET["filter"] - 1;
    $posts_title = $categories[$filter_id]["title"] . " Posts";
  }

  if (isset($_GET["sort"]) && !empty($_GET["sort"])) {
    if ($_GET["sort"] === "recent") {
      $sort = "order by posts.created_at desc";
    }
    if ($_GET["sort"] === "oldest") {
      $sort = "order by posts.created_at asc";
    }
    if ($_GET["sort"] === "category") {
      $sort = "order by posts.category_id desc";
    }
    if ($_GET["sort"] === "author") {
      $sort = "order by posts.author_id desc";
    }
    if ((!isset($_GET["filter"]) || (isset($_GET["filter"]) && $_GET["filter"] === "0")) && isset($_GET["sort"])) {
      $posts_title = "Posts sorted by {$_GET['sort']}";
    }
  }
}

if (isset($_GET["search"])) {

  $searchTerm = $_GET["search"];

  $searchQuery = "title like :searchTerm";

  $posts_title = "Search results for \"$searchTerm\"";

  if ($condition) {
    $condition .= "and $searchQuery";
  } else {
    $condition .= "where $searchQuery";
  }
  array_push($params, ["searchTerm" => "%$searchTerm%"]);
}

if (isset($_GET["category"])) {
  $filter_category_id = (int) $_GET["category"];
  $category_filter_query = "posts.category_id = $filter_category_id";

  if ($condition) {
    $condition .= "and $category_filter_query";
  } else {
    $condition .= "where $category_filter_query";
  }

  $posts_title = $categories[$filter_category_id - 1]["title"] . " Posts";
}


$posts_prepare = $conn->prepare("select posts.id, title, body, image, category_id, author_id, posts.created_at, users.name as author from posts join users on users.id = posts.author_id $condition $sort");
$posts_prepare->execute(...$params);

?>


<?php
if ($posts_prepare->rowCount() > 0) {
  $posts = $posts_prepare->fetchAll();

?>
  <div>

    <div class="flex lg:items-center justify-between lg:flex-row flex-col gap-5 mb-6 bg-gradient-to-r from-gray-700/30 via-gray-700/20 to-transparent py-6 px-4">
      <h1 class="lg:text-4xl sm:text-3xl text-2xl"><?= $posts_title ?></h1>
      <form action="#main" id="posts-filter-form" class="flex lg:items-center gap-3 sm:flex-row flex-col">
        <div>
          <select name="filter" class="p-2 lg:text-base text-sm bg-black border-2 border-gray-800 rounded-full">
            <option value="0" <?= (!isset($_GET["filter"])) ? "selected" : "" ?>>Filter by</option>
            <option value="1" <?= (isset($_GET["filter"]) && $_GET["filter"] === "1") ? "selected" : "" ?>>Web development</option>
            <option value="2" <?= (isset($_GET["filter"]) && $_GET["filter"] === "2") ? "selected" : "" ?>>Cyber security</option>
            <option value="3" <?= (isset($_GET["filter"]) && $_GET["filter"] === "3") ? "selected" : "" ?>>AI</option>
            <option value="4" <?= (isset($_GET["filter"]) && $_GET["filter"] === "4") ?>>Gaming</option>
          </select>
          <select name="sort" class="p-2 lg:text-base text-sm bg-black border-2 border-gray-800 rounded-full">
            <option value="recent" <?= (isset($_GET["sort"]) && $_GET["sort"] === "recent") ? "selected" : "" ?>>Sort by</option>
            <option value="recent" <?= (isset($_GET["sort"]) && $_GET["sort"] === "recent") ? "selected" : "" ?>>Recent</option>
            <option value="oldest" <?= (isset($_GET["sort"]) && $_GET["sort"] === "oldest") ? "selected" : "" ?>>Oldest</option>
            <option value="category" <?= (isset($_GET["sort"]) && $_GET["sort"] === "category") ? "selected" : "" ?>>Category</option>
            <option value="author" <?= (isset($_GET["sort"]) && $_GET["sort"] === "author") ? "selected" : "" ?>>Author</option>
          </select>
        </div>
        <button class="py-2 px-5 lg:text-base text-sm bg-gray-800 rounded-full max-w-max">Update</button>
      </form>
    </div>

    <div class="md:block flex gap-6 relative">
      <?php require base_path("components/posts-list.php"); ?>
    </div>

  </div>
<?php
} else {
  if (isset($_GET["search"])) {
    showError("No results founded for \"$searchTerm\"");
  } else {
    showError("No posts founded.");
  }
}
?>