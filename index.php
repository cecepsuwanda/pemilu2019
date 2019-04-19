<?php
  set_time_limit(0);
  function get_json($url)
  {
    $json = array();
    try {
      $json = file_get_contents($url);
      $json = json_decode($json);	
    } catch (Exception $e) {
      echo $e->getMessage();	
    }      
     return $json;    	
  	
  }

  function isi_data(&$data,$url_wilayah,$url_jml,$idx){

    $tmp=array('kab_kota','kec','lurah','tps');  
    
    $json_wilayah = get_json($url_wilayah);
    $json_jml = get_json($url_jml);   
    
    

    if(!empty($json_wilayah)){
	    foreach ($json_wilayah as $key_wilayah=>$wilayah) {  
	       $rec = array();
	       $rec['kode']=$key_wilayah;
	       $rec['nama']=$wilayah->nama;
	       //$data[$key_wilayah]['nama']=$wilayah->nama;
	       $rec['jml_suara']=array();
	       if(!empty($json_jml)){
	        if(isset($json_jml->table->$key_wilayah)){
	         foreach ($json_jml->table->$key_wilayah as $key_suara => $value) {
	     	       //$data[$key_wilayah]['jml_suara'][$key_suara]=$value;
	         	   $rec['jml_suara'][$key_suara]=$value;
	         } 
            }
	       }
	       
	       if($idx==0){
	          $url_wilayah = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_wilayah.'.json';  
	          $url_jml = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$key_wilayah.'.json';
	          isi_data($data[$key_wilayah][$tmp[$idx]],$url_wilayah,$url_jml,$idx+1); 
              
	       }elseif($idx<=3){

	          $url_path = parse_url($url_wilayah, PHP_URL_PATH);
	          $dirname = pathinfo($url_wilayah, PATHINFO_DIRNAME);
	          $filename = pathinfo($url_wilayah, PATHINFO_FILENAME);

	          $url_wilayah_tmp = $dirname.'/'.$filename.'/'.$key_wilayah.'.json';
	           
	          $url_path = parse_url($url_jml, PHP_URL_PATH);
	          $dirname = pathinfo($url_jml, PATHINFO_DIRNAME);
	          $filename = pathinfo($url_jml, PATHINFO_FILENAME);

	          $url_jml_tmp = $dirname.'/'.$filename.'/'.$key_wilayah.'.json';
	          isi_data($data[$key_wilayah][$tmp[$idx]],$url_wilayah_tmp,$url_jml_tmp,$idx+1);

	       }

	    }
	 }   
 }
  
  $data = array();
  isi_data($data,'https://pemilu2019.kpu.go.id/static/json/wilayah/0.json','https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json',0);
  echo "<pre>";
  print_r($data);
  echo "</pre>";


  
    
  
?>