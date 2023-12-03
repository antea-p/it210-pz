<?php
include_once('./utils/session.php');
$username = get_session_username();
echo "
<div class='header'>
  <div class='header-panel'>
    <a href='index.php' class='header-link header-link-right'>
      Početna
    </a>
    <a href='categories.php' class='header-link header-link-left'>
      Kategorije
    </a>
    <a href='index.php' class='header-logo'>
      Sweet Sensations
    </a>";
if (isset($username)) {
    echo "
    <a href='add-recipe.php' class='header-link header-link-right'>
      Dodaj recept
    </a>
    <a href='logout.php' class='header-link header-link-left username-link'>
        <span class='username'>$username</span><br>
    (Odlogiraj se)
    </a>
  </div>";
} else {
    echo "
    <a href='login.php' class='header-link header-link-right'>
      Login
    </a>
    <a href='register.php' class='header-link header-link-left'>
      Registracija
    </a>
  </div>";
}
echo "</div>";
?>

