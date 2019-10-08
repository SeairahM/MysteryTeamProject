<?php

   function sanitizeInput($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
    return $data;
    }
    
?>