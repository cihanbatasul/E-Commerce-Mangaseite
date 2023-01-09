<?php 
session_start();
// Unset Sessionvariablen

//unset($_SESSION['loggedin']);

//unset($_SESSION['user_id']);

//unset($_SESSION['cart']);

//unset($_SESSION['warenkorb_total_price']);
session_destroy();
// 
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: index.php');
}
exit;
?>