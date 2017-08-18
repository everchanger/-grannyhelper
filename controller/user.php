<?php

namespace controller;

class User extends Base
{
    public function register() 
    {
        $email      = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password1  = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
        $password2  = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
        
        if(!strlen($email)) 
        {
            $this->respondWithViewError("register", "Please enter a valid email address");
        }

        if((!strlen($password1) || !strlen($password2)) || $password1 != $password2) 
        {
            $this->respondWithViewError("register", "Passwords didn't match, please make sure you written the same password in both the password fields.");
        }

        $password_hash = hash_password($password1);
        $user = new \model\User();

        try 
        {
            $user_id = $user->add($email, $password_hash);
        } 
        catch(\Exception $e) 
        {
            $errorMsg = "Database error, please try again later";
            switch(intval($e->getCode())) {
                case 23000:
                    $errorMsg = "This email is already in use, please enter another adress.";
                break;
                default:
                break;
            }
            $this->respondWithViewError("register", $errorMsg);            
        }

        $_SESSION['signed_in_user_id'] = intval($user_id);
        $_SESSION['signed_in_user_email'] = $email;

        $this->userMessage("Welcome ".$email, USER_MESSAGE_SUCCESS);

        $this->respondWithController("home");
    }

    public function login() 
	{
		$email 		= filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password  	= filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

		if(!strlen($email) || !strlen($password)) {
            $this->respondWithViewError("unauth_home", "Please enter a valid email address and a password");
        }

		$user = new \model\user();

		try 
		{
			$current_user = $user->get($email);
			$match = validate_password($current_user->password_hash, $password);
            if(!$match) {
                $this->respondWithViewError("unauth_home", "Wrong password");
            }

            $_SESSION['signed_in_user_id'] = intval($current_user->id);
            $_SESSION['signed_in_user_email'] = $email;
		} 
		catch(\Exception $e) 
		{
			$errorMsg = "Database error, please try again later";
            switch(intval($e->getCode())) {
                case ERROR_CODE_WRONG_PASSWORD:
                case ERROR_CODE_USER_NOT_FOUND:
                    $errorMsg = $e->getMessage();
                break;
                default:
                    $errorMsg .= " " . $e->getCode();
                break;
            }

            $this->respondWithViewError("unauth_home", $errorMsg);
		}

        $this->userMessage("Welcome back ".$email, USER_MESSAGE_SUCCESS);

		$this->respondWithController("home");
	}

    public function logout() 
    {
         unset($_SESSION['signed_in_user_id']);
         unset($_SESSION['signed_in_user_email']);

         $this->respondWithController("home");
    }
}