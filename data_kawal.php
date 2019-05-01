<?php

/**
 * 
 */
class data_kawal 
{
	public $err_msg;
	function __construct()
	{
		# code...
	}

	  private function get_json($url)
	  {
	    $json = array();
	    
	    set_error_handler(
		    function ($severity, $message, $file, $line) {
		        throw new ErrorException($message, $severity, $severity, $file, $line);
		    }
		);

	    try {
	      $json = file_get_contents($url);
	      $json = json_decode($json);	
	    } catch (Exception $e) {
	      $this->err_msg.=$e->getMessage().'<br>';	
	    }

	    restore_error_handler();      
	    
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
	           $data[$kd]->sum->jml_tps=$row[2];	         
	         }   
             
	       }	                         
	  	}
        
        return $data;
	  }

}




?>