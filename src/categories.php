<?php
include_once('database/connection.php');
include('database/RecipeDataSource.php');

$con = connect_to_database();
$recipes = new RecipeDataSource($con);
$categories = $recipes->get_categories();

include('utils/page.php');

start_page('Kategorije');
include('partials/header.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
  <div class='recipe-list'><a href='category.php?id=1' class='recipe-card'>
    <img class='recipe-card-image' src='static/images/cake.png' alt='Cakes'>
    <div class='recipe-card-heading'>Torte</div>
  </a><a href='category.php?id=2' class='recipe-card'>
    <img class='recipe-card-image' src='static/images/brownie.png' alt='Brownies'>
    <div class='recipe-card-heading'>Browniesi</div>
  </a><a href='category.php?id=3' class='recipe-card'>
    <img class='recipe-card-image' src='static/images/cookie.png' alt='Cookies'>
    <div class='recipe-card-heading'>Keksi</div>
  </a><a href='category.php?id=4' class='recipe-card'>
    <img class='recipe-card-image' src='static/images/bar.png' alt='Bars'>
    <div class='recipe-card-heading'>Pločice</div>
  </a></div>
</div>
";

include('partials/footer.php');
end_page();