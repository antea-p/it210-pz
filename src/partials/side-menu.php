<?php
include_once('./database/connection.php');
include_once('./utils/session.php');
include_once('./database/RecipeDataSource.php');

$userId = get_session_user_id();
if ($userId === null) {
    echo "
    <div class='side-menu hidden'>
      <div class='side-menu-button hidden'> > </div>
    Trenutno niste logirani. Ako se ulogirate, moći ćete ovdje vidjeti svoje recepte.";
}
else {
    $con = connect_to_database();
    $recipes = new RecipeDataSource($con);
    $ownerRecipesArray = $recipes->get_owner_recipes($userId);
    echo "
    <div class='side-menu hidden'>
      <div class='side-menu-button hidden'> > </div>";
    foreach ($ownerRecipesArray as $row) {
        $title = htmlspecialchars($row['title']);
        echo "<a class='side-menu-recipe' href='recipe.php?id={$row['id']}'>$title</a>";
    }
}
echo "
</div>";



