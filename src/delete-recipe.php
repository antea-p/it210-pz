<?php
include_once('database/connection.php');
include_once('utils/session.php');
include('database/RecipeDataSource.php');

if (!isset($_POST['id'])) {
    header('Location: index.php');
    die();
}

session_start();

$recipeId = $_POST['id'];

$con = connect_to_database();
$recipes = new RecipeDataSource($con);

$recipe = $recipes->get_recipe($recipeId);
if ($recipe === null) {
    header('Location: index.php');
    die();
}

if (!can_edit_recipe($recipe)) {
    header('Location: login.php');
    die();
}

$recipes->delete_recipe($recipeId);
header('Location: index.php');
die();
