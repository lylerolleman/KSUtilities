<?php
    session_start();
    if (!isset($_SESSION[$_GET["key"]])) {
        header('HTTP/1.1 204 No Content');
    } else {
        echo $_SESSION[$_GET["key"]];
    }
    
?>