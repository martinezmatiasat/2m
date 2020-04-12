<?php
require_once 'Connection.php';
require_once 'Estudiante.php';

class Model extends Connection
{
   public static function exists($table, $field, $value, $field2 = null, $value2 = null)
   {
      $conn = new Connection;
      $query = "SELECT $field FROM $table WHERE $field = '";
      $query .= $conn->mysqli->real_escape_string($value);
      $query .= "'";
      if (isset($field2) && $field2!='' && isset($value2) && $value2!='') {
         $query .= " AND $field2 = '";
         $query .= $conn->mysqli->real_escape_string($value2);
         $query .= "'";
      }
      try {
         $stmt = $conn->mysqli->prepare($query);
         $stmt->execute();
         $row = $stmt->fetch();
         $stmt->close();
         if ($row > 0) { return true; } else { return false; }
      } catch (Exception $e){
			return false;
      }
   }

   public static function getRow($table, $conditions = array())
   {
      $conn = new Connection;
      $query = "SELECT * FROM $table WHERE ";
      $query .= implode(' AND ', $conditions);
      try {
         $result = $conn->mysqli->query($query);
         $row = $result->fetch_assoc();
         $result->free();
         return $row;
      } catch (Exception $e){
			return $e->getMessage();
      }
   }

   public static function getList($table, $field, $conditions = null)
   {
      $info = array();
      $conn = new Connection;
      $query = "SELECT $field FROM $table";
      if (isset($conditions) && $conditions != '') {
         $query .= " WHERE ";
         $query .= implode(' AND ', $conditions);
      }
      try {
         $result = $conn->mysqli->query($query);
         while ($row = $result->fetch_assoc()) $info[] = $row;
         $result->free();
         return $info;
      } catch (Exception $e){
			return $e->getMessage();
      }
   }

   public static function getObjects($table, $class, $conditions = null)
   {
      $info = array();
      $conn = new Connection;
      $query = "SELECT * FROM $table";
      if (isset($conditions)) {
         $query .= " WHERE ";
         $query .= implode(' AND ', $conditions);
      }
      try {
         $result = $conn->mysqli->query($query);
         while ($row = $result->fetch_assoc()) $info[] = new $class($row);
         $result->free();
         return $info;
      } catch (Exception $e){
         return $e->getMessage();
      }
   }

   public static function getFields($table)
   {
      $info = array();
      $conn = new Connection;
      try {
         $result = $conn->mysqli->query("DESCRIBE $table");
         while ($row = $result->fetch_assoc()) $info[] = $row['Field'];
         $result->free();
         return $info;
      } catch (Exception $e){
         return $e->getMessage();
      }
   }

   public static function insertArray($table, $data = array())
   {
      $conn = new Connection;
      $columns = self::getFields($table);
      $cols = null;
      $vals = null;
      if (isset($columns) && $columns !='') {
         unset($columns[0]);
         foreach ($data as $key => $value) {
            $founded = false;
            foreach ($columns as $c) {
               if ($c == $key) $founded = true;
            }
            if ($founded) {
               $cols[] = $key;
               $vals[] = $value;
            }
         }
         $cols = implode(', ', $cols);
      }
      try {
         $query = "INSERT INTO $table ($cols) VALUES ";
         foreach ($vals as $key => $value){
            if ($key == 0) {
               $query .= "('".$conn->mysqli->real_escape_string($value)."'";
            } elseif ($key == count($vals)-1) {
               $query .= ", '".$conn->mysqli->real_escape_string($value)."')";
            } else {
               $query .= ", '".$conn->mysqli->real_escape_string($value)."'";
            }
         }
         $result = $conn->mysqli->query($query);
         return $result;
      } catch (Exception $e) {
         return $e->getMessage();
      }
   }

   public static function insertObject($table, $object)
   {
      foreach ($object as $key => $value) {
         $data[$key] = $value;
      }
      $result = self::insertArray($table, $data);
      return $result;
   }

   public static function updateArray($table, $data = array(), $conditions = null)
   {
      $conn = new Connection;
      $columns = self::getFields($table);
      $settings = array();
      if (isset($columns) && $columns !='') {
         unset($columns[0]);
         foreach ($data as $key => $value) {
            $founded = false;
            foreach ($columns as $c) {
               if ($c == $key) $founded = true;
            }
            if ($founded) {
               $settings[] = "$key = '".$conn->mysqli->real_escape_string($value)."'";
            }
         }
         $query = "UPDATE $table SET ";
         $query .= implode(', ', $settings);
      }
      if (isset($conditions) && $conditions != '') {
         $query .= " WHERE ";
         $query .= implode(' AND ', $conditions);
      }
      try {
         $result = $conn->mysqli->query($query);
         return $result;
      } catch (Exception $e) {
         return $e->getMessage();
      }
   }

   public static function updateObject($table, $object, $conditions = null)
   {
      foreach ($object as $key => $value) {
         $data[$key] = $value;
      }
      $result = self::updateArray($table, $data, $conditions);
      return $result;
   }

   public static function delete($table, $conditions)
   {
      $conn = new Connection;
      $query = "DELETE FROM $table";
      if (isset($conditions) && $conditions != '') {
         $query .= " WHERE ";
         $query .= implode(' AND ', $conditions);
      }
      try {
         $result = $conn->mysqli->query($query);
         return $result;
      } catch (Exception $e) {
         return $e->getMessage();
      }
   }

   public static function generateSession($userId)
   {
      if (isset($_SESSION)) session_destroy();
      $sessionOptions = array('use_only_cookies' => 1,'auto_start' => 1,'read_and_close' => true);
      session_start($sessionOptions);
      $_SESSION['userId'] = $userId;
   }

   public static function login($user, $pass)
   {
      if (self::exists('table', array("userName = '$user'", "pass = '$pass'"))) {
         $u = self::getObjects('table', 'Class', array("userName = '$user'", "pass = '$pass'"));
         self::generateSession($u[0]->id);
         header('Location: ');
         exit();
      } elseif (self::exists('table2', array("userName = '$user'", "pass = '$pass'"))) {
         $u = self::getObjects('table2', 'Class2', array("userName = '$user'", "pass = '$pass'"));
         self::generateSession($u[0]->id);
         header('Location: ');
         exit();
      } else return false;
   }
}
