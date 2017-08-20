<?php

namespace model;

class Display
{
    public function add($name, $organization_id) 
    {
        if(!isset($name)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO displays (name) VALUES (:name)");
            
            $stmt->bindParam(":name", $name);

            $stmt->execute();

            $displayID =  DB::pdo()->lastInsertId(); 
            
            $stmt2 = DB::pdo()->prepare("INSERT INTO organizations_displays (organization_id, display_id) VALUES (:organization_id, :display_id)");
            
            $stmt2->bindParam(":organization_id", $organization_id);
            $stmt2->bindParam(":display_id", $displayID);

            $stmt2->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        return $displayID;
    }

    public function get($id) 
    {
        if(!isset($id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            // Select on id
            $stmt = DB::pdo()->prepare("SELECT id, name FROM displays WHERE id = :id");
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

    public function addUser($display_id, $user_id) {
        if(!isset($display_id) || !isset($user_id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO users_displays (display_id, user_id) VALUES (:display_id, :user_id)");
            $stmt->bindParam(":display_id", $display_id);
            $stmt->bindParam(":user_id", $user_id);
            

            $stmt->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }
    }

    public function removeUser($display_id, $user_id) {
        if(!isset($display_id) || !isset($user_id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("DELETE FROM users_displays WHERE display_id = :display_id AND user_id = :user_id");
            $stmt->bindParam(":display_id", $display_id);
            $stmt->bindParam(":user_id", $user_id);

            $stmt->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }
    }

    public function getOrganizationsAll($organization_id) 
    {
        try 
        {
            $stmt = DB::pdo()->prepare("SELECT displays.id, displays.name FROM displays JOIN organizations_displays ON organizations_displays.display_id = displays.id WHERE organization_id =  :organization_id ");
            $stmt->bindParam(":organization_id", $organization_id);
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

    public function getUsersAll($user_id) 
    {
        try 
        {
            $stmt = DB::pdo()->prepare("SELECT displays.id, displays.name FROM displays JOIN users_displays ON users_displays.display_id = displays.id WHERE user_id =  :user_id");
            $stmt->bindParam(":user_id", $user_id);
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
}