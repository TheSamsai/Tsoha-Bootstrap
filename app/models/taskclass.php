<?php
	class TaskClass extends BaseModel {
		public $id, $kayttaja_id, $kuvaus;
		
		public function __construct($attributes) {
			parent::__construct($attributes);
		}
		
		public static function all($user_id) {
			$query = DB::connection()->prepare("SELECT * FROM Luokka WHERE kayttaja_id = :kayttaja_id");
			$query->execute(array('kayttaja_id' => $user_id));
			
			$rows = $query->fetchAll();
			
			$classes = array();
			
			foreach($rows as $row) {
				$classes[] = new TaskClass(array(
					'id' => $row['id'],
					'kayttaja_id' => $row['kayttaja_id'],
					'kuvaus' => $row['kuvaus']
				));
			}
			
			return $classes;
		}
		
		public static function find($user_id, $id) {
			$query = DB::connection()->prepare("SELECT * FROM Luokka WHERE id = :id AND kayttaja_id = :kayttaja_id LIMIT 1");
			$query->execute(array('id' => $id, 'kayttaja_id' => $user_id));
			
			$row = $query->fetch();
			
			if ($row) {
				$class = new TaskClass(array(
					'id' => $row['id'],
					'kayttaja_id' => $row['kayttaja_id'],
					'kuvaus' => $row['kuvaus']
				));
			
				return $class;
			} else {
				return null;
			}
		}
		
		public static function findForTask($task_id) {
			$query = DB::connection()->prepare("SELECT luokka_id FROM TehtavaLuokka WHERE tehtava_id = :id");
			$query->execute(array('id' => $task_id));
			
			$rows = $query->fetchAll();
			
			$classes = array();
			
			foreach($rows as $row) {
				$classes[] = TaskClass::find($row['luokka_id']);
			}
			
			return $classes;
		}
		
		public static function connectTaskToClass($task_id, $class_id) {
			$query = DB::connection()->prepare("INSERT INTO TehtavaLuokka (tehtava_id, luokka_id) VALUES (:tehtava_id, :luokka_id);");
			$query->execute(array('tehtava_id' => $task_id, 'luokka_id' => $class_id));
		}
	}
?>
