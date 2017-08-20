<?php

namespace controller;

class Display extends Base 
{
	public function show() 
	{	
		
	}

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
};

?>