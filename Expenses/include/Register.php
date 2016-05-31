<?php
   include("db.php");
   if($_SERVER["REQUEST_METHOD"] == "POST")
   {
      $myusername=mysqli_real_escape_string($mysqli,$_POST['username']);
      $mypassword=mysqli_real_escape_string($mysqli,$_POST['passcode']);
      //la clave enviada por el usuario se hashea y se consulta a la base
      $passwordSecure=md5($mypassword);

      $existe= "SELECT * FROM users WHERE username='$myusername'";
      $result=mysqli_query($mysqli,$existe);
      $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count=mysqli_num_rows($result);

      if($count!=0) {
         $id_usuario= "0";
      }
      else {
         $sql="INSERT INTO users(username, passcode) VALUES ('$myusername', '$passwordSecure')";
         $resultado=mysqli_query($mysqli,$sql);

         $userCheck= "SELECT MAX(id) as id FROM users";
         $insertedResult=mysqli_query($mysqli,$userCheck);
         $usuario=mysqli_fetch_array($insertedResult,MYSQLI_ASSOC);

         $accountSQL= "INSERT INTO account(money,date,userId) VALUES(0 ,'".date('Y-m-d')."',". $usuario['id'].")";
         $accountInsert=mysqli_query($mysqli,$accountSQL);
         $response= "1";
         $id_usuario= $usuario['id'];
      }

      $error= '{"id_usuario":"'. $id_usuario .'"}';
      echo $error;
   }
?>