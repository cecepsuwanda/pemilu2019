<?php
  
  
  require_once "data_kpu.php";
  require_once "data_kawal.php";
  require_once "db_pemilu.php";


  set_time_limit(0);
  
  function baca_dt_tps()
  {
        $data_kpu = new data_kpu();
        $db_pemilu = new db_pemilu();

        $data = $db_pemilu->get_kelurahan(array('kode_provinsi'=>'1'),[]);
        foreach ($data as $row) {
          
          $data_tpss = $data_kpu->get_data(array($row['kode_provinsi'],$row['kode_kabkota'],$row['kode_kec'],$row['kode']));    

        if(!empty($data_tpss)){
          foreach ($data_tpss as $kode_tps => $value) {      
            if(is_array($value)){
               $new_data['kode'] = "$kode_tps";
               $new_data['nama'] =  $value['nama'];
               $new_data['kode_provinsi'] = $row['kode_provinsi'];
               $new_data['kode_kabkota'] = $row['kode_kabkota'];
               $new_data['kode_kec'] = $row['kode_kec'];
               $new_data['kode_kelurahan'] = $row['kode'];

               $data_tps = $data_kpu->get_data(array($row['kode_provinsi'],$row['kode_kabkota'],$row['kode_kec'],$row['kode'],$kode_tps));
               if(!empty($data_tps)){
                 $new_data['data_kpu'] = $data_tps;
               } 
               $db_pemilu->insert_tps($new_data);
            }else{
              echo "<pre>";
                 echo $kode_tps.'<br>'; 
                 var_dump($value);
              echo "</pre>";
            }

          }  
         }  
         
        }
  }


 /* $data_kawal = new data_kawal();
  $db_pemilu = new db_pemilu();
  
  $data_kelurahan = $db_pemilu->get_kelurahan(array('kode_provinsi'=>'1'),[]);
  foreach ($data_kelurahan as $row) {
    
    $data = $data_kawal->get_data($row['kode']);    
    if(!empty($data)){
      echo "$row[kode]<br>";
      echo "$row[nama]<br>";

      echo "<pre>"; 
        print_r($data);
      echo "</pre>"; 
    }  
  }*/

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
        $new_rec['data_kawal']= $hsl[$kode_provinsi];
        $db_pemilu->insert_provinsi($new_rec); 
    }
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
            
        
        $new_rec['data_kpu']= $kpu_provinsi[$data['kode']]['jml_suara'];
        $new_rec['data_kawal']= $kawal_provinsi[$data['kode']];
               
        $db_pemilu->update_provinsi(array('_id'=>$data['_id']),array('$set'=>$new_rec),[]); 

    }
}

function insert_kabkota()
{
    $data_kpu = new data_kpu();
    $data_kawal = new data_kawal();
    $db_pemilu = new db_pemilu();

    $data_provinsis = $db_pemilu->get_provinsi([],[]);
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
    }
}

function update_kabkota()
{
    $data_kpu = new data_kpu();
    $data_kawal = new data_kawal();
    $db_pemilu = new db_pemilu();    

    $data_provinsis = $db_pemilu->get_provinsi([],[]);
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
          
    }
}

update_kabkota();
  
?>