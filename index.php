<?php
  
  
  
  require_once "proses.php";


  set_time_limit(0);
  



/*$id = uniqid(); 

$db_pemilu = new db_pemilu();
$new_rec=array();
$new_rec['kode']=$id;
$new_rec['nama']='Input Provinsi';
$new_rec['progress']='Input Provinsi';
$new_rec['waktu_mulai']=date("Y-m-d H:i:s");
$new_rec['waktu_selesai']=date("Y-m-d H:i:s");;
$db_pemilu->insert_progres($new_rec);

$new_rec = array();
$new_rec['progress']='Kode-Nama-Status';
$db_pemilu->update_progres(array('kode'=>"$id"),array('$set'=>$new_rec),[]);*/

/*echo "update provinsi <br>";
update_provinsi();
echo "insert kabkota <br>";
insert_kabkota();
echo "insert kec <br>";
insert_kec();
echo "update kec <br>";
update_kec();
echo "update kabkota <br>";
update_kabkota();
echo "update kelurahan <br>";
update_kelurahan();
echo "insert kelurahan <br>";
insert_kelurahan();*/
 $proses = new proses();
 $update_err=$proses->update_provinsi(); 
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pantau Progress Pilpres</title>
</head>
<body>

    <table border="1" width="80%">       
       <tr>
           <th rowspan="3" >No</th>
           <th rowspan="3">Provinsi</th>
           <th colspan="4">Data KPU</th>
           <th colspan="4">Data KawalPemilu</th>           
       </tr> 
       <tr>
         <th colspan="2"> Kandidat 1</th>
         <th colspan="2"> Kandidat 2</th>
         <th colspan="2"> Kandidat 1</th>
         <th colspan="2"> Kandidat 2</th>
       </tr>
       <tr>
         <th>Jumlah</th>
         <th>%</th>
         <th>Jumlah</th>
         <th>%</th>
         <th>Jumlah</th>
         <th>%</th>
         <th>Jumlah</th>
         <th>%</th>
       </tr>
        <?php 
           $db_pemilu = new db_pemilu();



           $data_provinsis = $db_pemilu->get_provinsi([],[]);
          
           $i=1;
           $str='';
           $total1=0;
           $total2=0;
           $total3=0;
           $total4=0;
           foreach ($data_provinsis as $data_provinsi) {
             $jml=$data_provinsi['data_kpu'][21]+$data_provinsi['data_kpu'][22];
             $p1 = ($data_provinsi['data_kpu'][21]/$jml)*100;
             $p2 = ($data_provinsi['data_kpu'][22]/$jml)*100;
             $p1color = ($p1>=50 ? 'bgcolor="#00FF00"' : '');
             $p2color = ($p2>=50 ? 'bgcolor="#00FF00"' : '');
             $jml=$data_provinsi['data_kawal']['sum']['pas1']+$data_provinsi['data_kawal']['sum']['pas2'];
             $p3 = ($data_provinsi['data_kawal']['sum']['pas1']/$jml)*100;
             $p4 = ($data_provinsi['data_kawal']['sum']['pas2']/$jml)*100;
             $p3color = ($p3>=50 ? 'bgcolor="#FF0000"' : '');
             $p4color = ($p4>=50 ? 'bgcolor="#FF0000"' : '');

             $total1+=$data_provinsi['data_kpu'][21];
             $total2+=$data_provinsi['data_kpu'][22];
             $total3+=$data_provinsi['data_kawal']['sum']['pas1'];
             $total4+=$data_provinsi['data_kawal']['sum']['pas2'];

             $str.='<tr>';
               $str.="<td>$i</td>";               
               $str.="<td><a href='kabkota.php?p1=$data_provinsi[kode]'>$data_provinsi[nama]</a></td>";
               $str.='<td align="right">'.number_format($data_provinsi['data_kpu'][21],0,',','.').'</td>';
               $str.='<td align="right" '.$p1color.'>'.number_format($p1,2,',','.').'</td>';
               $str.='<td align="right">'.number_format($data_provinsi['data_kpu'][22],0,',','.').'</td>';;
               $str.='<td align="right"'.$p2color.'>'.number_format($p2,2,',','.').'</td>';
               $str.='<td align="right">'.number_format($data_provinsi['data_kawal']['sum']['pas1'],0,',','.').'</td>';
               $str.='<td align="right"'.$p3color.'>'.number_format($p3,2,',','.').'</td>';
               $str.='<td align="right">'.number_format($data_provinsi['data_kawal']['sum']['pas2'],0,',','.').'</td>';;
               $str.='<td align="right"'.$p4color.'>'.number_format($p4,2,',','.').'</td>';
             $str.='</tr>'; 
             $i++;
           }
           
           $jml=$total1+$total2;
           $p1total = ($total1/$jml)*100;
           $p2total = ($total2/$jml)*100;
           $jml=$total3+$total4;
           $p3total = ($total3/$jml)*100;
           $p4total = ($total4/$jml)*100;

           $tmpstr='<tr>';
               $tmpstr.="<td colspan='2' >TOTAL</td>";               
               $tmpstr.='<td align="right">'.number_format($total1,0,',','.').'</td>';
               $tmpstr.='<td align="right">'.number_format($p1total,2,',','.').'</td>';
               $tmpstr.='<td align="right">'.number_format($total2,0,',','.').'</td>';;
               $tmpstr.='<td align="right">'.number_format($p2total,2,',','.').'</td>';
               $tmpstr.='<td align="right">'.number_format($total3,0,',','.').'</td>';
               $tmpstr.='<td align="right">'.number_format($p3total,2,',','.').'</td>';
               $tmpstr.='<td align="right">'.number_format($total4,0,',','.').'</td>';;
               $tmpstr.='<td align="right">'.number_format($p4total,2,',','.').'</td>';
             $tmpstr.='</tr>';             

           echo $tmpstr.$str;  

        ?>
    </table>
     <?php 
      echo $update_err;
    ?>
</body>
</html>