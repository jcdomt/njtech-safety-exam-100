<?php
    if (!isset($_COOKIE['cookie'])) {
        header("Location: /timu/login.html");
        exit();
    } else {
        header("Location: /timu/do.html");
        exit();
    }