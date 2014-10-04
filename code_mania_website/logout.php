<?php
    ob_start();
?>
<?php
    session_start();
    
    if (isset($_SESSION['username']) || isset($_SESSION['table'])) {
        setcookie('username', $_SESSION['username'], time()-645326);
        setcookie('table', $_SESSION['table'], time()-678687);
        setcookie('crypted', $_SESSION['crypted'], time()-476786);
    }
    
    session_destroy();
    
    header("Location: login.php?logout=1");
?>

<?php
    ob_end_flush();
?>
