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
			$members = array();
			
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
					$members = $organization->getMembers($org_id);	

					foreach($members as $member) {
						// Check what displays each member has access too
						$member->displays =  $display->getUsersAll($member->id);
					}
				} else {

				}
			}
		}	
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		respondWithView("organization", array("organizations" => $organizations, "userOrganization" => $userOrganization, "displays" => $displays, "members" => $members));
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
			$this->respondWithControllerError("organization", $e->getMessage());  
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
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Successfully joined an organization", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function addAdmin() 
	{
		$organization_id = filter_input(INPUT_GET, 'organization_id', FILTER_SANITIZE_STRING);
		$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
		
		try {
			$organization = new \model\Organization();
			$organization->setIsAdministrator($organization_id, $user_id, true);
		}
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Added an administrator", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}

	public function removeAdmin() {
		$organization_id = filter_input(INPUT_GET, 'organization_id', FILTER_SANITIZE_STRING);
		$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

		try {
			$organization = new \model\Organization();
			$organization->setIsAdministrator($organization_id, $user_id, false);
		}
		catch(\Exception $e) {
			$this->respondWithControllerError("organization", $e->getMessage());  
		}

		$this->userMessage("Removed an administrator", USER_MESSAGE_SUCCESS);

		$this->respondWithController("organization");
	}
};

?>