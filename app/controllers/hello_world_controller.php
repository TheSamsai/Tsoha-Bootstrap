<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('home.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      $eka = Task::find(1);
      $kaikki = Task::all();
      
      Kint::dump($eka);
      Kint::dump($kaikki);
      
      View::make('helloworld.html');
      
    }
    
    public static function view_list() {
      View::make('suunnitelmat/list_view.html');
    }
    
    public static function modify_item() {
      View::make('suunnitelmat/modify_item.html');
    }
    
    public static function login() {
      View::make('suunnitelmat/login.html');
    }
    
    public static function register() {
      View::make('suunnitelmat/register.html');
    }
  }
