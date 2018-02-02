<?php
	class Task extends BaseModel {
		public $id, $kayttaja_id, $kuvaus, $prioriteetti, $luokat;
		
		public function __construct($attributes) {
			parent::__construct($attributes);
		}
		
		public static function all() {
			$query = DB::connection()->prepare("SELECT * FROM Tehtava;");
			$query->execute();
			
			$rows = $query->fetchAll();
			
			$tasks = array();
			
			foreach($rows as $row) {
				$classes = TaskClass::findForTask($row['id']);
				
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
		
		public static function find($id) {
			$query = DB::connection()->prepare("SELECT * FROM Tehtava WHERE id = :id LIMIT 1;");
			$query->execute(array('id' => $id));
			
			$row = $query->fetch();
			
			if ($row) {
				$classes = TaskClass::findForTask($row['id']);
				
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
			$query = DB::connection()->prepare("UPDATE Tehtava SET kuvaus = :kuvaus, prioriteetti = :prioriteetti WHERE id = :id;");
			$query->execute(array('kuvaus' => $this->kuvaus, 'prioriteetti' => $this->prioriteetti, 'id' => $this->id));
		}
		
		public function save() {
			$query = DB::connection()->prepare("INSERT INTO Tehtava (kayttaja_id, kuvaus, prioriteetti) VALUES (:kayttaja_id, :kuvaus, :prioriteetti) RETURNING id;");
			$query->execute(array('kayttaja_id' => $this->kayttaja_id, 'kuvaus' => $this->kuvaus, 'prioriteetti' => $this->prioriteetti));
			$row = $query->fetch();
			
			$this->id = $row['id'];
		}
	}
?>
