<?php
function set_session_user($user): void
{
    session_start();
    $_SESSION['username'] = $user['username'];
    $_SESSION['userId'] = $user['id'];
    $_SESSION['isAdmin'] = $user['is_admin'];
}

function get_session_username() {
    return $_SESSION['username'] ?? null;
}

function get_session_user_id() {
    return $_SESSION['userId'] ?? null;
}

function is_session_admin() {
    return $_SESSION['isAdmin'] ?? null;
}

function can_edit_recipe($recipe): bool
{
    return is_session_admin() || get_session_user_id() === $recipe['created_by_user_id'];
}

function log_out_session(): void
{
//    unset($_SESSION['name'], $_SESSION['userId'], $_SESSION['isAdmin']);
    session_destroy();
}

