<?php

function connect_to_database(): ?mysqli
{
    $mysqli = null;
    try {
        $mysqli = new mysqli('localhost:3307', 'recipe_site_user',
            'password', 'recipe_site');
    } catch (mysqli_sql_exception $e) {
        echo "Error: {$e->getMessage()}";
    }
    return $mysqli;
}