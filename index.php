<?php
  
  
  require_once "data_kpu.php";


  set_time_limit(0);
 

  //$data = array();
  //isi_data($data,'https://pemilu2019.kpu.go.id/static/json/wilayah/0.json','https://pemilu2019.kpu.go.id/static/json/hhcw/ppwp.json',0);
  //echo "<pre>";
  //print_r($data);
 //echo "</pre>";

/* $mng_kelurahan = new mongodb_library('kpu_pemilu2019','kd_kelurahan');
 $mng_tps = new mongodb_library('kpu_pemilu2019','tps');

 $data_kelurahan = $mng_kelurahan->find([],[]);
 $i=1;
 foreach ($data_kelurahan as $row) {       
      $data_tps = $mng_tps->findOne(array('kode_kelurahan'=>$row['kode']));
      if(empty($data_tps)){
      	  echo "kelurahan ke-$i kode : $row[kode] <br>";
		  $url_wilayah = 'https://pemilu2019.kpu.go.id/static/json/wilayah/'.$row['kode_provinsi'].'/'.$row['kode_kabkota'].'/'.$row['kode_kec'].'/'.$row['kode'].'.json';
		  $json_wilayah = get_json($url_wilayah);  
		  if(!empty($json_wilayah)){
			  foreach ($json_wilayah as $key => $value) {
			  	       $rec = array();
				       $rec['kode']=$key;
				       $rec['nama']=$value->nama;
				       $rec['kode_provinsi']=$row['kode_provinsi'];
				       $rec['kode_kabkota']=$row['kode_kabkota'];
				       $rec['kode_kec']=$row['kode_kec']; 
				       $rec['kode_kelurahan']=$row['kode']; 
			           
			           
			           $data_tps = $mng_tps->findOne(array('kode'=>$key));
			           if(empty($data_tps)){			             
			             $mng_tps->insertOne($rec); 
			           }else{
			             //echo "$i $key sudah ada !<br>";             
			           } 
			                     
			   }
		  }
	}
	$i++;	  	      
}*/
  
?>