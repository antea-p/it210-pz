<?php
include_once('database/connection.php');
include_once('utils/page.php');
include('utils/session.php');
include('database/RecipeStepDataSource.php');
include('utils/validation.php');


if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header('Location: index.php');
    die();
}

$stepId = $_POST['id'] ?? $_GET['id'];

$con = connect_to_database();
$recipeSteps = new RecipeStepDataSource($con);

$stepRecipeInfo = $recipeSteps->get_step_recipe_info($stepId);
if ($stepRecipeInfo === null) {
    header('Location: index.php');
    die();
}

if (!can_edit_recipe($stepRecipeInfo)) {
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
        $recipeSteps->edit_step($stepId, $step);
        header("Location: recipe.php?id={$stepRecipeInfo['id']}");
        die();
    }
}
start_page('Izmijeni korak');
include('partials/header.php');
include ('partials/side-menu.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
  <form class='form form-dark' method='post'>
    <h2>Izmjena koraka</h2>";
echo isset($invalidStep)?
    '<div class="alert-dark">Molimo, dodajte korak. Opis ne smije biti duži od 256 karaktera.</div>'
    : '';
echo "
    <input class='input' name='step' placeholder='Korak'>
    <input type='hidden' name='id' value='{$stepId}'>
    <input class='button' type='submit' name='submit' value='Izmijeni'>
  </form>
</div>
";

include('partials/footer.php');