<div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-3">

  <?php

  foreach ($posts as $post) {

    foreach ($categories as $cat) {
      if ($cat["id"] === $post["category_id"]) {
        $post["cat"] = $cat["title"];
      }
    }

  ?>

    <div class="relative bg-zinc-950 rounded-lg flex flex-col overflow-hidden text-left">
      <a href="<?= main_url ?>view-post.php?id=<?= $post["id"] ?>" class="inline-block">
        <img src="<?= assets("images/posts/") . $post["image"] ?>" alt="<?= $post["title"] ?>" class="sm:h-[200px] w-full object-cover">
      </a>
      <div class="flex flex-col gap-3 p-3 flex-1">
        <p class="text-sm">By <a href="<?= main_url ?>author.php?id=<?= $post["author_id"] ?>" class="text-gray-400"><?= $post["author"] ?></a></p>
        <a href="<?= main_url ?>view-post.php?id=<?= $post["id"] ?>" class="inline-block">
          <h2><?= $post["title"] ?></h2>
        </a>
        <a href="<?= main_url ?>posts.php?category=<?= $post["category_id"] ?>" class="text-gray-400 text-xs inline-block mt-auto"><?= $post["cat"] ?></a>
      </div>
    </div>

  <?php

  }

  ?>

</div>