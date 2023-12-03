<?php
include_once('database/connection.php');
include_once('utils/session.php');
include('utils/validation.php');
include('database/RecipeDataSource.php');
include('utils/page.php');

$con = connect_to_database();
$recipes = new RecipeDataSource($con);

if (get_session_username() === null) {
    header('Location: login.php');
    die();
}


if (isset($_POST['submit'])) {
    if (isset($_POST['recipeTitle'], $_POST['categoryId'], $_POST['description'], $_POST['prepTimeHours'],
        $_POST['prepTimeMinutes'], $_POST['difficultyId'], $_POST['servings'])) {

        $invalidTitle = null;
        $invalidCategoryId = null;
        $invalidDescription = null;
        $invalidPrepHours = null;
        $invalidPrepMins = null;
        $invalidDifficultyId = null;
        $invalidServings = null;

        $recipeTitle = $_POST['recipeTitle'];
        if (is_too_short($recipeTitle, 3) || (is_too_long($recipeTitle, 64))) {
            $invalidTitle = true;
        }


        $categoryId = $_POST['categoryId'];
        if (!ctype_digit($categoryId) || ($recipes->get_category($categoryId) === null)) {
            $invalidCategoryId = true;
        }

        $description = $_POST['description'];
        if (is_too_long($description, 1024)) {
            $invalidDescription = true;
        }

        $prepHours = $_POST['prepTimeHours'];
        if ($prepHours < 0 || !(ctype_digit($prepHours))) {
            $invalidPrepHours = true;
        }

        $prepMins = $_POST['prepTimeMinutes'];
        if ($prepMins < 0 || $prepMins > 59 || !(ctype_digit($prepMins))) {
            $invalidPrepMins = true;
        }

        $difficultyId = $_POST['difficultyId'];
        if (!ctype_digit($difficultyId) || ($recipes->get_preparation_difficulty($difficultyId) === null)) {
            $invalidDifficultyId = true;
        }

        $servings = $_POST['servings'];
        if ($servings < 1 || !(ctype_digit($servings))) {
            $invalidServings = true;
        }

        if ($prepMins === '0' && $prepHours === '0') {
            $invalidPrepMins = true;
        }

        if (!(isset($invalidTitle) || isset($invalidCategoryId) || isset($invalidDescription) 
            || isset($invalidPrepHours) || isset($invalidPrepMins) || isset($invalidDifficultyId) || isset($invalidServings))) {
            $prepTime = ($prepHours * 60) + $prepMins;
            $userId = get_session_user_id();
            $recipeId = $recipes->insert_recipe($userId, $recipeTitle, $categoryId, 
                $description, $prepTime, $difficultyId, $servings);
            header("Location: recipe.php?id={$recipeId}");
            die();
        }
        
    } else {
        $emptyRecipeFields = true;
    }
}


start_page('Dodaj recept');
include('partials/header.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
  <form class='form form-dark' method='post'>
    <h2>Dodaj recept</h2>";
echo (isset($invalidTitle)) ?
    "<div class='alert-dark'>Naziv mora imati između 3 i 64 karaktera.</div>"
    : '';
echo "
    <input class='input' name='recipeTitle' placeholder='Naziv recepta'>";
echo (isset($invalidCategoryId)) ?
    "<div class='alert-dark'>Molimo, izaberite kategoriju.</div>"
    : '';
echo "
    <select class='input' name='categoryId'>";
echo "
      <option selected=''>Kategorija</option>";
$categoryResult = $recipes->get_categories();
foreach ($categoryResult as $category) {
    echo "<option value={$category['id']}>{$category['name']}</option>";
}
echo "</select>";
echo  (isset($invalidDescription)) ?
    "<div class='alert-dark'>Opis ne smije sadržavati više od 1024 znaka.</div>"
    : '';
echo "
    <textarea name='description' class='input' placeholder='Opis'></textarea>
    <div>";
echo (isset($invalidPrepHours) || isset($invalidPrepMins)) ?
    "<div class='alert-dark'>Molimo, unesite vrijeme pripreme.</div>"
    : '';
echo "
      Vrijeme pripreme:
      <input class='input-small' type='number' min='0' value='0' name='prepTimeHours'> h i
      <input class='input-small' type='number' min='0' max='59' value='0' name='prepTimeMinutes'> min.
    </div>";
echo (isset($invalidDifficultyId)) ?
    "<div class='alert-dark'>Molimo, unesite težinu pripreme.</div>"
    : '';
echo "
      <select class='input' name='difficultyId'>
      <option selected=''>Težina pripreme</option>";
$difficultyResult = $recipes->get_preparation_difficulties();
foreach ($difficultyResult as $difficulty) {
    echo "<option value={$difficulty['id']}>{$difficulty['name']}</option>";
}
echo "</select>";
echo (isset($invalidServings)) ?
    "<div class='alert-dark'>Molimo, unesite broj porcija.</div>" : '';
echo "
    <input class='input' type='number' min='1' name='servings' placeholder='Broj porcija'>
    <input class='button' type='submit' name='submit' value='Dodaj'>";
echo (isset($emptyRecipeFields)) ?
    "<div class='alert-dark'>Moraju biti popunjena sva polja forme!</div>"
    : '';
echo "
  </form>
</div>
";
include('partials/footer.php');
end_page();