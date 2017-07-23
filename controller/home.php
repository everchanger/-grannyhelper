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
		$id  			= filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_STRING);
		$photo_id  		= filter_input(INPUT_POST, 'photo_id', FILTER_SANITIZE_STRING);
		$title  		= filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
		$description  	= filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
		$setAsAStandard	= filter_input(INPUT_POST, 'standard', FILTER_SANITIZE_STRING);

		$havePhoto = false;
		$filename = null;

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
				$newFileName 	= uniqid();
				$filename 		= $target_dir . $newFileName;
				$fileSize 		= $_FILES['photo']['size'];

				move_uploaded_file($_FILES['photo']['tmp_name'], $filename);
			}
		}

		// Format a correct date
		$date = new \DateTime($in_date . ' 00:00');

		try {
			$event = new \model\Event();
			$planned = $event->set($id, $date, $title, $description, $photo_id, $filename, $setAsAStandard);
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

	public function display()
	{
		$date = new \DateTime('today midnight');
		//$date->modify('yesterday');

		try {
			$event = new \model\Event();
			$plannedEvents = $event->get($date);
			$currentEvent = $plannedEvents[0];
			foreach($plannedEvents as $plannedEvent) {
				if(!$plannedEvent->standard) {
					$currentEvent = $plannedEvent;
					break;
				}
			}
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		respondWithView("display", array("date" => $date, "event" => $currentEvent));
	}
};

?>