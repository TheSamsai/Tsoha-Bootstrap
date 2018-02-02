<?php
	class TaskController extends BaseController {
		public static function index() {
			$tasks = Task::all();
			Kint::dump($tasks);
			View::make('task/index.html', array('tasks' => $tasks));
		}
		
		public static function modify_view($id) {
			$task = Task::find($id);
			$classes = TaskClass::all();
			
			Kint::dump($task);
			Kint::dump($classes);
			View::make('task/modify_item.html', array('task' => $task, 'classes' => $classes));
		}
		
		public static function new_view() {
			$classes = TaskClass::all();
			
			View::make('task/new_item.html', array('classes' => $classes));
		}
		
		public static function store() {
			$params = $_POST;
			
			$task = new Task(array(
				'kuvaus' => $params['kuvaus'],
				'prioriteetti' => $params['prioriteetti']
			));
			
			$task->save();
			
			if ($params['luokka'] != 'none') {
				TaskClass::connectTaskToClass($task->id, $params['luokka']);
			}
			
			TaskController::index();
		}
		
		public static function update($id) {
			$params = $_POST;
			
			$task = Task::find($id);
			$task->kuvaus = $params['kuvaus'];
			$task->prioriteetti = $params['prioriteetti'];
			
			$task->update();
			
			TaskController::index();
		}
	}
?>
