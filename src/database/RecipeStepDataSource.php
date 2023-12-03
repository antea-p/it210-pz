<?php
class RecipeStepDataSource {

    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    public function add_step($recipeId, $step): int
    {
        $stmt = $this->con->prepare('INSERT INTO recipe_site.Recipe_Steps(recipe_id, step)
        VALUES (?, ?)');
        $stmt->bind_param("is", $recipeId, $step);
        $stmt->execute();

        $step_id = $stmt->insert_id;
        $stmt->close();

        return $step_id;
    }

    public function get_steps($recipeId): array
    {
        $stmt = $this->con->prepare('SELECT id, recipe_id, step FROM recipe_site.Recipe_Steps WHERE recipe_id = ?');
        $stmt->bind_param("i", $recipeId);
        $stmt->execute();
        $result = $stmt->get_result();

        $steps_array = array();
        foreach ($result as $row) {
            $steps_array[] = $row;
        }
        $result->close();
        return $steps_array;
    }

    public function get_step_recipe_info($stepId): array|null
    {
        $stmt = $this->con->prepare('SELECT 
            Recipes.id, Recipes.created_by_user_id FROM recipe_site.Recipe_Steps
            JOIN recipe_site.Recipes on Recipes.id = Recipe_Steps.recipe_id
            WHERE Recipe_Steps.id = ?');
        $stmt->bind_param("i", $stepId);
        $stmt->execute();
        $result = $stmt->get_result();

        $info_row = null;
        if ($result->num_rows > 0) {
            $info_row = $result->fetch_array();
        }
        $result->close();
        return $info_row;
    }

    public function delete_step($id): void
    {
        $stmt = $this->con->prepare('DELETE FROM recipe_site.Recipe_Steps WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    public function edit_step($id, $step): void
    {
        $stmt = $this->con->prepare('UPDATE recipe_site.Recipe_Steps SET step = ? WHERE id = ?');
        $stmt->bind_param('si', $step, $id);
        $stmt->execute();
        $stmt->close();
    }
}