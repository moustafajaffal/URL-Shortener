<?php

require "functions/dbConn.php";
$error = false;
if (isset($_GET["error"])) {
  if ($_GET["error"] == true) {
    $error = true;
  }
} else {

  if (isset($_GET["code"])) {
    

    $conn = connect();
    $code = $_GET["code"];

    $stmt = $conn->prepare("SELECT URL FROM links WHERE Ext=?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $link = $result->fetch_assoc();

    if ($result->num_rows > 0) {
      $redirect = $link["URL"];
      header("Location: ".$redirect."");
    } else {
      header("Location: index.php?error");
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>URL shorterner</title>
  </head>
  <body>
    <main>
      <div class="container">
        <section class="middle">
          <form method="POST" action="functions/Shortener.php">
            <input type="text" placeholder="shorten a link here" name="link" required spellcheck="false"/>
            <button type="submit" name="btnShortenIt" class="submit">Shorten it!</button>
            <img
              src="images/bg-shorten-desktop.svg"
              alt="shorten url desktop svg"
              class="desktop shorten"
            />
            <img
              src="images/bg-shorten-mobile.svg"
              alt="shorten url desktop svg"
              class="mobile shorten"
            />
          </form>

          <?php
          
          if ($error == true) {
            echo '        
            <div class="results">
            <p style="color: red; font-weight: bold; text-align: center;">Check URL Format!<br> Your URL must include https://</p>
          </div>';
          }
          function excerpt($link) {
            $new = substr($link, 0, 30);
    
            if (strlen($link) > 30) {
                return $new.'...';
            } else {
                return $link;
            }
        }
          session_start();
          if (isset($_SESSION["newURL"])) {

            echo '        
            <div class="results"><div class="item">
            <p>'.excerpt($_SESSION["mainURL"]).'</p>
            <p id="result"><a target="_blank" href="https://'.$_SESSION["newURL"].'">'.$_SESSION["newURL"].'</a></p>
            <button class="copy" onclick="copyToClipboard()">Copy</button>
          </div>
          </div>';
          session_unset();
          session_destroy();

          }

          ?>
        </section>
      </div>
    </main>
    <script src="main.js"></script>
  </body>
</html>
