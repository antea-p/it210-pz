<?php
include_once('database/connection.php');
include_once('utils/session.php');
include('database/RecipeStepDataSource.php');


session_start();

if (!isset($_POST['id'])) {
    header('Location: index.php');
    die();
}

$stepId = $_POST['id'];

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

$recipeSteps->delete_step($stepId);
header("Location: recipe.php?id={$stepRecipeInfo['id']}");
die();