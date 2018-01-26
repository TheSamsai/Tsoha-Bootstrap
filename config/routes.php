<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/lista', function() {
    HelloWorldController::view_list();
  });
  
  $routes->get('/muokkaa', function() {
    HelloWorldController::modify_item();
  });
  
  $routes->get('/login', function() {
    HelloWorldController::login();
  });
  
  $routes->get('/register', function() {
    HelloWorldController::register();
  });
