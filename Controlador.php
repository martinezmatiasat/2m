<?php
require_once 'autoload.php';

$mensaje = '';
if (isset($_POST['email']) && isset($_POST['clave'])) {
   if (!Estudiante::searchByData('mail', $_POST['email']) && !Jtp::searchByData('email', $_POST['email'])) {
      $usuario = new Estudiante($_POST);
      if (Estudiante::saveUser($usuario)) {
         session_start();
         $_SESSION['email']=$_POST['email'];
         //setcookie("email", $_SESSION['email'], time() + 60 * 60 * 24 * 30);
         header('Location: EST-perfil.php');
      } else $mensaje = 'Error. No se pudo registrar usuario';
   } else $mensaje = 'Ya hay un usuario registrado con su email';
}

?>
