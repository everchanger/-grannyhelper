<?php

namespace controller;

class Organization extends Base 
{
	
	public function show() 
	{	
		$user_id = $_SESSION['signed_in_user_id'];
		$isAdministrator = false;
		$inOrganization = false;

		try {
			$organization = new \model\Organization();
			$organizations = $organization->getAll();
		}
		catch(\Exception $e) {
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		respondWithView("organization", array("organizations" => $organizations, "isAdministrator" => $isAdministrator, "inOrganization" => $inOrganization));
	}

	public function create() 
	{
		$organization_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
		$user_id = $_SESSION['signed_in_user_id'];

		try {
			$organization = new \model\Organization();
			$joined = $organization->add($organization_name, $user_id);
		}
		catch(\Exception $e) {
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully created an organization", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function join() 
	{	
		$organization_id = filter_input(INPUT_POST, 'organization_id', FILTER_SANITIZE_STRING);
		$user_id = $_SESSION['signed_in_user_id'];

		try {
			$organization = new \model\Organization();
			$joined = $organization->join($organization_id, $user_id);
		}
		catch(\Exception $e) {
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully joined an organization", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function setAdministrator() 
	{
		$organization_id = filter_input(INPUT_POST, 'organization_id', FILTER_SANITIZE_STRING);
		$user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_STRING);
		$isAdministrator = false;

		if(array_key_exists('is_administrator')) {
			$isAdministrator = true;
		}
		
		try {
			$organization = new \model\Organization();
			$joined = $organization->setIsAdministrator($organization_id, $user_id, $isAdministrator);
		}
		catch(\Exception $e) {
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		$this->userMessage("Administration settings updated", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}
};

?>