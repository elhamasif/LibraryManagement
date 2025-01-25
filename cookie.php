<?php 

    $cookie_name="book";
    $cookie_value= $_POST["book"];
    setcookie($cookie_name, $cookie_value, time()+ 20,"/");
    //header("location:http://localhost/myproject/cookie.php"); 

    if(!isset($_COOKIE[$cookie_name])) {
        echo"not set";

    }
    else {echo"set";}
    //$url = $_SERVER['PHP_SELF']; 
    //header("refresh:20; url = $url");
?>
