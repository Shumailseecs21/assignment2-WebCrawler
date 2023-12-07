<?php
    $conn=new mysqli("localhost","root","12345678","webassignment2");
        if ($conn->connect_error) { 
            die("Connection failed: ". $conn->connect_error);
        }else{
            echo "Connected successfully";
        }
?>