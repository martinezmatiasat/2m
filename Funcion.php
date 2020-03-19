<?php
class Funcion
{
   //-----------------------------REGISTRACIÓN-----------------------------------
   /*Incluir en el front del formulario de registración
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
   }*/
   //----------------------------------------------------------------------------


   /*-------------------------- LEER EN BD --------------------------*/

   //Busca en una columna un valor y devuelve T/F si encuentra por lo menos un registro - Ok
   public static function exists($tabla, $columna, $valor)
   {
      global $con;
      try {
         $stmt = $con->prepare("SELECT COUNT($columna) FROM $tabla WHERE $columna = :valor");
         $stmt->bindValue(':valor', $valor);
         $stmt->execute();
         $row = $stmt->fetchColumn();
         $stmt->closeCursor();
         if ($row > 0) {return true;} else {return false;}
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en varias columnas distintos valores y devuelve T/F si encuentra por lo menos un registro - Ok
   public static function existsByArray($tabla, $condiciones = array())
   {
      global $con;
      try {
         $consulta = "SELECT COUNT(*) FROM $tabla WHERE ";
         $consulta .= implode(' AND ', $condiciones);
         $stmt = $con->prepare($consulta);
         $stmt->execute();
         $row = $stmt->fetchColumn();
         $stmt->closeCursor();
         if ($row > 0) {return true;} else {return false;}
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en una columna un valor, devuelve registro encontrado - Ok
   public static function getRow($tabla, $columna, $valor)
   {
      global $con;
      try {
         $stmt = $con->prepare("SELECT * FROM $tabla WHERE $columna = :valor");
         $stmt->bindValue(':valor', $valor);
         $stmt->execute();
         $info = $stmt->fetch(PDO::FETCH_ASSOC);
         $stmt->closeCursor();
         return $info;
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en varias columnas distintos valores, devuelve registro encontrado - Ok
   public static function getRowByArray($tabla, $condiciones = array())
   {
      global $con;
      try {
         $consulta = "SELECT * FROM $tabla WHERE ";
         $consulta .= implode(' AND ', $condiciones);
         $stmt = $con->prepare($consulta);
         $stmt->execute();
         $info = $stmt->fetch(PDO::FETCH_ASSOC);
         $stmt->closeCursor();
         return $info;
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en una columna un valor, devuelve registro encontrado - Ok
   public static function getElement($tabla, $columna, $valor, $clase)
   {
      global $con;
      try {
         $stmt = $con->prepare("SELECT * FROM $tabla WHERE $columna = :valor");
         $stmt->bindValue(':valor', $valor);
         $stmt->execute();
         $info = $stmt->fetch(PDO::FETCH_ASSOC);
         $stmt->closeCursor();
         return new $clase($info);
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en varias columnas distintos valores, devuelve registro encontrado - Ok
   public static function getElementByArray($tabla, $condiciones = array(), $clase)
   {
      global $con;
      try {
         $consulta = "SELECT * FROM $tabla WHERE ";
         $consulta .= implode(' AND ', $condiciones);
         $stmt = $con->prepare($consulta);
         $stmt->execute();
         $info = $stmt->fetch(PDO::FETCH_ASSOC);
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return new $clase($info);
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en una columna un valor, devuelve array de todos los registros encontrados y su cantidad - Ok
   public static function getList($tabla, $columna, $valor)
   {
      global $con;
      $info = array();
      try {
         $stmt = $con->prepare("SELECT * FROM $tabla WHERE $columna = :valor");
         $stmt->bindValue(':valor', $valor);
         $stmt->execute();
         while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) $info[] = $registro;
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return array('info' => $info, 'numFilas' => $numFilas);
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en varias columnas distintos valores, devuelve array de todos los registros encontrados y su cantidad - Ok
   public static function getListByArray($tabla, $condiciones = array(), $clase)
   {
      global $con;
      $info = array();
      try {
         $consulta = "SELECT * FROM $tabla WHERE ";
         $consulta .= implode(' AND ', $condiciones);
         $stmt = $con->prepare($consulta);
         $stmt->execute();
         while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) $info[] = $registro;
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return array('info' => $info, 'numFilas' => $numFilas);
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en una columna un valor, devuelve objetos con los registros encontrados y su cantidad - Ok
   public static function getElements($tabla, $columna, $valor, $clase)
   {
      global $con;
      $info = array();
      try {
         $stmt = $con->prepare("SELECT * FROM $tabla WHERE $columna = :valor");
         $stmt->bindValue(':valor', $valor);
         $stmt->execute();
         while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) $info[] = new $clase($registro);
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return array('info' => $info, 'numFilas' => $numFilas);
      } catch (PDOException $e) {
         return false;
      }
   }

   //Busca en varias columnas distintos valores, devuelve objetos con los registros encontrados y su cantidad - Ok
   public static function getElementsByArray($tabla, $condiciones = array(), $clase)
   {
      global $con;
      $info = array();
      try {
         $consulta = "SELECT * FROM $tabla WHERE ";
         $consulta .= implode(' AND ', $condiciones);
         $stmt = $con->prepare($consulta);
         $stmt->execute();
         while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) $info[] = new $clase($registro);
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return array('info' => $info, 'numFilas' => $numFilas);
      } catch (PDOException $e) {
         return false;
      }
   }

   // Ok
   public static function login($usuario, $clave)
   {
      global $con;
      if (Funcion::exists('tabla', 'nombreUsuario', $usuario)) {
         $usu = Funcion::getElement('tabla', 'nombreUsuario', $usuario, 'Clase'));
         if ($usu['clave'] == $clave) {
            session_start();
            $_SESSION['nombreUsuario'] = $usu['nombreUsuario'];
            return $usu;
         } else return false;
      } elseif (Funcion::exists('tabla2', 'nombreUsuario', $usuario)) {
         $usu = Funcion::getElement('tabla2', 'nombreUsuario', $usuario, 'Clase2');
         if ($usu['clave'] == $clave) {
            session_start();
            $_SESSION['nombreUsuario'] = $usu['nombreUsuario'];
            return $usu;
         } else return false;
      } else return false;
   }

   //Ok
   public static function login2($usuario, $clave)
   {
      global $con;
      if (Funcion::existsByArray('tabla', array("nombreUsuario = '$usuario'", "clave = '$clave'"))) {
         session_start();
         $_SESSION['usuario'] = Funcion::getRow('tabla', 'nombreUsuario', $usuario);
         return true;
      } elseif (Funcion::existsByArray('tabla2', array("nombreUsuario = '$usuario'", "clave = '$clave'"))) {
         session_start();
         $_SESSION['usuario'] = Funcion::getRow('tabla2', 'nombreUsuario', $usuario);
         return true;
      } else return false;
   }

   // Ok
   public static function getFields($tabla)
   {
      global $con;
      $info = array();
      try {
         $stmt = $con->prepare("DESCRIBE $tabla");
         $stmt->execute();
         while ($registro = $stmt->fetch(PDO::FETCH_ASSOC)) $info[] = $registro['Field'];
         $numFilas = $stmt->rowCount();
         $stmt->closeCursor();
         return array('info' => $info, 'numFilas' => $numFilas);
      } catch (PDOException $e) {
         return false;
      }
   }

   /*-------------------------- ESCRIBIR EN BD --------------------------*/

   public static function insertArray($tabla, $datos = array())
   {
      global $con;
      $columnas = Funcion::getFields($tabla);
      if (isset($columnas) && $columnas != ''){
         unset($columnas['info'][0]);
         foreach ($datos as $key => $value) {
            $esta = false;
            foreach ($columnas['info'] as $c) {
               if ($c == $key) $esta = true;
            }
            if ($esta) {
               $cols[] = $key;
               $vals[] = "'".$value."'";
            }
         }
         $cols = implode(', ', $cols);
         $vals = implode(', ', $vals);
      }
      try {
         $stmt = $con->prepare("INSERT INTO $tabla ($cols) VALUES ($vals)");
         $stmt->execute();
         $stmt->closeCursor();
         return true;
      } catch (PDOException $exception) {
         return false;
      }
   }

   // update
   // updateElement

   public static function updateTpComp(TrabajoComponente $tp){
      global $con;
      try {
         $stmt = $con->prepare('UPDATE trabajo_componente SET trabajo_componente_id = :trabajo_componente_id, trabajo_id = :trabajo_id, descripcion = :descripcion, tipo = :tipo');
         $stmt->bindValue(':trabajo_componente_id', $tp->getTrabajo_componente_id());
         $stmt->bindValue(':trabajo_id', $tp->getTrabajo_id());
         $stmt->bindValue(':descripcion', $tp->getDescripcion());
         $stmt->bindValue(':tipo', $tp->getTipo());
         $stmt->execute();
         $stmt->closeCursor();
         return true;
      } catch (PDOException $e) {
         return false;
      }
   }

   // register

   /*-------------------------- BORRAR EN BD --------------------------*/









}

?>
