
<?php
session_start();
session_unset();
session_destroy();

setcookie("PHPSESSID", "", time() - 3600, "/"); 

echo json_encode(["success" => true]);
?>
