<?php

namespace model;

class Organization
{
    public function add($name, $user_id) 
    {
        if(!isset($name)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO organizations (name) VALUES (:name)");
            
            $stmt->bindParam(":name", $name);

            $stmt->execute();

            $organizationID =  DB::pdo()->lastInsertId();

            $stmt2 = DB::pdo()->prepare("INSERT INTO organizations_admins (organization_id, user_id) VALUES (:organization_id, :user_id)");
            
            $stmt2->bindParam(":organization_id", $organizationID);
            $stmt2->bindParam(":user_id", $user_id);

            $stmt2->execute();
            
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        return $organizationID;
    }

    // This functions gets a user either from an id or an mail, if the id isn't an int we do email lookup.
    public function get($id) 
    {
        if(!isset($id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            // Select on id
            $stmt = DB::pdo()->prepare("SELECT id, name FROM organizations WHERE id = :id");
            $stmt->bindParam(":id", $id);
            
            $stmt->execute();

            if ($stmt->rowCount() <= 0){
                return array();
            }

            return $stmt->fetch(\PDO::FETCH_OBJ);
        } 
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }

    public function getAll() 
    {
        try 
        {
            $stmt = DB::pdo()->prepare("SELECT id, name FROM organizations");
            $stmt->execute();

            if ($stmt->rowCount() <= 0){
                return array();
            }

            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } 
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }

    public function getUsersOrganization($user_id) 
    {
        // needs to join on both the organizations_users and organizations_administrators tables.
    }
}