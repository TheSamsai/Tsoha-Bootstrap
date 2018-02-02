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
  
  $routes->get('/task', function() {
    TaskController::index();
  });
  
  $routes->get('/task/new', function() {
    TaskController::new_view();
  });
  
  $routes->post('/task/new', function() {
    TaskController::store();
  });
  
  $routes->get('/task/:id/edit', function($id) {
    TaskController::modify_view($id);
  });
  
  $routes->get('/task/:id/remove_class/:class_id', function($id, $class_id) {
    TaskController::modify_view($id);
  });
  
  $routes->post('/task/:id/edit', function($id) {
    TaskController::update($id);
  });
