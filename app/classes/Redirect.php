<?php

class Redirect
{
   private $location;

   public static function to($location)
   {
      $self = new self();
      $self->location = $location;

      // Si las cabeceras ya fueron envíadas
      if (headers_sent()) {
         echo '<script type="text/javascript">';
         echo 'window.location.href="' . URL . $self->location . '";';
         echo '</script>';
         echo '<noscript>';
         echo '<meta http-equiv="refresh" content="0;url=' . URL . $self->location . '" />';
         echo '</noscript>';
         exit();
      }

      // Para cuando se pasa la url de una web externa
      if (strpos($self->location, 'http') !== false) {
         header('Location: ' . $self->location);
         exit();
      }

      // Pasar nombre del controlador y del método
      header('Location: ' . URL . $self->location);
      exit();
   }
}
