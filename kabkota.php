<?php
   
  require_once "proses.php";
  require_once "fumum.php";
  set_time_limit(0);
  
  
  $kode_provinsi=$_GET['p1'];


 $proses = new proses();
 $insert_err=$proses->insert_kabkota($kode_provinsi);
 $update_err=$proses->update_kabkota($kode_provinsi); 
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pantau Progress Pilpres</title>
</head>
<body>
    <a href="index.php">Provinsi</a>
    
    <table border="1" width="80%">       
       
        <?php 
           $db_pemilu = new db_pemilu();
           $data_provinsis = $db_pemilu->get_provinsi(['kode'=>$kode_provinsi],[]);
          
           $i=1;
           $str='';
           $total1=array(0,0);          
           $total2=array(0,0);
           foreach ($data_provinsis as $data_provinsi) {
             $str.='<tr>';
               $str.="<td>$i</td>";               
               $str.="<td>$data_provinsi[nama]</td>";               
               $jml=array($data_provinsi['data_kpu'][21],$data_provinsi['data_kpu'][22]);
               $str.=jumlahkan($jml,'bgcolor="#ffbf00"',$total1);               
               
               $p = $data_provinsi['data_kpu']['persen'];
               $pcolor = $p>90 ? 'bgcolor="#0080ff"': ($p>80 ? 'bgcolor="#00ffff"': ($p>50 ? 'bgcolor="#ffff00"' :'')) ;

               $str.='<td align="right" '.$pcolor.'>'.number_format($p,2,',','.').'</td>';
               $jml=array($data_provinsi['data_kawal']['sum']['pas1'],$data_provinsi['data_kawal']['sum']['pas2']);
               $str.=jumlahkan($jml,'bgcolor="#ffbf00"',$total2);

               $p = ($data_provinsi['data_kawal']['sum']['cakupan']/$data_provinsi['data_kawal']['sum']['jml_tps'])*100;
               $pcolor = $p>90 ? 'bgcolor="#0080ff"': ($p>80 ? 'bgcolor="#00ffff"': ($p>50 ? 'bgcolor="#ffff00"' :'')) ;
               $str.='<td align="right" '.$pcolor.'>'.number_format($p,2,',','.').'</td>';               

             $str.='</tr>';              
             $i++;
           }          
           
           echo table_header('Provinsi').$str;  

        ?>
    </table>
    <br><br>
    <table border="1" width="80%"> 
        <?php 
           $db_pemilu = new db_pemilu();

           $data_provinsis = $db_pemilu->get_kabkota(['kode_provinsi'=>$kode_provinsi],[]);

           $i=1;
           $str='';
           $total1=array(0,0);          
           $total2=array(0,0);
           foreach ($data_provinsis as $data_provinsi) {
             $str.='<tr>';
               $str.="<td>$i</td>";               
               $str.="<td><a href='kec.php?p1=$kode_provinsi&p2=$data_provinsi[kode]'>$data_provinsi[nama]</a></td>";               
               $jml=array($data_provinsi['data_kpu'][21],$data_provinsi['data_kpu'][22]);
               $str.=jumlahkan($jml,'bgcolor="#ffbf00"',$total1);               
               
               $p = $data_provinsi['data_kpu']['persen'];
               $pcolor = $p>90 ? 'bgcolor="#0080ff"': ($p>80 ? 'bgcolor="#00ffff"': ($p>50 ? 'bgcolor="#ffff00"' :'')) ;

               $str.='<td align="right" '.$pcolor.'>'.number_format($p,2,',','.').'</td>';               
               $jml=array($data_provinsi['data_kawal']['sum']['pas1'],$data_provinsi['data_kawal']['sum']['pas2']);
               $str.=jumlahkan($jml,'bgcolor="#ffbf00"',$total2);

               $p = ($data_provinsi['data_kawal']['sum']['cakupan']/$data_provinsi['data_kawal']['sum']['jml_tps'])*100;
               $pcolor = $p>90 ? 'bgcolor="#0080ff"': ($p>80 ? 'bgcolor="#00ffff"': ($p>50 ? 'bgcolor="#ffff00"' :'')) ;
               $str.='<td align="right" '.$pcolor.'>'.number_format($p,2,',','.').'</td>';               

             $str.='</tr>';              
             $i++;
           }
          
           $tmpstr='<tr>';
               $tmpstr.="<td colspan='2' >TOTAL</td>";               
               $tmpstr.= jumlahkan($total1);
               $tmpstr.='<td ></td>';
               $tmpstr.= jumlahkan($total2,'bgcolor="#ffbf00"');
             $tmpstr.='</tr>';             

           echo table_header('Kabupaten/Kota').$tmpstr.$str;  

        ?>
    </table>
    <?php 
      echo $insert_err;
      echo $update_err;
    ?>
</body>
</html>