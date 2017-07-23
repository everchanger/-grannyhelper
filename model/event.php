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
            $stmt = DB::pdo()->prepare("SELECT e.id as id, e.description, e.title, e.standard, p.id as photo_id, p.filename FROM events e JOIN photos AS p ON p.id = e.photo_id WHERE (DAYOFWEEK(e.date) = :dayOfWeek AND e.standard = 1) OR (e.date = :date)");
            
            $dayOfWeek = $date->format('w')+1;
            $formatedDate = $date->format('Y-m-d H:i:s');

            $stmt->bindParam(":dayOfWeek", $dayOfWeek);
            $stmt->bindParam(":date", $formatedDate);

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

    public function delete($id) 
    {
        if(!isset($id)){
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        try {
            $stmt = DB::pdo()->prepare("DELETE FROM events WHERE id =:id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
        }
        catch (\Exception $e) 
        {
            throw $e;
        } 
    }

    public function set($id, $date, $title, $description, $photo_id, $photo, $standard)
    {
        if(!isset($date) || !isset($title) || !isset($description)) 
        {
            throw new \Exception("One or more input parameters are not set", ERROR_CODE_INVALID_PARAMETERS);
        }

        // Do we have a photo that needs insertion?
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
            // If we are setting a standard we need to remove the current standard post for this weekday.
            if($standard) {
                $stmt = DB::pdo()->prepare("DELETE FROM events WHERE (DAYOFWEEK(date) = :dayOfWeek AND standard = 1 AND NOT id =:id)");
                $dayOfWeek = $date->format('w')+1;

                $stmt->bindParam(":id", $id);
                $stmt->bindParam(":dayOfWeek", $dayOfWeek);
                $stmt->execute();
            } else {
                // This means we are not setting a new standard, if we're modifying a special event aka an event with the standard flag sat to 0 we don't
                // need to do anything. If we are modifying an event with the standard flag sat to 1 we should add this event, not modify!
                if($id) { 
                    $stmt = DB::pdo()->prepare("SELECT standard FROM events e WHERE e.id = :id");
                    $stmt->bindParam(":id", $id);
                    $stmt->execute();

                    $event = $stmt->fetchAll(\PDO::FETCH_OBJ);

                    if($event[0]->standard) {
                        // Modifying a standard event but we're trying to set it to be nonstandard, this is not allowed, set id to zero to insert an new event.
                        $id = 0;
                    }
                }
            }
            

            if($id) {
                $stmt = DB::pdo()->prepare("UPDATE events SET title=:title, description=:desc, date=:date, standard=:std, photo_id=:photo_id WHERE id=:id");
                $stmt->bindParam(":id", $id);
            } else {
                $stmt = DB::pdo()->prepare("INSERT INTO events (title, description, date, standard, photo_id) VALUES (:title, :desc, :date, :std, :photo_id)");
            }
            
            $formatedDate = $date->format('Y-m-d H:i:s');

            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":desc", $description);
            $stmt->bindParam(":date", $formatedDate);
            $stmt->bindParam(":std", $standard);
            $stmt->bindParam(":photo_id", $photo_id);
        
            $stmt->execute();
        } 
        catch (\Exception $e) 
        {
            throw $e;
        }

        if(!$id) {
            return DB::pdo()->lastInsertId();
        }
    }
}