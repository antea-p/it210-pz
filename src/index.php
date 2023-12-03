<?php
include_once('database/connection.php');
include_once('database/RecipeDataSource.php');
include("utils/page.php");

$con = connect_to_database();
$recipes = new RecipeDataSource($con);
$recentRecipesArray = $recipes->get_most_recent_recipes();

start_page("Dobrodošli");
include("partials/header.php");
include('partials/side-menu.php');
echo "
<div class='layout'>
    <div class='slider'>
        <div class='slider-button slider-button-left'>
          <
        </div>
        <div class='slider-content'>
          <div class='slider-content-inner'>";
    foreach ($recentRecipesArray as $recipe) {
        $recipeTitle = htmlspecialchars($recipe['title']);
        $hours = (int) ($recipe['preparation_time_minutes'] / 60);
        $minutes = $recipe['preparation_time_minutes'] % 60;
        echo "<a class='recipe-card' href='recipe.php?id={$recipe['id']}'>
              <img class='recipe-card-image' src='{$recipe['image_url']}' alt='$recipeTitle'>
              <div class='recipe-card-heading'>$recipeTitle</div>
              <div class='recipe-card-content'>Vrijeme: ";
                if ($hours > 0) {
                    echo "$hours h ";
                }
                echo "$minutes min
              </div>
              <div class='recipe-card-content'>Porcija: {$recipe['number_of_servings']}</div>
            </a>";
    }
echo "
        </div>
    </div>
    <div class='slider-button slider-button-right'>
      &gt;
    </div>
  </div>
</div>
";
include("partials/footer.php");
end_page();
