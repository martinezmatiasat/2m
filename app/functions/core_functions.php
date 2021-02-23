<?php

function to_object($array)
{
   return json_decode(json_encode($array));
}

function get_sitename()
{
   return '2M framework';
}

function get_fields($table)
{
   try {
      $fields = [];
      $conn = Db::connect();
      $query = $conn->prepare("DESCRIBE $table");
      $query->execute();
      $result = $query->rowCount() > 0 ? $query->fetchAll(PDO::FETCH_ASSOC) : false;
      foreach ($result as $res) {
         $fields[] = $res['Field'];
      }
      return $fields;
   } catch (Exception $e) {
      return $e->getMessage();
   }
}

function validate_array($table, $data = [])
{
   $fields = get_fields($table);
   foreach ($data as $key => $value) {
      $validated = false;
      foreach ($fields as $field_name) {
         if ($field_name == $key) $validated = true;
      }
      if (!$validated) return false;
   }
   return true;
}

function get_var_name($v)
{
   $trace = debug_backtrace();
   $v_line = file(__FILE__);
   $f_line = $v_line[$trace[0]['line'] - 1];
   preg_match("#\\$(\w+)#", $f_line, $match);
   return $match[1];
}

function getRealIpAddr(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ($ip == '::1') $ip = '127.0.0.1';
	return $ip;
}

function validate_loged_user(){
   if (empty($_COOKIE['session_id']) || empty($_COOKIE['admin_id']) || empty($_COOKIE['empresa_id'])) {
      Redirect::to('login/logout');
   } else {
      $ip = getRealIpAddr();
      $ses = Factory::exists('administrador_acceso', ['ip' => $ip, 'session_id' => $_COOKIE['session_id']]);
      if (!$ses) {
         Redirect::to('login/logout');
      }
   }
   return true;
}

function format_date($date) {
   return $date;
}

function format_price($currency, $price) {
   $res = false;
   if ($currency == 1) { $res = '$ '; } elseif ($currency == 2) { $res = 'U$S '; }
   if ($res) $res .= number_format($price, 2, ',', '.');
   return $res;
}

function random_string($length = 10) {
   $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $charactersLength = strlen($characters);
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
       $randomString .= $characters[rand(0, $charactersLength - 1)];
   }
   return $randomString;
}