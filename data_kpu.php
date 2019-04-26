<?php

/**
 * 
 */
class data_kpu 
{
	public $err_msg;
	
	function __construct()
	{
		$this->err_msg='';
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

	  public function get_data($args=array())
	  {
	  	$i=1;
	  	if(empty($args)){
	  	  $url_wilayah   = 'https://pemilu2019.kpu.go.id/static/json/wilayah/0.json';
	  	  $url_jml_suara = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json';
	  	}else{
	      $url_wilayah = 'https://pemilu2019.kpu.go.id/static/json/wilayah/';  
	      $url_jml_suara = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/';		
	      
	      $jml = count($args);
	      
	      foreach ($args as $key => $value) {
	      	$url_wilayah.= $value . (($i<$jml) ? '/' : '.json');
	      	$url_jml_suara.= $value . (($i<$jml) ? '/' : '.json');
            $i++;
	      }
	  	}  

	     $json_wilayah=array();
	     if($i<5){		  	
	  	   $json_wilayah = $this->get_json($url_wilayah);
	  	  }
	  	  $json_suara = $this->get_json($url_jml_suara);

        
        $data = array();

	  	if (!empty($json_wilayah)) {
	       foreach ($json_wilayah as $kd_wilayah => $data_wilayah) {
	            $data[$kd_wilayah]['nama']=$data_wilayah->nama;
	            if(!empty($json_suara)){
	                 if(isset($json_suara->table->$kd_wilayah)){
	                    foreach ($json_suara->table->$kd_wilayah as $kd_kandidat => $value) {
	     	                 $data[$kd_wilayah]['jml_suara'][$kd_kandidat]=$value;
	         	   
	                    } 
                     }
	            }      
	       }                    
	  	}elseif (!empty($json_suara)) {
	  		 if($i>=5){  
	  		    foreach ($json_suara as $key=>$value) {
	     	        $data[$key]=$value;	         	   
	            } 
             }       
	  	}
        
        return $data;
	  }

}




?>