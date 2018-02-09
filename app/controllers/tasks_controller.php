<?php
	class TaskController extends BaseController {
		public static function index() {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$tasks = Task::all($user->id);
			Kint::dump($tasks);
			View::make('task/index.html', array('tasks' => $tasks));
		}
		
		public static function modify_view($id) {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$task = Task::find($user->id, $id);
			$classes = TaskClass::all($user->id);
			
			Kint::dump($task);
			Kint::dump($classes);
			View::make('task/modify_item.html', array('task' => $task, 'classes' => $classes));
		}
		
		public static function new_view() {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$classes = TaskClass::all($user->id);
			
			View::make('task/new_item.html', array('classes' => $classes));
		}
		
		public static function store() {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$params = $_POST;
			
			$attrib = array(
				'kayttaja_id' => $user->id,
				'kuvaus' => $params['kuvaus'],
				'prioriteetti' => $params['prioriteetti']
			);
			
			$task = new Task($attrib);
			
			$err = $task->errors();
			
			if (count($err) == 0) {
				$task->save();
			
				if ($params['luokka'] != 'none') {
					TaskClass::connectTaskToClass($task->id, $params['luokka']);
				}
				
				Redirect::to('/');
			} else {
				// käsittele virheet
				$classes = TaskClass::all($user->id);
				
				View::make('task/new_item.html', array('classes' => $classes, 'errors' => $err, 'attributes' => $attrib));
			}
		}
		
		public static function update($id) {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$params = $_POST;
			
			$task = Task::find($user->id, $id);
			$task->kuvaus = $params['kuvaus'];
			$task->prioriteetti = $params['prioriteetti'];
			
			$err = $task->errors();
			
			if (count($err) == 0) {
				$task->update($user->id);
				Redirect::to('/');
			} else {
				$classes = TaskClass::all($user->id);
				
				View::make('task/modify_item.html', array('task' => $task, 'classes' => $classes, 'errors' => $err));
			}
		}
		
		public static function delete($id) {
			$user = self::get_user_logged_in();
			
			if (!$user) {
				Redirect::to('/login');
			}
			
			$task = Task::find($user->id, $id);
			
			$task->delete();
			
			Redirect::to('/', array('messages' => array('Tehtävä poistettu.')));
		}
	}
?>
