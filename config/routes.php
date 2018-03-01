<?php

  $routes->get('/', function() {
    TaskController::index();
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
    UserController::login();
  });
  
  $routes->post('/login', function() {
    UserController::handle_login();
  });
  
  $routes->get('/register', function() {
    UserController::register();
  });
  
  $routes->post('/register', function() {
    UserController::handle_register();
  });
  
  $routes->post('/logout', function() {
    UserController::logout();
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
  
  $routes->get('/task/:id/remove', function($id) {
    TaskController::delete($id);
  });
  
  $routes->get('/task/:id/remove_class/:class_id', function($id, $class_id) {
    TaskController::modify_view($id);
  });
  
  $routes->post('/task/:id/add_class', function($id) {
    TaskController::add_class_to_task($id);
  });
  
  $routes->post('/task/:id/edit', function($id) {
    TaskController::update($id);
  });
  
  $routes->get('/class/:id/remove', function($id) {
    ClassController::delete($id);
  });
  
  $routes->get('/class/:id/edit', function($id) {
    ClassController::modify_view($id);
  });
  
  $routes->post('/class/:id/edit', function($id) {
    ClassController::edit($id);
  });
  
  $routes->get('/class/manage', function() {
    ClassController::manage_view();
  });
  
  $routes->post('/class/new', function() {
    ClassController::store();
  });
