<?php
$_output['view'] = '';
$_output['html_model'] = 'logout';
?>

<?php 


session_start();

$_SESSION = []; // očisti sve session varijable
session_destroy();

setcookie(session_name(), '', time() - 3600, '/');

header("Location: index.php");
exit;
?>