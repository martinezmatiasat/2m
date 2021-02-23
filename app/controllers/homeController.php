<?php

class homeController
{
   public function index()
   {
      if (!isset($_SESSION['user'])) Redirect::to('login');
      $data = ['title' => 'Home', 'bg' => 'dark'];
      View::render('home', $data);
   }
}
