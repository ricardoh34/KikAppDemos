<?php
   include("db.php");
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $id= isset($_POST['id'])?$_POST['id']:0;
      $myusername=$_POST['username'];
      $mypassword=$_POST['password'];
      //la clave enviada por el usuario se hashea y se consulta a la base
      $passwordSecure=md5($mypassword);

      $sql= "UPDATE users SET username='$myusername', passcode='$passwordSecure' WHERE id='$id'";
      $result = $mysqli->query($sql) or die($mysqli->error.__LINE__);
      $result = $mysqli->affected_rows;

      //chequea si encuentra al usuario con esos datos
      if($result==1)
      {
         $response = "1";
      }
      else
      {
         $response = "0";
      }
      $error= '{"response":"' . $response . '"}';
      echo $error;
   }
?>