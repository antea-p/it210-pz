<?php

const QUERY = 'SELECT Recipes.id, created_by_user_id, title, 
    Categories.name AS category, Categories.image_url AS image_url,
    description, preparation_time_minutes,
    Preparation_Difficulties.name AS difficulty,
    number_of_servings, Users.username AS creator_username
    FROM recipe_site.Recipes
    JOIN recipe_site.Users ON Recipes.created_by_user_id = Users.id
    JOIN recipe_site.Categories ON Recipes.category_id = Categories.id
    JOIN recipe_site.Preparation_Difficulties on Recipes.preparation_difficulty_id = Preparation_Difficulties.id';


class RecipeDataSource
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    public function get_categories(): array
    {
        $stmt = $this->con->prepare("SELECT * FROM recipe_site.Categories");
        $stmt->execute();
        $result = $stmt->get_result();

        $category_array = array();
        foreach ($result as $row) {
            $category_array[] = $row;
        }
        $result->close();
        return $category_array;
    }

    public function get_preparation_difficulties(): array
    {
        $stmt = $this->con->prepare("SELECT * FROM recipe_site.Preparation_Difficulties");
        $stmt->execute();
        $result = $stmt->get_result();

        $difficulty_array = array();
        foreach ($result as $row) {
            $difficulty_array[] = $row;
        }
        $result->close();
        return $difficulty_array;
    }

    public function get_category($id): array|null
    {
        $category_row = null;
        $stmt = $this->con->prepare("SELECT id, name, image_url FROM recipe_site.Categories
        WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $category_row = $result->fetch_array();
        }
        $result->close();
        return $category_row;
    }

    public function get_preparation_difficulty($id): array|null
    {
        $difficulty_row = null;
        $stmt = $this->con->prepare('SELECT id, name FROM recipe_site.Preparation_Difficulties
        WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $difficulty_row = $result->fetch_array();
        }
        $result->close();
        return $difficulty_row;
    }

    public function insert_recipe($user_id, $title, $category_id, $description, $prep_time_minutes, $difficulty_id, $servings): int
    {
        $stmt = $this->con->prepare('INSERT INTO recipe_site.Recipes(created_by_user_id, title, category_id, 
        description, preparation_time_minutes, preparation_difficulty_id, number_of_servings) 
        VALUES  (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isisiii', $user_id, $title, $category_id, $description, $prep_time_minutes, $difficulty_id, $servings);
        $stmt->execute();

        $recipe_id = $stmt->insert_id;
        $stmt->close();

        return $recipe_id;
    }

    public function get_recipe($id): array|null
    {
        $recipe_row = null;
        $query = QUERY . ' WHERE Recipes.id = ?';
        $stmt = $this->con->prepare($query);

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $recipe_row = $result->fetch_array();
        }
        $result->close();
        return $recipe_row;
    }

    public function delete_recipe($id): void
    {
        $stmt = $this->con->prepare('DELETE FROM recipe_site.Recipes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    public function get_recipes_by_category($categoryId): array
    {
        $stmt = $this->con->prepare(QUERY . ' WHERE Recipes.category_id = ?');
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();

        $result = $stmt->get_result();

        $recipes_array = array();
        foreach ($result as $row) {
            $recipes_array[] = $row;
        }
        $result->close();
        return $recipes_array;
    }


    public function get_owner_recipes($user_id): array
    {
        $stmt = $this->con->prepare(QUERY . ' WHERE Recipes.created_by_user_id = ?');
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $recipes_array = array();
        foreach ($result as $row) {
            $recipes_array[] = $row;
        }
        $result->close();
        return $recipes_array;
    }

    public function get_most_recent_recipes(): array
    {
        $stmt = $this->con->prepare(QUERY . ' ORDER BY Recipes.id DESC LIMIT 10;');
        $stmt->execute();

        $result = $stmt->get_result();

        $recipes_array = array();
        foreach ($result as $row) {
            $recipes_array[] = $row;
        }
        $result->close();
        return $recipes_array;
    }
}