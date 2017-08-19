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
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully created an display", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}
};

?>