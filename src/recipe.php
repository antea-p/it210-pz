<?php
include_once('database/connection.php');
include('database/RecipeDataSource.php');
include('database/RecipeStepDataSource.php');
include('utils/page.php');


if (!isset($_GET['id'])) {
    include('partials/notfound.php');
}

$recipeId = $_GET['id'];

$con = connect_to_database();
$recipes = new RecipeDataSource($con);
$recipeSteps = new RecipeStepDataSource($con);

$recipe = $recipes->get_recipe($recipeId);
if ($recipe === null) {
    include('partials/notfound.php');
}
$recipeTitle = htmlspecialchars($recipe['title']);
$stepsArray = $recipeSteps->get_steps($recipe['id']);

start_page($recipeTitle);
include('partials/header.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
  <div class='recipe-display'>
    <div class='recipe-display-header'>";
    echo "
      <div class='flex-grow-1'>{$recipeTitle}</div>";
    if (can_edit_recipe($recipe)) {
        echo "
        <a class='button' href='add-step.php?recipeId={$recipeId}'>Dodaj korak</a>
        <form class='form-small' action='delete-recipe.php' method='post'>
            <input type='hidden' value='{$recipe['id']}' name='id'>
            <input class='button' type='submit' value='Izbriši recept'>
          </form>
        ";
    }
echo "
    </div>
    <div class='recipe-display-body'>
      <div class='recipe-display-contents'>
        <h2> Opis: </h2>";
    $recipeDescription = htmlspecialchars($recipe['description']);
echo "
        <div class='recipe-display-info-field'>{$recipeDescription}</div>
        <br>
        <br>
        <h2> Koraci: </h2>
        <ol>";
    foreach ($stepsArray as $step) {
        $stepText = htmlspecialchars($step['step']);
        echo "<li class='list-item'>
              <h2 class='flex-grow-1'>{$stepText}</h2>";
        if (can_edit_recipe($recipe)) {
            echo "
              <form class='form-small' action='delete-step.php' method='post'>
                <input type='hidden' name='id' value='{$step['id']}'>
                <input type='submit' class='button' value='Izbriši'>
              </form>
              <a class='button' href='edit-step.php?id={$step['id']}'>Izmijeni</a>
            ";
        }
        echo "</li>";
    }
    echo "
        </ol>
      </div>
      <div class='recipe-display-info'>
        <div class='recipe-display-info-field'> Autor: {$recipe['creator_username']} </div>
        <div class='recipe-display-info-field'> Porcija: {$recipe['number_of_servings']} </div>
        <div class='recipe-display-info-field'> Kategorija: {$recipe['category']} </div>
        <div class='recipe-display-info-field'> Težina: {$recipe['difficulty']} </div>
        <div class='recipe-display-info-field'> Vrijeme pripreme: ";
        $hours = (int) ($recipe['preparation_time_minutes'] / 60);
        $minutes = $recipe['preparation_time_minutes'] % 60;
        if ($hours > 0) {
            echo "$hours h ";
        }
        echo "$minutes min </div >
      </div>
    </div>
  </div>
</div>
";

include('partials/footer.php');
end_page();