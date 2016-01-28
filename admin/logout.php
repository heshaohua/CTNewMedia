<?php
/**
 * logout page
 */

require_once 'config.inc.php';

//log out
$_SESSION['admin'] = '';

header("location:login.php");