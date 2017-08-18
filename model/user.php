<?php

namespace model;

class User
{
    public function add($email, $password_hash) 
    {
        if(!isset($email) || !isset($password_hash)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :pwd_hash)");
            
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pwd_hash", $password_hash);

            $stmt->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        return DB::pdo()->lastInsertId();
    }

    // This functions gets a user either from an id or an mail, if the id isn't an int we do email lookup.
    public function get($id) {
        if(!isset($id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = null;

            if(is_int($id)) 
            {
                // Select on id
                $stmt = DB::pdo()->prepare("SELECT id, email, password_hash FROM users WHERE id = :id");
                $stmt->bindParam(":id", $id);
            }
            else 
            {
                // Select on email
                $stmt = DB::pdo()->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
                $stmt->bindParam(":email", $id);
            }
            
            $stmt->execute();

            if ($stmt->rowCount() <= 0){
                throw new \Exception("No user with id: ".$id." found", ERROR_CODE_USER_NOT_FOUND);
            }

            return $stmt->fetch(\PDO::FETCH_OBJ);
        } 
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }
}