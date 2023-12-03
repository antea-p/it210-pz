<?php
include_once('database/connection.php');
include_once('utils/page.php');
include('database/RecipeDataSource.php');
include('database/RecipeStepDataSource.php');
include('utils/validation.php');
include('utils/session.php');

if (!isset($_GET['recipeId']) && !isset($_POST['recipeId'])) {
    header('Location: index.php');
    die();
}

$recipeId = $_POST['recipeId'] ?? $_GET['recipeId'];

$con = connect_to_database();
$recipes = new RecipeDataSource($con);
$recipeSteps = new RecipeStepDataSource($con);

$recipe = $recipes->get_recipe($recipeId);
if ($recipe === null) {
    header('Location: index.php');
    die();
}

if (!can_edit_recipe($recipe)) {
    header('Location: login.php');
    die();
}

if (isset($_POST['submit'], $_POST['step'])) {
    $invalidStep = null;

    $step = $_POST['step'];
    if (is_too_short($step, 1) || (is_too_long($step, 256))) {
        $invalidStep = true;
    }

    if (!isset($invalidStep)) {
        $recipeSteps->add_step($recipeId, $step);
        header("Location: recipe.php?id={$recipeId}");
        die();
    }
}

start_page('Dodaj korak');
include('partials/header.php');
include ('partials/side-menu.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
  <form class='form form-dark' method='post'>
    <h2>Novi korak</h2>";
echo isset($invalidStep)?
    '<div class="alert-dark">Molimo, dodajte korak. Opis ne smije biti duži od 256 karaktera.</div>'
    : '';
echo "
    <input class='input' name='step' placeholder='Korak'>
    <input type='hidden' name='recipeId' value='{$recipeId}'>
    <input class='button' type='submit' name='submit' value='Dodaj'>
  </form>
</div>
";

include('partials/footer.php');
end_page();