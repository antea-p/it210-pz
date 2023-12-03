<?php
include('utils/validation.php');
include('utils/page.php');

if (isset($_POST['submit'])) {
    if (isset($_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone'], $_POST['customerMessage'])) {

        $invalidName = null;
        $invalidSurname = null;
        $invalidEmail = null;
        $invalidPhoneNumber = null;
        $invalidMessage = null;

        $name = $_POST['name'];
        $nameMatch = preg_match('/^[a-zA-Z]/', $name);


        if (!$nameMatch || is_too_short($name, 1)) {
            $invalidName = true;
        }

        $surname = $_POST['surname'];
        $surnameMatch = preg_match('/^[a-zA-Z]/', $surname);

        if (!$surnameMatch || is_too_short($name, 1)) {
            $invalidSurname = true;
        }

        // https://www.w3schools.com/php/php_filter.asp
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalidEmail = true;
        }

        $phone = $_POST['phone'];
        $phoneMatch = preg_match('/^[+]?\d+$/', $phone);
        if (!$phoneMatch) {
            $invalidPhoneNumber = true;
        }

        $message = $_POST['customerMessage'];
        if (is_too_short($message, 64) || (is_too_long($message, 1024))) {
            $invalidMessage = true;
        }
        
        if (!(isset($invalidName) || isset($invalidSurname) || isset($invalidEmail) || isset($invalidPhoneNumber) || isset($invalidMessage))) {
            header('Location: index.php');
            die();
        }
    } else {
        $emptyContactFields = true;
    }
}
start_page('Kontaktirajte nas');
include('partials/header.php');
include ('partials/side-menu.php');
echo "
<div class='layout'>
<div>
   <form class='form' method='post'>";
    echo (isset($invalidName)) ?
        "<div class='alert'>Ime se smije sastojati isključivo od slova!</div>"
        : '';
    echo "
         <input class='input' placeholder='Ime' name='name'>";
    echo (isset($invalidSurname)) ?
        "<div class='alert'>Prezime se smije sastojati isključivo od slova!</div>"
        : '';
    echo "
     <input class='input' placeholder='Prezime' name='surname'>";
    echo (isset($invalidEmail)) ?
        "<div class='alert'>Neispravan format e-mail adrese!</div>"
        : '';
    echo "
     <input class='input' placeholder='Email' type='email' name='email'>";
    echo (isset($invalidPhoneNumber)) ?
        "<div class='alert'>Neispravan format tel. broja! Prvi karakter može biti + ili cifra, a svi ostali smiju biti
         samo cifre (0-9)!</div>"
        : '';
    echo "
     <input class='input' placeholder='Tel. broj' type='tel' name='phone'>";
    echo (isset($invalidMessage)) ?
        "<div class='alert'>Poruka mora sadržavati između 64 i 1024 karaktera!</div>"
        : '';
    echo "
     <textarea class='input' placeholder='Poruka' name='customerMessage'></textarea>
     <input class='button' type='submit' name='submit' value='Pošalji'>
     ";
     echo (isset($emptyRegisterFields)) ?
            "<div class='alert'>Moraju biti popunjena sva polja forme!</div>"
            : '';
     echo "
   </form>
 </div>
 <div>
   <div class='contact-info text-center'>
     <h2 class='heading'>Kontakt info</h2>
     <div><span class='heading'>Email: </span>info@sweetsensations.com</div>
     <div><span class='heading'>Telefon: </span>01234 567890</div>
     <div><span class='heading'>Adresa: </span>Harry Potter Studio Tour, Leavesden,<br> Watford WD25 7LR, UK</div>
     <div class='contact-map-container'>
       <iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2532621.888824038!2d-4.899741074999993!3d51.69162179999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48764103e950758d%3A0x2b152593ceb59b52!2sHogwarts%20Castle!5e0!3m2!1shr!2shr!4v1660072462536!5m2!1shr!2shr' width='600' height='450' style='border:0;' allowfullscreen='' loading='lazy' referrerpolicy='no-referrer-when-downgrade'></iframe></iframe>
     </div>
   </div>
 </div>
</div>
";
include('partials/footer.php');
end_page();
