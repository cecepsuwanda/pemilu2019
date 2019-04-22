<?php

/**
 * 
 */
class data_kawal 
{
	
	function __construct()
	{
		# code...
	}

	  private function get_json($url)
	  {
	    $json = array();
	    try {
	      $json = file_get_contents($url);
	      $json = json_decode($json);	
	    } catch (Exception $e) {
	      	
	    }      
	     return $json;    		  	
	  }

	  public function get_data($idx=0)
	  {
	  	
	  	$url_data   = 'https://kawal-c1.appspot.com/api/c/'.$idx;	  	 	  

	  	$json_data = $this->get_json($url_data);	  	
        
        $data = array();

	  	if (!empty($json_data) and isset($json_data->children)) {	       
	       foreach ($json_data->children as $row) {
	       	 $kd=$row[0];	       	 
	         if(isset($json_data->data->$kd) and !empty($json_data->data->$kd) and isset($json_data->data->$kd->sum->sah)){
	           $data[$kd]=$json_data->data->$kd;	         
	         }   

	       }	                         
	  	}
        
        return $data;
	  }

}




?>