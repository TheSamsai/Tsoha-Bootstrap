<?php
	class Task extends BaseModel {
		public $id, $kayttaja_id, $kuvaus, $prioriteetti, $luokat, $validators;
		
		public function __construct($attributes) {
			parent::__construct($attributes);
			$this->validators = array('validate_description', 'validate_priority');
		}
		
		public static function all($user_id) {
			$query = DB::connection()->prepare("SELECT * FROM Tehtava WHERE kayttaja_id = :kayttaja_id ORDER BY Tehtava.prioriteetti DESC;");
			$query->execute(array('kayttaja_id' => $user_id));
			
			$rows = $query->fetchAll();
			
			$tasks = array();
			
			foreach($rows as $row) {
				$classes = TaskClass::findForTask($user_id, $row['id']);
				
				$tasks[] = new Task(array(
					'id' => $row['id'],
					'kayttaja_id' => $row['kayttaja_id'],
					'kuvaus' => $row['kuvaus'],
					'prioriteetti' => $row['prioriteetti'],
					'luokat' => $classes
				));
			}
			
			return $tasks;
		}
		
		public static function find($user_id, $id) {
			$query = DB::connection()->prepare("SELECT * FROM Tehtava WHERE id = :id AND kayttaja_id = :kayttaja_id LIMIT 1;");
			$query->execute(array('id' => $id, 'kayttaja_id' => $user_id));
			
			$row = $query->fetch();
			
			if ($row) {
				$classes = TaskClass::findForTask($user_id, $row['id']);
				
				$task = new Task(array(
					'id' => $row['id'],
					'kayttaja_id' => $row['kayttaja_id'],
					'kuvaus' => $row['kuvaus'],
					'prioriteetti' => $row['prioriteetti'],
					'luokat' => $classes
				));
			
				return $task;
			} else {
				return null;
			}
		}
		
		public function update() {
			$query = DB::connection()->prepare("UPDATE Tehtava SET kuvaus = :kuvaus, prioriteetti = :prioriteetti WHERE id = :id AND kayttaja_id = :kayttaja_id;");
			$query->execute(array('kuvaus' => $this->kuvaus, 'prioriteetti' => $this->prioriteetti, 'id' => $this->id, 'kayttaja_id' => $this->kayttaja_id));
		}
		
		public function save() {
			$query = DB::connection()->prepare("INSERT INTO Tehtava (kayttaja_id, kuvaus, prioriteetti) VALUES (:kayttaja_id, :kuvaus, :prioriteetti) RETURNING id;");
			$query->execute(array('kayttaja_id' => $this->kayttaja_id, 'kuvaus' => $this->kuvaus, 'prioriteetti' => $this->prioriteetti));
			$row = $query->fetch();
			
			$this->id = $row['id'];
		}
		
		public function delete() {
			$query = DB::connection()->prepare("DELETE FROM TehtavaLuokka WHERE tehtava_id = :id");
			$query->execute(array('id' => $this->id));
			$query = DB::connection()->prepare("DELETE FROM Tehtava WHERE id = :id AND kayttaja_id = :kayttaja_id;");
			$query->execute(array('id' => $this->id, 'kayttaja_id' => $this->kayttaja_id));
		}
		
		public function validate_description() {
			$errors = array();
			
			if (!$this::validate_string_length($this->kuvaus, 1)) {
				$errors[] = 'Kuvaus ei saa olla tyhjä!';
			} 
			
			return $errors;
		}
		
		public function validate_priority() {
			$errors = array();
			
			if (!$this::validate_numeric($this->prioriteetti)) {
				$errors[] = 'Prioriteetin tulee olla luku!';
			} else if ($this->prioriteetti < 0) {
				$errors[] = 'Prioriteetti ei saa olla negatiivinen!';
			}
			
			return $errors;
		}
	}
?>
