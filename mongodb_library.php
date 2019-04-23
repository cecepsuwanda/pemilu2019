<?php

require_once __DIR__ . "/vendor/autoload.php";


class mongodb_library
{

   private $collection;
   public $msg;

   function __construct($db,$collection)
   {
     $this->collection = (new MongoDB\Client)->$db->$collection;
     $this->msg = '';
   }


   function insertOne($data)
   {
		$insertOneResult = $this->collection->insertOne($data);

		$this->msg="Inserted ".$insertOneResult->getInsertedCount()." document(s) <br>";
    //echo '<pre>';
		return $insertOneResult->getInsertedId();
		//echo '</pre>';

   }
    
   function insertMany($data)
   {
		$insertManyResult = $this->collection->insertMany($data);

		$this->msg="Inserted ".$insertManyResult->getInsertedCount()." document(s) <br>";
    //echo '<pre>';
		return $insertManyResult->getInsertedIds();
		//echo '</pre>';

   } 

   function findOne($where)
   {
     $document = $this->collection->findOne($where);     
     return $document;
   }

   function find($where,$option)
   {
   	$cursor = $this->collection->find($where,$option);
    return $cursor;
   }

   function drop()
   {
   	$this->collection->drop();
   }

   function aggregate($opt)
   {
   	 $document = $this->collection->aggregate($opt);
   	 return $document;
   }

   public function updateMany($where,$set,$option)
    {
      $updateResult = $this->collection->updateMany($where,$set,$option);
      $this->msg= "Matched ".$updateResult->getMatchedCount()." document(s)<br>" ;
      $this->msg.= "Modified ".$updateResult->getModifiedCount()." document(s)<br>";
    }

}

?>