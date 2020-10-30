<?php
session_start();
if(isset($_SESSION['username'])){
    $pagetitle = "dashboard";
    include "init.php";

        echo "Welcome";

    include $tpl."footer.php";

}else{
    header('Location: index.php');
    exit();
}


?>