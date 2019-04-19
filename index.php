<?php
  set_time_limit(0);
  function get_json($url)
  {
    $json = file_get_contents($url);
    $json = json_decode($json);
  	return $json;
  }

  function isi_data(&$data,$url_wilayah,$url_jml,$idx){

    $tmp=array('kab_kota','kec','lurah');  
    
    $json_wilayah = get_json($url_wilayah);
    $json_jml = get_json($url_jml);   
    
    if(!empty($json_wilayah)){
	    foreach ($json_wilayah as $key_wilayah=>$wilayah) {  
	       $data[$key_wilayah]['nama']=$wilayah->nama;
	       if(!empty($json_jml)){
	        if(isset($json_jml->table->$key_wilayah)){
	         foreach ($json_jml->table->$key_wilayah as $key_suara => $value) {
	     	       $data[$key_wilayah]['jml_suara'][$key_suara]=$value;
	         } 
            }
	       }
	       
	       if($idx==0){
	          $url_wilayah = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_wilayah.'.json';  
	          $url_jml = 'https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$key_wilayah.'.json';
	          isi_data($data[$key_wilayah][$tmp[$idx]],$url_wilayah,$url_jml,$idx+1); 
              
	       }elseif($idx<=1){
	                    
	         /* ;

	          $url_path = parse_url($url_wilayah, PHP_URL_PATH);
	          $dirname = pathinfo($url_wilayah, PATHINFO_DIRNAME);
	          $filename = pathinfo($url_wilayah, PATHINFO_FILENAME);

	          $url_wilayah = 'https://pemilu2019.kpu.go.id/'.$dirname.'/'.$filename.'/'.$key_wilayah.'.json';
	           
	          $url_path = parse_url($url_jml, PHP_URL_PATH);
	          $dirname = pathinfo($url_jml, PATHINFO_DIRNAME);
	          $filename = pathinfo($url_jml, PATHINFO_FILENAME);

	          $url_jml = 'https://pemilu2019.kpu.go.id/'.$dirname.'/'.$filename.'/'.$key_wilayah.'.json';
	          isi_data($data[$tmp[$idx]],$url_wilayah,$url_jml,$idx++);*/

	       }

	    }
	 }   
 }
  
  $data = array();
  isi_data($data,'https://pemilu2019.kpu.go.id/static/json/wilayah/0.json','https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json',0);
  echo "<pre>";
  print_r($data);
  echo "</pre>";


  //$url = 'https://pemilu2019.kpu.go.id/static/json/wilayah/1/2.json';
  //$url_path = parse_url($url, PHP_URL_PATH);
  //$dirname = pathinfo($url_path, PATHINFO_DIRNAME);
  //$filename = pathinfo($url_path, PATHINFO_FILENAME);
  //echo 'https://pemilu2019.kpu.go.id/'.$dirname.'/'.$filename;


 /* $json_prov = get_json('https://pemilu2019.kpu.go.id/static/json/wilayah/0.json');
  $json_jml_suara_prov = get_json('https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json');     
  
  $data=array();
  foreach ($json_prov as $key_prov=>$wilayah_prov) {
  	 $data[$key_prov]['nama']=$wilayah_prov->nama;
     foreach ($json_jml_suara_prov->table->$key_prov as $key_suara_prov => $value_prov) {
     	$data[$key_prov]['jml_suara'][$key_suara_prov]=$value_prov;
     }  

     
     $json_kab_kota = get_json('https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_prov.'.json');
     $json_jml_suara_kab_kota = get_json('https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$key_prov.'.json');   
     
        foreach ($json_kab_kota as $key_kab_kota=>$wilayah_kab_kota) {
  	       $data[$key_prov]['kab_kota'][$key_kab_kota]['nama']=$wilayah_kab_kota->nama;
           if(!empty($json_jml_suara_kab_kota)){
             foreach ($json_jml_suara_kab_kota->table->$key_kab_kota as $key_suara_kab_kota => $value_kab_kota){
     	          $data[$key_prov]['kab_kota'][$key_kab_kota]['jml_suara'][$key_suara_kab_kota]=$value_kab_kota;
             }  
           }  
            
           $json_kec = get_json('https://pemilu2019.kpu.go.id/static/json/wilayah/'.$key_prov.'/'.$key_kab_kota.'.json');
           $json_jml_suara_kec = get_json('https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp/'.$key_prov.'/'.$key_kab_kota.'.json');          

             foreach ($json_kec as $key_kec=>$wilayah_kec) {
  	                 $data[$key_prov]['kab_kota'][$key_kab_kota]['kec'][$key_kec]['nama']=$wilayah_kec->nama;
              if(!empty($json_jml_suara_kec)){
                  foreach ($json_jml_suara_kec->table->$key_kec as $key_suara_kec => $value_kec){
     	              $data[$key_prov]['kab_kota'][$key_kab_kota]['kec'][$key_kec]['jml_suara'][$key_suara_kec]=$value_kec;
                  }  
               }  
            
               

            }      

      }       

      echo "<pre>";
      print_r($data);
      echo "</pre>";     
      exit;    
  }*/
    
  
?>