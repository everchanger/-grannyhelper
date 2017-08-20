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

    public function join($organization_id, $user_id) 
    {
        if(!isset($organization_id) || !isset($user_id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO organizations_users (organization_id, user_id) VALUES (:organization_id, :user_id)");
            
            $stmt->bindParam(":organization_id", $organization_id);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        return;
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

    public function getMembers($organization_id) 
    {
        try 
        {
            $members = array();

            // Fetch administrators 
            $stmt = DB::pdo()->prepare("SELECT users.id, users.email FROM users JOIN organizations_admins ON organizations_admins.user_id = users.id WHERE organization_id = :organization_id");
            $stmt->bindParam(":organization_id", $organization_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0){
                $members = $stmt->fetchAll(\PDO::FETCH_OBJ);
            }

            foreach($members as $member) {
                $member->isAdmin = true;
            }

            // Fetch ordinary users 
            $stmt2 = DB::pdo()->prepare("SELECT users.id, users.email FROM users JOIN organizations_users ON organizations_users.user_id = users.id WHERE organization_id = :organization_id");
            $stmt2->bindParam(":organization_id", $organization_id);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0){
                $members = array_merge($members, $stmt2->fetchAll(\PDO::FETCH_OBJ));
            }

            return $members;
        } 
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }

    public function memberOf($user_id) 
    {
        try 
        {
            $stmt = DB::pdo()->prepare("SELECT organization_id as member_of FROM organizations_users WHERE user_id = :user_id");
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            if ($stmt->rowCount() <= 0){
                // Check if we're admins
                
                $stmt2 = DB::pdo()->prepare("SELECT organization_id as admin_of FROM organizations_admins WHERE user_id = :user_id");
                $stmt2->bindParam(":user_id", $user_id);
                $stmt2->execute();

                if ($stmt2->rowCount() <= 0){
                    null;
                }

                return $stmt2->fetch(\PDO::FETCH_OBJ);
            }

            return $stmt->fetch(\PDO::FETCH_OBJ);
        } 
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }

    public function setIsAdministrator($organization_id, $user_id, $admin) 
    {
        if(!isset($organization_id) || !isset($user_id)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = null;
            $stmt2 = null;

            if($admin) {
                $stmt = DB::pdo()->prepare("DELETE FROM organizations_users WHERE user_id = :user_id AND organization_id = :organization_id");
                $stmt2 = DB::pdo()->prepare("INSERT INTO organizations_admins (organization_id, user_id) VALUES (:organization_id, :user_id)");
            } else {
                $stmt = DB::pdo()->prepare("DELETE FROM organizations_admins WHERE user_id = :user_id AND organization_id = :organization_id");
                $stmt2 = DB::pdo()->prepare("INSERT INTO organizations_users (organization_id, user_id) VALUES (:organization_id, :user_id)");
            }
            
            $stmt->bindParam(":organization_id", $organization_id);
            $stmt->bindParam(":user_id", $user_id);
            $stmt2->bindParam(":organization_id", $organization_id);
            $stmt2->bindParam(":user_id", $user_id);
           
            $stmt->execute();
            $stmt2->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }      
    }
}