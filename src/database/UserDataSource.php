<?php

class UserDataSource
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    public function get_user($username, $password_hash): array|null
    {
        $user_row = null;
        $stmt = $this->con->prepare('SELECT id, username, password_hash, is_admin FROM recipe_site.Users
            WHERE username = ? AND password_hash = ?');
        $stmt->bind_param('ss', $username, $password_hash);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_row = $result->fetch_array();
        }
        $result->close();
        return $user_row;
    }

    public function save_user($username, $password_hash): array
    {
        $stmt = $this->con->prepare('INSERT INTO recipe_site.Users(username, password_hash, is_admin)
         VALUES (?, ?, false)');
        $stmt->bind_param('ss', $username, $password_hash);
        $stmt->execute();

        $user_array = array();
        $user_array["id"] = $stmt->insert_id;
        $user_array["username"] = $username;
        $user_array["is_admin"] = false;

        return $user_array;
    }

    public function user_exists($username): bool {
        $exists = false;
        $stmt = $this->con->prepare('SELECT username FROM recipe_site.Users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $exists = true;
        }
        $result->close();
        return $exists;
    }
}