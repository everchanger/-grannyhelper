<?php

namespace controller;

class home extends Base 
{
	
	public function show() 
	{	
		respondWithView("home", array());
	}

	public function updateSettings() 
	{
		$in_date  		= filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
		$title  		= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$description  	= filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
		$setAsAStandard	= filter_input(INPUT_POST, 'standard', FILTER_SANITIZE_STRING);

		$havePhoto = false;

		// Check if file uploads went OK
		if($_FILES['photo']['error'] && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) 
		{
			$this->respondWithError("Error with file upload, file not uploaded"); 
		} else {
			$havePhoto = true;

			// Store file on the server before we add references in the db to it.
			$target_dir 	= UPLOAD_PATH;
			$newFileName 	= uniqid();
			$targetFile 	= $target_dir . $newFileName;
			$fileSize 		= $_FILES['photo']['size'];

			move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
		}

		// Format a correct date
		$date = new \DateTime($in_date . ' 00:00');

		try {
			$event = new \model\Event();
			$planned = $event->set($date, $title, $description, $targetFile, $setAsAStandard);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		respondWithView("home", array());
	}

	public function getEvent()
	{
		$in_date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

		// Format a correct date
		$date = new \DateTime($in_date . ' 00:00');

		try {
			$event = new \model\Event();
			$plannedEvents = $event->get($date);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		echo json_encode($plannedEvents);
	}
};

?>