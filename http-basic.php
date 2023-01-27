<?php

$username = null;
$password = null;

if (isset($_SERVER['PHP_AUTH_USER'])) {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    if ('admin' === $username && '123' === $password) {

        return true;
    }

} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {

    if (str_starts_with(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic')) {
        list($username, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
    }
    if ('admin' === $username && '123' === $password) {

        return true;
    }
}

if (!is_null($username) && 'admin' !== $username) {

    return false;
}