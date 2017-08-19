<?php

namespace controller;

class Organization extends Base 
{
	public function show() 
	{	
		$user_id = $_SESSION['signed_in_user_id'];

		try {
			$organization = new \model\Organization();
			$organizations = $organization->getAll();
			$userOrganization = $organization->memberOf($user_id);	

			$display = new \model\Display();
			$displays = array();
			
			if($userOrganization) {
				$admin = false;
				if(isset($userOrganization->admin_of)) {
					$admin = true;
				}

				$org_id = $admin ? $userOrganization->admin_of : $userOrganization->member_of;

				foreach($organizations as $org)
				{
					if($org->id == $org_id) {
						$userOrganization->id = $org->id;
						$userOrganization->name = $org->name;
					}
				}

				if($admin) {
					$displays = $display->getOrganizationsAll($org_id);	
				} else {

				}
			}
		}	
		catch(\Exception $e) {
			$this->respondWithViewError("organization", $e->getMessage());  
		}

		respondWithView("organization", array("organizations" => $organizations, "userOrganization" => $userOrganization, "displays" => $displays));
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