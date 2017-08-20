<?php

namespace controller;

class home extends Base 
{
	public function show() 
	{	
		$user_id = $_SESSION['signed_in_user_id'];
		
		try {
			$organization = new \model\Organization();
			$userOrganization = $organization->memberOf($user_id);	

			$display = new \model\Display();
			$displays = array();
			
			if($userOrganization) {
				$displays = $display->getUsersAll($user_id);
			}
		}	
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		respondWithView("home", array("displays" => $displays, "inOrganization" => $userOrganization));
	}

	public function show_unauth() 
	{	
		respondWithView("unauth_home", array());
	}

	public function show_register() 
	{	
		respondWithView("register", array());
	}

	public function updateSettings() 
	{
		$in_date  		= filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
		$id  			= filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_STRING);
		$photo_id  		= filter_input(INPUT_POST, 'photo_id', FILTER_SANITIZE_STRING);
		$displays_id  	= $_POST['display_ids'];
		$title  		= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$description  	= filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
		$setAsAStandard	= filter_input(INPUT_POST, 'standard', FILTER_SANITIZE_STRING);

		$havePhoto = false;
		$filename = null;

		if($title == null || $title == "") {
			$title = "nothing";
		}

		// Check if file uploads went OK
		if(array_key_exists('photo', $_FILES)) {
			if($_FILES['photo']['error'] && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) 
			{
				$this->respondWithError("Error with file upload, file not uploaded"); 
			} if($_FILES['photo']['error'] == UPLOAD_ERR_NO_FILE) {
				$filename = null;
			} else {
				$havePhoto = true;

				// Store file on the server before we add references in the db to it.
				$target_dir 	= UPLOAD_PATH;
				$newFileName 	= hash_file('sha256', $_FILES['photo']['tmp_name']);
				$filename 		= $target_dir . $newFileName;
				$fileSize 		= $_FILES['photo']['size'];

				move_uploaded_file($_FILES['photo']['tmp_name'], $filename);

				$this->rotateImageExif($filename);
			}
		}

		// Format a correct date
		$date = new \DateTime($in_date . ' 00:00');

		try {
			$event = new \model\Event();

			if(count($displays_id) > 1) {
				$id = 0;
			}

			foreach($displays_id as $display_id) {
				$planned = $event->set($id, $date, $title, $description, $photo_id, $filename, $display_id, $setAsAStandard);
			}
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		$this->userMessage("Händelse tillagt!", USER_MESSAGE_SUCCESS);

		$this->respondWithController("home", "show");
	}

	public function getEvent()
	{
		$in_date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
		$display_id = filter_input(INPUT_GET, 'display_id', FILTER_SANITIZE_STRING);

		// Format a correct date
		$date = new \DateTime($in_date . ' 00:00');

		try {
			$event = new \model\Event();
			$plannedEvents = $event->get($date, $display_id);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		echo json_encode($plannedEvents);
	}

	public function deleteEvent()
	{
		$id = filter_input(INPUT_GET, 'event_id', FILTER_SANITIZE_STRING);

		try {
			$event = new \model\Event();
			$plannedEvents = $event->delete($id);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		$this->userMessage("Händelse borttaget!", USER_MESSAGE_SUCCESS);

		$this->respondWithController("home", "show");
	}

	private function rotateImageExif($filename)
	{
		$exif = exif_read_data($filename);
		if (!empty($exif['Orientation'])) {
			$imageResource = imagecreatefromjpeg($filename); // provided that the image is jpeg. Use relevant function otherwise
			switch ($exif['Orientation']) {
				case 3:
				$image = imagerotate($imageResource, 180, 0);
				break;
				case 6:
				$image = imagerotate($imageResource, -90, 0);
				break;
				case 8:
				$image = imagerotate($imageResource, 90, 0);
				break;
				default:
				$image = $imageResource;
			} 
		}

		@imagejpeg($image, $filename, 90);

		@imagedestroy($imageResource);
		@imagedestroy($image);
	}
};

?>