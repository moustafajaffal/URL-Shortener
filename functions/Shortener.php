<?php

require "dbConn.php";

function generateLinkKey() {
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWZYTabcdefghijklmnopqrstuvwzyt";

    $letters = substr(str_shuffle($chars), 0, 2);
    $num = random_int(1000, 9999);

    $linkKey = $letters.$num;
    return $linkKey;
}

if (isset($_POST["btnShortenIt"])) {

    $conn = connect();
    $linkKey = generateLinkKey();
    $link = $_POST["link"];
    
    if (!empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {

        $stmt = $conn->prepare("INSERT INTO links (URL, Ext) VALUES (?, ?)");
        $stmt->bind_param("ss", $link, $linkKey);
        $exec = $stmt->execute();

        if ($exec) {
            $url = $_SERVER["SERVER_NAME"]."/projects/URL-Shortener/".$linkKey;
            session_start();
            $_SESSION["mainURL"] = $link;
            $_SESSION["newURL"] = $url;

            header("Location: ../index.php?status=success");
        }
    } else {
        header("Location: ../index.php?error=true");
    }

} else {
    header("Location: ../index.php");
}