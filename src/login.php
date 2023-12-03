<?php
include_once('database/connection.php');
include_once('utils/session.php');
include_once('utils/validation.php');
include('database/UserDataSource.php');

$con = connect_to_database();
$users = new UserDataSource($con);

if (isset($_POST['submit'])) {
    if (isset($_POST['usernameInput'], $_POST['passwordInput'])) {

        $invalidUsername = null;
        $invalidPassword = null;

        $username = $_POST['usernameInput'];
        if (is_too_short($username, 1)) {
            $invalidUsername = true;
        }

        $password = $_POST['passwordInput'];
        if (is_too_short($password, 1)) {
            $invalidPassword = true;
        }

        if (!(isset($invalidUsername) || isset($invalidPassword))) {
            $password_hash = hash('sha256', $_POST['passwordInput']);
            $found_user = $users->get_user($username, $password_hash);
            if ($found_user) {
                set_session_user($found_user);
                header('Location: index.php');
                die();
            } else {
                $notFound = true;
            }
        }

    } else {
        $emptyLoginFields = true;
    }
}

include('utils/page.php');

start_page('Login');
include('partials/header.php');
include('partials/side-menu.php');
?>
    <div class='layout'>
        <form class='form' method='post'>
            <?php
            echo (isset($notFound) || isset($invalidUsername) || isset($invalidPassword)) ? "<div class='alert'>Neispravno korisničko ime i/ili lozinka!</div>" : '';
            echo (isset($emptyLoginFields)) ? "<div class='alert'>Moraju biti popunjena sva polja forme!</div>" : '';
            ?>
            <input class='input' placeholder='Korisničko ime' name='usernameInput'>
            <input class='input' placeholder='Lozinka' type='password' name='passwordInput'>
            <input class='button' type='submit' name='submit' value='Log In'>
        </form>
    </div>
<?php
include('partials/footer.php');
end_page();
?>