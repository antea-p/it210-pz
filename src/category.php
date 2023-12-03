<?php
include_once('database/connection.php');
include('database/RecipeDataSource.php');
include('utils/page.php');


if (!isset($_GET['id'])) {
    include('partials/notfound.php');
}

$categoryId = $_GET['id'];

$con = connect_to_database();
$recipes = new RecipeDataSource($con);

$category = $recipes->get_category($categoryId);
if ($category === null) {
    include('partials/notfound.php');
}

$matchingRecipes = $recipes->get_recipes_by_category($categoryId);

start_page($category['name']);
include('partials/header.php');
include ('partials/side-menu.php');
echo "
    <h2 class='heading'>Kategorija: {$category['name']}</h2>
    <div class='layout'>";
if (count($matchingRecipes) > 0) {
    echo "
      <div class='recipe-list'>";
        foreach ($matchingRecipes as $recipe) {
            $recipeTitle = htmlspecialchars($recipe['title']);
            $hours = (int) ($recipe['preparation_time_minutes'] / 60);
            $minutes = $recipe['preparation_time_minutes'] % 60;
            echo "
            <a class='recipe-card' href='recipe.php?id={$recipe['id']}'>
                <img class='recipe-card-image' src='{$recipe['image_url']}' alt='{$recipeTitle}'>
                <div class='recipe-card-heading'>{$recipeTitle}</div>
                <div class='recipe-card-content'>Priprema: ";
                    if ($hours > 0) {
                        echo "$hours h ";
                    }
                    echo "$minutes min
                </div>
                <div class='recipe-card-content'>Porcija: {$recipe['number_of_servings']}</div>
            </a>
          ";
        }
    echo "</div>";
}
echo "
</div>
";

include('partials/footer.php');
end_page();