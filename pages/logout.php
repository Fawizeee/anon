<?php


// Get all cookies
$cookies = $_COOKIE;

// Loop through each cookie and delete it
foreach ($cookies as $cookie_name => $value) {
    setcookie($cookie_name, '', time() - (86400 * 30), "/");
}
unset($_SESSION);
header("location:/anon/login");
