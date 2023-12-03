<?php

session_start();

function start_page($title): void
{
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <title>{$title}</title>
            <link rel='stylesheet' type='text/css' href='static/style.css'>
            <link rel='icon' type='image/x-icon' href='static/images/favicon.png'>
            <script src='static/slider.js'></script> 
            <script src='static/side-menu.js'></script> 
        </head>
        <body>
        <!--Sadržaj-->
";
}

function end_page(): void
{
    echo "
        </body>
        </html>
    ";
}