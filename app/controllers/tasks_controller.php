<?php
	class TaskController extends BaseController {
		// Muistilistan päänäkymä
		public static function index() {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$tasks = Task::all($user->id);
			View::make('task/index.html', array('tasks' => $tasks));
		}
		
		// Näytä muistilistalta tiettyyn luokkaan kuuluvat tehtävät
		public static function index_by_class($class_id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$tasks = Task::all_with_class($user->id, $class_id);
			View::make('task/index.html', array('tasks' => $tasks, 'messages' => array('Näytetään luokkakohtaiset tehtävät.')));
		}
		
		// Muokkausnäkymä tehtävälle
		public static function modify_view($id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$task = Task::find($user->id, $id);
			$classes = TaskClass::all($user->id);
			
			View::make('task/modify_item.html', array('task' => $task, 'classes' => $classes));
		}
		
		// Uuden tehtävän luontinäkymä
		public static function new_view() {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$classes = TaskClass::all($user->id);
			
			View::make('task/new_item.html', array('classes' => $classes));
		}
		
		// Tallenna uusi tehtävä
		public static function store() {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$params = $_POST;
			
			$attrib = array(
				'kayttaja_id' => $user->id,
				'kuvaus' => $params['kuvaus'],
				'prioriteetti' => $params['prioriteetti']
			);
			
			$classes = array();
			
			if (isset($_POST['luokat'])) {
				$classes = $params['luokat'];
			}
			
			$task = new Task($attrib);
			
			$err = $task->errors();
			
			if (count($err) == 0) {
				$task->save();
			
				if (count($classes) > 0) {
					foreach ($classes as $class) {
						TaskClass::connectTaskToClass($task->id, $class);
					}
				}
				
				Redirect::to('/');
			} else {
				// käsittele virheet
				$classes = TaskClass::all($user->id);
				
				View::make('task/new_item.html', array('classes' => $classes, 'errors' => $err, 'attributes' => $attrib));
			}
		}
		
		// Lisää tehtälle luokka
		public static function add_class_to_task($task_id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$params = $_POST;
			
			$class = $params['luokka'];
			
			$task = Task::find($user->id, $task_id);
			
			if ($class != 'none') {
				TaskClass::connectTaskToClass($task->id, $class);
			}
				
			Redirect::to('/task/' . $task_id . '/edit');
		}
		
		// Päivitä tehtävä
		public static function update($id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$params = $_POST;
			
			$task = Task::find($user->id, $id);
			$task->kuvaus = $params['kuvaus'];
			$task->prioriteetti = $params['prioriteetti'];
			
			$err = $task->errors();
			
			if (count($err) == 0) {
				$task->update();
				Redirect::to('/');
			} else {
				$classes = TaskClass::all($user->id);
				
				View::make('task/modify_item.html', array('task' => $task, 'classes' => $classes, 'errors' => $err));
			}
		}
		
		// Poista tehtävä
		public static function delete($id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$task = Task::find($user->id, $id);
			
			$task->delete();
			
			Redirect::to('/', array('messages' => array('Tehtävä poistettu.')));
		}
	}
?>
