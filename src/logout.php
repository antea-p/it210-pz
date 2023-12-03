<?php
include_once('utils/session.php');
session_start();

log_out_session();
header('Location: index.php');
die();