<?php

namespace model;

class Event
{
    public function get($date) 
    {
        if(!isset($date)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try 
        {
            $stmt = DB::pdo()->prepare("SELECT * FROM events WHERE (DAYOFWEEK(date) = :dayOfWeek AND standard = 1) OR (date = :date)");
            
            $dayOfWeek = $date->format('w')+1;

            $stmt->bindParam(":dayOfWeek", $dayOfWeek);
            $stmt->bindParam(":date", $date->format('Y-m-d H:i:s'));

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

    public function set($date, $title, $description, $photo, $standard)
    {
        if(!isset($date) || !isset($title) || !isset($description)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        // Do we have a photo that needs insertion?
        $photo_id = 0;
        if($photo) {
            try 
            {
                $stmt = DB::pdo()->prepare("INSERT INTO photos (filename) VALUES (:name)");
                
                $stmt->bindParam(":name", $photo);

                $stmt->execute();
            } 
            catch (\Exception $e) 
            {
                throw $e;
            }

            $photo_id = DB::pdo()->lastInsertId();
        }

        try 
        {
            $stmt = DB::pdo()->prepare("INSERT INTO events (title, description, date, standard, photo_id) VALUES (:title, :desc, :date, :std, :photo_id)");
            
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":desc", $description);
            $stmt->bindParam(":date", $date->format('Y-m-d H:i:s'));
            $stmt->bindParam(":std", $standard);
            $stmt->bindParam(":photo_id", $photo_id);

            $stmt->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        return DB::pdo()->lastInsertId();
    }
}