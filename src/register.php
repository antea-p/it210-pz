<?php
include_once('database/connection.php');
include_once('utils/session.php');
include_once('utils/validation.php');
include('database/UserDataSource.php');

$con = connect_to_database();
$users = new UserDataSource($con);

// https://stackoverflow.com/a/47240310
if (isset($_POST['submit'])) {
    if (isset($_POST['usernameInput'], $_POST['passwordInput'], $_POST['passwordRepeatInput'])) {
        
        $invalidUsername = null;
        $invalidPassword = null;

        $username = $_POST['usernameInput'];
        $foundMatch = preg_match('/^[a-zA-Z0-9]+$/', $username);
        
        $password = $_POST['passwordInput'];
        $passwordRepeat = $_POST['passwordRepeatInput'];
        
        if (!$foundMatch || strlen($username) > 64 || strlen($username) === 0) {
            $invalidUsername = true;
        }
         
        if ($password !== $passwordRepeat || strlen($password) < 8) {
            $invalidPassword = true;
        }

        if ($users->user_exists($username)) {
            $userExists = true;
        }
        
        if (!(isset($invalidUsername) || isset($invalidPassword) || isset($userExists))) {
            $password_hash = hash('sha256', $password);
            $user_result = $users->save_user($username, $password_hash);
            set_session_user($user_result);
            header('Location: index.php');
            die();
        }
    } else {
        $emptyRegisterFields = true;
    }

}


include('utils/page.php');

start_page('Registracija');
include('partials/header.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
    <form class='form' method = 'post' >";
        echo (isset($invalidUsername)) ?
            "<div class='alert'>Neispravno korisničko ime! Može imati najviše 64 karaktera, i ne smije
                      sadržavati razmak ni druge specijalne karaktere!</div>"
            : '';
        echo (isset($userExists)) ?
            "<div class='alert'>Korisničko ime već postoji!</div>"
            : '';
        echo "<input class='input' placeholder='Korisničko ime' name='usernameInput' >";
        echo (isset($invalidPassword)) ?
            "<div class='alert'>Neispravna lozinka! Mora imati najmanje 8 karaktera, i treba da bude ista u oba polja!</div>"
            : '';
        echo "
        <input class='input' placeholder='Lozinka' type='password' name='passwordInput' >
        <input class='input' placeholder='Ponoviti lozinku' type='password' name='passwordRepeatInput' >
        <input class='button' type='submit' name='submit' value='Registriraj se' >";
        echo (isset($emptyRegisterFields)) ?
            "<div class='alert'>Moraju biti popunjena sva polja forme!</div>"
            : '';
        echo "
      </form>
 </div>
";
include('partials/footer.php');
end_page();

