<?php

session_start();
session_unset();
session_destroy();


header("Location:/sam/all_view_home.php");
die();