<?php
	class User extends BaseModel {
		public $id, $username, $password, $validators;
		
		public function __construct($attributes) {
			parent::__construct($attributes);
			$this->validators = array('validate_username', 'validate_password');
		}
		
		public static function authenticate($username, $password) {
			$query = DB::connection()->prepare("SELECT * FROM Kayttaja 
				WHERE username = :username AND password = :password LIMIT 1");
			$query->execute(array('username' => $username, 'password' => $password));
			
			$row = $query->fetch();
			
			if ($row) {
				$user = new User(array(
					'id' => $row['id'],
					'username' => $row['username'],
					'password' => $row['password']
				));
			
				return $user;
			} else {
				return null;
			}
		}
		
		public static function find($user_id) {
			$query = DB::connection()->prepare("SELECT * FROM Kayttaja 
				WHERE id = :id LIMIT 1");
			$query->execute(array('id' => $user_id));
			
			$row = $query->fetch();
			
			if ($row) {
				$user = new User(array(
					'id' => $user_id,
					'username' => $row['username'],
					'password' => $row['password']
				));
				
				return $user;
			} else {
				return null;
			}
		}
		
		public static function find_by_name($username) {
			$query = DB::connection()->prepare("SELECT * FROM Kayttaja 
				WHERE username = :username LIMIT 1");
			$query->execute(array('username' => $username));
			
			$row = $query->fetch();
			
			if ($row) {
				$user = new User(array(
					'id' => $row['id'],
					'username' => $row['username'],
					'password' => $row['password']
				));
				
				return $user;
			} else {
				return null;
			}
		}
		
		public function store_new_user() {
			$query = DB::connection()->prepare("INSERT INTO Kayttaja (username, password) 
				VALUES (:username, :password) RETURNING id");
			$query->execute(array('username' => $this->username, 'password' => $this->password));
			
			$row = $query->fetch();
			$this->id = $row['id'];
		}
		
		public function validate_username() {
			$errors = array();
			
			if (!$this::validate_string_length($this->username, 1)) {
				$errors[] = 'Käyttäjänimi ei saa olla tyhjä!';
			} 
			
			return $errors;
		}
		
		public function validate_password() {
			$errors = array();
			
			if (!$this::validate_string_length($this->username, 1)) {
				$errors[] = 'Salasana ei saa olla tyhjä!';
			} 
			
			return $errors;
		}
	}
?>
