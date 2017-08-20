<?php

namespace controller;

class Display extends Base 
{
	public function create() 
	{
		$display_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$organization_id = filter_input(INPUT_POST, 'organization_id', FILTER_SANITIZE_STRING);

		try {
			$display = new \model\Display();
			$joined = $display->add($display_name, $organization_id);
		}
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully created an display", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function addUser()
	{
		
		$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING);
		$display_id = filter_input(INPUT_POST, 'display_id', FILTER_SANITIZE_STRING);

		try {
			$display = new \model\Display();
			$joined = $display->addUser($display_id, $user_id);
		}
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully assigned a user to a display", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function removeUser()
	{
		
		$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
		$display_id = filter_input(INPUT_GET, 'display_id', FILTER_SANITIZE_STRING);

		try {
			$display = new \model\Display();
			$joined = $display->removeUser($display_id, $user_id);
		}
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully removed a user from a display", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}	

	public function show()
	{
		$display_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
		$date = new \DateTime('today midnight');
	
		try {
			$event = new \model\Event();
			$plannedEvents = $event->get($date, $display_id);
			$currentEvent = $plannedEvents[0];
			foreach($plannedEvents as $plannedEvent) {
				if(!$plannedEvent->standard) {
					$currentEvent = $plannedEvent;
					break;
				}
			}

			$event_hash = $this->hashObject($currentEvent);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		respondWithView("display", array("date" => $date, "event" => $currentEvent, "day_period" => $this->getTimePeriod(), "event_hash" => $event_hash, "display_id" => $display_id), 200, false);
	}

	public function needsRefresh()
	{
		$hash = filter_input(INPUT_GET, 'event_hash', FILTER_SANITIZE_STRING);
		$display_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

		$date = new \DateTime('today midnight');
	
		try {
			$event = new \model\Event();
			$plannedEvents = $event->get($date, $display_id);
			$currentEvent = $plannedEvents[0];
			foreach($plannedEvents as $plannedEvent) {
				if(!$plannedEvent->standard) {
					$currentEvent = $plannedEvent;
					break;
				}
			}

			$event_hash = $this->hashObject($currentEvent);
		}
		catch(\Exception $e) {
			var_dump($e);die();
		}

		if($hash != $event_hash) {
			echo 'TRUE';
		}
	}

	private function getTimePeriod()
	{
		$current_time = new \DateTime();
		$period = "morgon";
		$current_hour = intval($current_time->format('H'))+1;

		if($current_hour >= 23 || ($current_hour >= 0 && $current_hour < 3)) {
			$period = "natt";
		} else if($current_hour >= 3 && $current_hour < 9) {
			$period = "morgon";
		} else if($current_hour >= 9 && $current_hour < 17) {
			$period = "dag";
		} else if($current_hour >= 17 && $current_hour < 23) {
			$period = "kväll";
		}

		return $period;
	}

	private function hashObject($object) {
		$text = $object->title . $object->description . $object->filename . $this->getTimePeriod();
		return hash('md5', $text);
	}
};

?>