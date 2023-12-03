<?php
include_once('./utils/page.php');
include('header.php');
include('side-menu.php');
start_page('Greška');
echo "
<div class='layout'>
    <div class='notfound' >
        <div class='notfound-header' >
    Greška
    </div >
        <div class='notfound-body' >
    Tražena stranica ne postoji.<br >
          <br >
          <a href = 'index.php'>Početna stranica</a >
        </div >
      </div >
</div>
";
include('footer.php');
die();