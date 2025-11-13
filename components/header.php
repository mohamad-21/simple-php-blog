<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="dark light" />
  <title>DEV BLOG</title>
  <link rel="stylesheet" href="<?= assets("styles/tailwind.css") ?>">
  <link rel="stylesheet" href="<?= assets("styles/style.css") ?>">
  <link rel="shortcut icon" href="<?= assets("images/logo.png") ?>" type="image/x-icon" />
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
  <script src="<?= assets("scripts/main.js?v=" . time()) ?>" defer></script>
</head>

<body class="bg-background text-gray-200 relative w-full items-center justify-center">
  <div class="absolute inset-0 [background-size:40px_40px] [background-image:linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] dark:[background-image:linear-gradient(to_right,#262626_1px,transparent_1px),linear-gradient(to_bottom,#262626_1px,transparent_1px)]"></div>
  <div class="pointer-events-none absolute inset-0 flex items-center justify-center [mask-image:radial-gradient(ellipse_at_center,transparent_20%,black)] bg-background"></div>
  <header>
    <nav class="bg-zinc-950/30 backdrop-blur-lg fixed top-0 left-0 right-0 z-10 px-8 py-3 w-full flex items-center gap-4 justify-between md:text-lg">
      <a href="<?= main_url ?>">
        <img src="<?= assets("images/logo.svg") ?>" width="40" alt="dev">
      </a>
      <ul class="flex items-center gap-6 [&>li>a]:hover:opacity-70">
        <?php
        if (isset($_SESSION["user"])) {
        ?>
          <li>
            <button class="relative group flex" onclick="this.classList.toggle('active')">
              <img src="<?= assets("images/profiles/" . $_SESSION["user"]["profile"]) ?>" width="30" class="rounded-full aspect-square object-cover" alt="<?= $_SESSION["user"]["name"] ?>">
              <ul class="absolute bg-gray-800 top-10 left-1/2 -translate-x-1/2 w-[150px] hidden group-[.active]:flex flex-col text-left [&>li]:odd:border-b [&>li]:odd:border-gray-600">
                <li>
                  <a href="<?= main_url . "profile.php" ?>" class="py-2 px-3 flex items-center gap-1">Profile</a>
                </li>
                <li>
                  <a href="<?= main_url . "create.php" ?>" class="py-2 px-3 flex items-center gap-1">Create post</a>
                </li>
                <li>
                  <a href="<?= main_url . "auth/logout.php" ?>" class="py-2 px-3 flex items-center gap-1 bg-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                      <path d="M9 12h12l-3 -3" />
                      <path d="M18 15l3 -3" />
                    </svg>
                    Logout
                  </a>
                </li>
              </ul>
            </button>
          </li>
        <?php
        } else {
        ?>
          <li><a href="<?= main_url . "auth/login.php" ?>">Login</a></li>
          <li><a href="<?= main_url . "auth/create-account.php" ?>">Create account</a></li>
        <?php
        }
        ?>
        <li><a href="<?= main_url ?>posts.php">Posts</a></li>

        <li>
          <button id="search-trigger-btn" class="flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
              <path d="M21 21l-6 -6" />
            </svg>
          </button>
        </li>
      </ul>
    </nav>
    <?php
    if (ishomepage()) {
    ?>
      <section class="mt-16 flex justify-center bg-gray-700">
        <img src="<?= assets("images/hero-3.jpg") ?>" alt="hero">
      </section>
    <?php
    }
    ?>
  </header>
  <div class="fixed inset-0 bg-zinc-950/30 backdrop-blur-lg p-12 pt-20 z-20 hidden [&.active]:block " id="search-modal">
    <button class="text-gray-400 absolute top-7 right-10 text-xl" id="search-close-btn">X</button>
    <form class="w-full max-w-sm flex items-center gap-2 mx-auto border-2 border-gray-400 rounded-full px-4" action="<?= main_url ?>#main" id="search-form">
      <div class="text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-search">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
          <path d="M21 21l-6 -6" />
        </svg>
      </div>
      <input type="text" autofocus name="search" class="w-full py-3 outline-none" placeholder="Search post...">
    </form>
  </div>
  <main class="py-24 px-8 max-w-6xl mx-auto relative min-h-[100dvh] grid" id="main">