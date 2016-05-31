<?php
   include("db.php");
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $myusername=mysqli_real_escape_string($mysqli,$_POST['username']);
      $mypassword=mysqli_real_escape_string($mysqli,$_POST['password']);
      //la clave enviada por el usuario se hashea y se consulta a la base
      $passwordSecure=md5($mypassword);
      
      $sql="SELECT id FROM users WHERE username='$myusername' and passcode='$passwordSecure'";
      $result=mysqli_query($mysqli,$sql);
      $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count=mysqli_num_rows($result);


      //chequea si encuentra al usuario con esos datos
      if($count==1)
      {
         $response = $row['id'];
      }
      else
      {
         $response = "0";
      }
      $error= '{"response":"' . $response . '"}';
      echo $error;
   }
?>