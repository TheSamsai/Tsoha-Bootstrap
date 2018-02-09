<?php
	class UserController extends BaseController {
		public static function login() {
			View::make('login.html', array());
		}
		
		public static function register() {
			View::make('register.html', array());
		}
		
		public static function handle_login() {
			$params = $_POST;
			
			$user = User::authenticate($params['username'], $params['password']);
			
			if (!$user) {
				View::make('login.html', array('errors' => array('Väärä käyttäjätunnus tai salasana!'), 'attributes' => array('username' => $params['username'], 'password' => $params['password'])));
			} else {
				$_SESSION['user'] = $user->id;
				
				Redirect::to('/', array('messages' => array('Tervetuloa takaisin, ' . $user->username . '!')));
			}
		}
		
		public static function handle_register() {
			$params = $_POST;
			
			$attrib = array(
				'username' => $params['username'],
				'password' => $params['password']
			);
			
			$password_again = $params['password-repeat'];
			
			$user = new User($attrib);
			
			if ($user->password != $password_again) {
				View::make('register.html', array('errors' => array('Salasanat eivät täsmää!'), 'attributes' => $attrib));
			}
			
			$err = $user->errors();
			
			if (count($err) == 0) {
				$already_exists = User::find_by_name($user->username);
			
				if (!$already_exists) {
					$user->store_new_user();
					
					Redirect::to('/login', array('messages' => array('Käyttäjätilisi on nyt luotu.')));
				} else {
					View::make('register.html', array('errors' => array('Käyttäjänimi on jo käytössä!'), 'attributes' => $attrib));
				}
			} else {
				View::make('register.html', array('errors' => $err, 'attributes' => $attrib));
			}
		}
	}
?>
