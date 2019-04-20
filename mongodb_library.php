<?php

require_once __DIR__ . "/vendor/autoload.php";


class mongodb_library
{

   private $collection;

   function __construct($db,$collection)
   {
     $this->collection = (new MongoDB\Client)->$db->$collection;
   }


   function insertOne($data)
   {
		$insertOneResult = $this->collection->insertOne($data);

		echo "Inserted ".$insertOneResult->getInsertedCount()." document(s) <br>";
        echo '<pre>';
		var_dump($insertOneResult->getInsertedId());
		echo '</pre>';

   }
    
   function insertMany($data)
   {
		$insertManyResult = $this->collection->insertMany($data);

		echo "Inserted ".$insertManyResult->getInsertedCount()." document(s) <br>";
    echo '<pre>';
		var_dump($insertManyResult->getInsertedIds());
		echo '</pre>';

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
      echo "Matched ".$updateResult->getMatchedCount()." document(s)<br>" ;
      echo "Modified ".$updateResult->getModifiedCount()." document(s)<br>";
    }

}

?>