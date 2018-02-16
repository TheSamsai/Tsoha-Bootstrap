<?php
	class ClassController extends BaseController {
		public static function manage_view() {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$classes = TaskClass::all($user->id);
			
			Kint::dump($classes);
			View::make('class/manage_classes.html', array('classes' => $classes));
		}
		
		public static function store() {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$params = $_POST;
			
			$attrib = array(
				'kayttaja_id' => $user->id,
				'kuvaus' => $params['kuvaus']
			);
			
			$class = new TaskClass($attrib);
			
			$err = $class->errors();
			
			if (count($err) == 0) {
				$class->save();
				
				$classes = TaskClass::all($user->id);
				Redirect::to('/class/manage', array('messages' => array('Luokka lisätty.'), 'classes' => $classes));
			} else {
				// käsittele virheet
				$classes = TaskClass::all($user->id);
				
				View::make('class/manage_classes.html', array('classes' => $classes, 'errors' => $err, 'attributes' => $attrib));
			}
		}
		
		public static function delete($id) {
			self::check_logged_in();
			$user = self::get_user_logged_in();
			
			$class = TaskClass::find($user->id, $id);
			
			$class->delete();
			
			Redirect::to('/class/manage', array('messages' => array('Tehtävä poistettu.')));
		}
	}
?>
