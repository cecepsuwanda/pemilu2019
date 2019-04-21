<?php
  
  
  require_once "data_kpu.php";
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



  


  
?>