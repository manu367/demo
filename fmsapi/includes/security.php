<?php
function checkuser($link,$loginId,$password){
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql="SELECT username,password FROM admin_users WHERE username='$loginId' LIMIT 1";
    $result=mysqli_query($link,$sql);
    if($result && mysqli_num_rows($result)==1){
        while ($row=mysqli_fetch_assoc($result)){
            if($row['password']==$password){
                return true;
            }
        }
    }
    return false;

}