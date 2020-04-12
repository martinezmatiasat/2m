<?php
class Connection
{
   public $mysqli = null;

   public function __construct() {
      $this->mysqli = mysqli_init();
      $this->mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
      $this->mysqli->real_connect('localhost', 'mifadu', 'mifadu', 'mifadu');
      if ($this->mysqli->connect_errno) {
         die ("Falló la conexión a MySQL: (".$this->mysqli->connect_errno.") ".$this->mysqli->connect_error);
      }
      $this->mysqli->set_charset('utf8');
      return $this->mysqli;
   }

   public function __destruct() {
      $this->mysqli->close();
   }
}
