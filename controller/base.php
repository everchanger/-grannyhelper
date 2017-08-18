<?php

namespace controller;

class Base 
{

	public function __construct() 
	{	
	}
	
	protected function respondWithError($errorText, $errorCode=500)
	{
		respondWithStatus($errorText, $errorCode);
	}

	protected function respondWithViewError($view, $errorText)
	{
		$this->userMessage($errorText, USER_MESSAGE_ERROR);
		respondWithView($view, array());
	}

	protected function respond($view, $args) 
	{	
		respondWithView($view, $args);
	}
	
	protected function redirect($request) 
	{		
		header("Location: " . $request);
		exit;
	}
	
	protected function respondWithController($controller, $action = "show", $parameters = array()) 
	{		
		$query = '';
		if(count($parameters) > 0 ) 
		{
			$query .= "&" . http_build_query($parameters);
		}
		$request = "?controller=" . $controller . "&action=" . $action . $query;
		
		$this->redirect($request);
	}

	protected function userMessage($message, $message_type)
	{
		$user_message = new \stdClass();
		$user_message->type 	= $message_type;
		$user_message->message 	= $message;

		$_SESSION['message_to_user'] = $user_message;
	}
};

?>