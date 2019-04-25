<?php

require_once "data_kpu.php";
require_once "data_kawal.php";
require_once "db_pemilu.php";

/**
 * 
 */
class proses 
{
	
	function __construct()
	{
		# code...
	}

	function insert_provinsi()
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $hsl = $data_kawal->get_data();
	      
	    $data_provinsis = $data_kpu->get_data();
	    foreach ($data_provinsis as $kode_provinsi=>$data) {
	        $new_rec['kode']= "$kode_provinsi";
	        $new_rec['nama']= $data['nama'];        
	        $new_rec['data_kpu']= $data['jml_suara'];        
	        if(!empty($hsl)){
	          $new_rec['data_kawal']= $hsl[$kode_provinsi];
	        }

	        $db_pemilu->insert_provinsi($new_rec); 
	    }

	    return $data_kpu->err_msg;
	}

	function update_provinsi()
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $kawal_provinsi = $data_kawal->get_data();      
	    $kpu_provinsi = $data_kpu->get_data();

	    $data_provinsis = $db_pemilu->get_provinsi([],[]);   

	    foreach ($data_provinsis as $data) {
	       $new_rec = array();
	       if(!empty($kpu_provinsi) and isset($kpu_provinsi[$data['kode']]['jml_suara']) ){       
	        $new_rec['data_kpu']= $kpu_provinsi[$data['kode']]['jml_suara'];
	       }

	       if(!empty($kawal_provinsi) and isset($kawal_provinsi[$data['kode']])){ 
	        $new_rec['data_kawal']= $kawal_provinsi[$data['kode']];
	       }
	       
	       if (!empty($new_rec)) {
	        $db_pemilu->update_provinsi(array('_id'=>$data['_id']),array('$set'=>$new_rec),[]); 
	       } 

	    }
	    return $data_kpu->err_msg;
	}

	function insert_kabkota($kode_provinsi=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $data_provinsis = $kode_provinsi==0 ? $db_pemilu->get_provinsi([],[]) : $db_pemilu->get_provinsi(['kode'=>$kode_provinsi],[]);
	    foreach ($data_provinsis as $data_provinsi) {
	        
	         $kawal_kabkota = $data_kawal->get_data($data_provinsi['kode']);      
	         $kpu_kabkotas = $data_kpu->get_data(array($data_provinsi['kode']));
	         if(!empty($kpu_kabkotas)){  
	           foreach ($kpu_kabkotas as $kode_kabkota => $data_kabkota) {
	              $new_rec['kode']= "$kode_kabkota";
	              $new_rec['nama']= $data_kabkota['nama'];
	              $new_rec['kode_provinsi']= $data_provinsi['kode'];
	              if(isset($data_kabkota['jml_suara'])){  
	                $new_rec['data_kpu']= $data_kabkota['jml_suara'];
	              }
	              if(isset($kawal_kabkota[$kode_kabkota])){
	                $new_rec['data_kawal']= $kawal_kabkota[$kode_kabkota];            
	              }

	              $db_pemilu->insert_kabkota($new_rec); 
	           }           
	         }

	         //sleep(2); 
	    }
	    return $data_kpu->err_msg;
	}

	function update_kabkota($kode_provinsi=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();    

	    $data_provinsis = $kode_provinsi==0 ? $db_pemilu->get_provinsi([],[]) : $db_pemilu->get_provinsi(['kode'=>$kode_provinsi],[]);
	    foreach ($data_provinsis as $data_provinsi) {
	        
	         $kawal_kabkota = $data_kawal->get_data($data_provinsi['kode']);      
	         $kpu_kabkotas = $data_kpu->get_data(array($data_provinsi['kode']));
	         

	         $data_kabkotas = $db_pemilu->get_kabkota(array('kode_provinsi'=>$data_provinsi['kode']),[]);
	           foreach ($data_kabkotas as $data_kabkota) {
	              
	              $new_rec=array();
	              if(!empty($kpu_kabkota) and isset($kpu_kabkota[$data_kabkota['kode']]['jml_suara'])){  
	                $new_rec['data_kpu']= $kpu_kabkota[$data_kabkota['kode']]['jml_suara'];
	              }
	              if(!empty($kawal_kabkota) and isset($kawal_kabkota[$data_kabkota['kode']]) ){
	                $new_rec['data_kawal']= $kawal_kabkota[$data_kabkota['kode']];            
	              }
	              if(!empty($new_rec)){
	                $db_pemilu->update_kabkota(array('_id'=>$data_kabkota['_id']),array('$set'=>$new_rec),[]); 
	              }
	           }           
	          //sleep(2);
	    }
	    return $data_kpu->err_msg;
	}

	function insert_kec($kode_provinsi=0,$kode_kabkota=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $data_kabkotas = ($kode_provinsi==0) and ($kode_kabkota==0) ? $db_pemilu->get_kabkota([],[]) : $db_pemilu->get_kabkota(['kode_provinsi'=>$kode_provinsi,'kode'=>$kode_kabkota],[]);
	    if(!empty($data_kabkotas)){
		    foreach ($data_kabkotas as $data_kabkota) {	        
		         $kawal_kec = $data_kawal->get_data($data_kabkota['kode']);      
		         $kpu_kec = $data_kpu->get_data(array($data_kabkota['kode_provinsi'],$data_kabkota['kode']));

		         if(!empty($kpu_kec)){  
		           foreach ($kpu_kec as $kode_kec => $data_kec) {
		              $new_rec['kode']= "$kode_kec";
		              $new_rec['nama']= $data_kec['nama'];
		              $new_rec['kode_provinsi']= $data_kabkota['kode_provinsi'];
		              $new_rec['kode_kabkota']= $data_kabkota['kode'];

		              if(isset($data_kec['jml_suara'])){  
		                $new_rec['data_kpu']= $data_kec['jml_suara'];
		              }
		              if(isset($kawal_kabkota[$kode_kec])){
		                $new_rec['data_kawal']= $kawal_kabkota[$kode_kec];            
		              }

		              $db_pemilu->insert_kec($new_rec); 
		           }           
		         }
		         //sleep(2);
		    }
		 }
		 return $data_kpu->err_msg;   
	}

	function update_kec($kode_provinsi=0,$kode_kabkota=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();    

	    $data_kabkotas = ($kode_provinsi==0) and ($kode_kabkota==0) ? $db_pemilu->get_kabkota([],[]) : $db_pemilu->get_kabkota(['kode_provinsi'=>$kode_provinsi,'kode'=>$kode_kabkota],[]);
        if(!empty($data_kabkotas)){   
		    foreach ($data_kabkotas as $data_kabkota) {
		        
		         $kawal_kec = $data_kawal->get_data($data_kabkota['kode']);      
		         $kpu_kec = $data_kpu->get_data(array($data_kabkota['kode_provinsi'],$data_kabkota['kode']));         

		         $data_kecs = $db_pemilu->get_kec(array('kode_kabkota'=>$data_kabkota['kode']),[]);
		           foreach ($data_kecs as $data_kec) {
		              
		              $new_rec=array();
		              if(!empty($kpu_kec) and isset($kpu_kec[$data_kec['kode']]['jml_suara'])){  
		                $new_rec['data_kpu']= $kpu_kec[$data_kec['kode']]['jml_suara'];
		              }
		              if(!empty($kawal_kec) and isset($kawal_kec[$data_kec['kode']]) ){
		                $new_rec['data_kawal']= $kawal_kec[$data_kec['kode']];            
		              }
		              if(!empty($new_rec)){
		                $db_pemilu->update_kec(array('_id'=>$data_kec['_id']),array('$set'=>$new_rec),[]); 
		              }
		           } 
		           //sleep(2);
		    }
		 }
		 return $data_kpu->err_msg;   
	}


	function insert_kelurahan($kode_provinsi=0,$kode_kabkota=0,$kode_kec=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $data_kecs = (($kode_provinsi==0) and ($kode_kabkota==0) and ($kode_kec==0)) ? $db_pemilu->get_kec([],[]) : $db_pemilu->get_kec(['kode_provinsi'=>$kode_provinsi,'kode_kabkota'=>$kode_kabkota,'kode'=>$kode_kec],[]);

	    foreach ($data_kecs as $data_kec) {
	        
	         $kawal_kelurahan = $data_kawal->get_data($data_kec['kode']);      
	         $kpu_kelurahan = $data_kpu->get_data(array($data_kec['kode_provinsi'],$data_kec['kode_kabkota'],$data_kec['kode']));

	         if(!empty($kpu_kelurahan)){  
	           foreach ($kpu_kelurahan as $kode_kelurahan => $data_kelurahan) {
	              $new_rec['kode']= "$kode_kelurahan";
	              $new_rec['nama']= $data_kelurahan['nama'];
	              $new_rec['kode_provinsi']= $data_kec['kode_provinsi'];
	              $new_rec['kode_kabkota']= $data_kec['kode_kabkota'];
	              $new_rec['kode_kec']= $data_kec['kode'];

	              if(isset($data_kelurahan['jml_suara'])){  
	                $new_rec['data_kpu']= $data_kelurahan['jml_suara'];
	              }
	              if(isset($kawal_kelurahan[$kode_kelurahan])){
	                $new_rec['data_kawal']= $kawal_kelurahan[$kode_kelurahan];            
	              }

	              $db_pemilu->insert_kelurahan($new_rec); 
	           }           
	         }

	         //sleep(2);
	    }
	    return $data_kpu->err_msg;
	}

	function update_kelurahan($kode_provinsi=0,$kode_kabkota=0,$kode_kec=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();    

	    $data_kecs = (($kode_provinsi==0) and ($kode_kabkota==0) and ($kode_kec==0)) ? $db_pemilu->get_kec([],[]) : $db_pemilu->get_kec(['kode_provinsi'=>$kode_provinsi,'kode_kabkota'=>$kode_kabkota,'kode'=>$kode_kec],[]);
	    foreach ($data_kecs as $data_kec) {
	        
	         $kawal_kelurahan = $data_kawal->get_data($data_kec['kode']);      
	         $kpu_kelurahan = $data_kpu->get_data(array($data_kec['kode_provinsi'],$data_kec['kode_kabkota'],$data_kec['kode']));         

	           $data_kelurahans = $db_pemilu->get_kelurahan(array('kode_kec'=>$data_kec['kode']),[]);
	           foreach ($data_kelurahans as $data_kelurahan) {
	              
	              $new_rec=array();
	              if(!empty($kpu_kelurahan) and isset($kpu_kelurahan[$data_kelurahan['kode']]['jml_suara'])){  
	                $new_rec['data_kpu']= $kpu_kelurahan[$data_kelurahan['kode']]['jml_suara'];
	              }
	              if(!empty($kawal_kelurahan) and isset($kawal_kelurahan[$data_kelurahan['kode']]) ){
	                $new_rec['data_kawal']= $kawal_kelurahan[$data_kelurahan['kode']];            
	              }
	              if(!empty($new_rec)){
	                $db_pemilu->update_kelurahan(array('_id'=>$data_kelurahan['_id']),array('$set'=>$new_rec),[]); 
	              }
	           }
	           //sleep(2);
	    }
	    return $data_kpu->err_msg;

	}

	function insert_tps($kode_provinsi=0,$kode_kabkota=0,$kode_kec=0,$kode_kelurahan=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $data_kelurahans = (($kode_provinsi==0) and ($kode_kabkota==0) and ($kode_kec==0) and ($kode_kelurahan==0)) ? $db_pemilu->get_kelurahan([],[]) : $db_pemilu->get_kelurahan(['kode_provinsi'=>$kode_provinsi,'kode_kabkota'=>$kode_kabkota,'kode_kec'=>$kode_kec,'kode'=>$kode_kelurahan],[]);
	    foreach ($data_kelurahans as $data_kelurahan) {
	        
	         $kawal_tps = $data_kawal->get_data($data_kelurahan['kode']);
             $kpu_tps = $data_kpu->get_data(array($data_kelurahan['kode_provinsi'],$data_kelurahan['kode_kabkota'],$data_kelurahan['kode_kec'],$data_kelurahan['kode']));

	         if(!empty($kpu_tps)){  	           
	           foreach ($kpu_tps as $kode_tps => $data_tps) {	              
	              
		              $new_rec['kode']= "$kode_tps";
		              $new_rec['nama']= $data_tps['nama'];
		              $new_rec['kode_provinsi']= $data_kelurahan['kode_provinsi'];
		              $new_rec['kode_kabkota']= $data_kelurahan['kode_kabkota'];
		              $new_rec['kode_kec']= $data_kelurahan['kode_kec'];
		              $new_rec['kode_kelurahan']= $data_kelurahan['kode'];

		               $tmp = explode(' ',$data_tps['nama']);
	                                    
		               $detail_tps = $data_kpu->get_data(array($data_kelurahan['kode_provinsi'],$data_kelurahan['kode_kabkota'],$data_kelurahan['kode_kec'],$data_kelurahan['kode'],$kode_tps));
	                  
	 
		              if(!empty($detail_tps)){
		                $new_rec['data_kpu']= $detail_tps;
		              }
		             
		              if(!empty($kawal_tps) and isset($kawal_tps[intval($tmp[1])])){
		                $new_rec['data_kawal']['sum']=$kawal_tps[intval($tmp[1])]->sum;
		                $new_rec['data_kawal']['ts']=$kawal_tps[intval($tmp[1])]->ts;
	                    foreach ($kawal_tps[intval($tmp[1])]->photos as $key => $value) {
	                    	$tmp_arr=array();
	                    	$tmp_arr['url']=$key;
	                    	foreach ($value as $key1 => $value1) {
	                    		$tmp_arr[$key1]=$value1;                    		
	                    	}
	                    	$new_rec['data_kawal']['photos'][]=$tmp_arr;
	                    }
		                 
		              }

		             $db_pemilu->insert_tps($new_rec);
                   
	           }           
	         }
	         //sleep(2);
	    }
	    return $data_kpu->err_msg;
	}

	function update_tps($kode_provinsi=0,$kode_kabkota=0,$kode_kec=0,$kode_kelurahan=0)
	{
	    $data_kpu = new data_kpu();
	    $data_kawal = new data_kawal();
	    $db_pemilu = new db_pemilu();

	    $data_kelurahans = (($kode_provinsi==0) and ($kode_kabkota==0) and ($kode_kec==0) and ($kode_kelurahan==0)) ? $db_pemilu->get_kelurahan([],[]) : $db_pemilu->get_kelurahan(['kode_provinsi'=>$kode_provinsi,'kode_kabkota'=>$kode_kabkota,'kode_kec'=>$kode_kec,'kode'=>$kode_kelurahan],[]);
	    foreach ($data_kelurahans as $data_kelurahan) {
	        
	         $kawal_tps = $data_kawal->get_data($data_kelurahan['kode']);      
	         $kpu_tps = $data_kpu->get_data(array($data_kelurahan['kode_provinsi'],$data_kelurahan['kode_kabkota'],$data_kelurahan['kode_kec'],$data_kelurahan['kode']));

	         $data_tpss = $db_pemilu->get_tps(array('kode_kelurahan'=>$data_kelurahan['kode']),[]);
	         if(!empty($data_tpss)){  
	           foreach ($data_tpss as $data_tps) {
	              
	              $tmp = explode(' ',$data_tps['nama']);

	              $detail_tps = $data_kpu->get_data(array($data_kelurahan['kode_provinsi'],$data_kelurahan['kode_kabkota'],$data_kelurahan['kode_kec'],$data_kelurahan['kode'],$data_tps['kode']));

	              $new_rec = array();

	              if(!empty($detail_tps)){
	                $new_rec['data_kpu']= $detail_tps;
	              }
	             
	              if(!empty($kawal_tps) and isset($kawal_tps[intval($tmp[1])])){
	              	$new_rec['data_kawal']['sum']=$kawal_tps[intval($tmp[1])]->sum;
	                $new_rec['data_kawal']['ts']=$kawal_tps[intval($tmp[1])]->ts;
                    foreach ($kawal_tps[intval($tmp[1])]->photos as $key => $value) {
                    	$tmp_arr=array();
                    	$tmp_arr['url']=$key;
                    	foreach ($value as $key1 => $value1) {
                    		$tmp_arr[$key1]=$value1;                    		
                    	}
                    	$new_rec['data_kawal']['photos'][]=$tmp_arr;
                    }
	              }
	             
	             if(!empty($new_rec)){
	                $db_pemilu->update_tps(array('_id'=>$data_tps['_id']),array('$set'=>$new_rec),[]);    
	              }
	           }           
	         }

	         //sleep(2);
	    }
	    return $data_kpu->err_msg;
	}
}

?>