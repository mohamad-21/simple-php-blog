<?php

require "../config.php";

session_unset();
session_destroy();
header("Location: " . main_url);
