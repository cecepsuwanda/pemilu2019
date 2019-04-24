<?php
   
  require_once "proses.php";
  set_time_limit(0);
  
  $kode_provinsi=$_GET['p1'];
  $kode_kabkota=$_GET['p2'];
  $kode_kec=$_GET['p3'];

 $proses = new proses();
 $proses->insert_kelurahan($kode_provinsi,$kode_kabkota,$kode_kec);
 $proses->update_kelurahan($kode_provinsi,$kode_kabkota,$kode_kec); 
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pantau Progress Pilpres</title>
</head>
<body>
    <a href="index.php">Provinsi</a>-><a href="kabkota.php?p1=<?php echo $kode_provinsi; ?>">KabKota</a>-><a href="kec.php?p1=<?php echo $kode_provinsi; ?>&p2=<?php echo $kode_kabkota; ?>">Kecamatan</a>
    <table border="1" width="80%">       
       <tr>
           <th rowspan="3" >No</th>
           <th rowspan="3">Kelurahan</th>
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



           $data_provinsis = $db_pemilu->get_kelurahan(['kode_provinsi'=>$kode_provinsi,'kode_kabkota'=>$kode_kabkota,'kode_kec'=>$kode_kec],[]);
          
           $i=1;
           $str='';
           $total1=0;
           $total2=0;
           $total3=0;
           $total4=0;
           foreach ($data_provinsis as $data_provinsi) {             
              
             if(isset($data_provinsi['data_kpu'])){
                $jml1=$data_provinsi['data_kpu'][21]+$data_provinsi['data_kpu'][22];
                if($jml1!=0){
                  $p1 = ($data_provinsi['data_kpu'][21]/$jml1)*100;
                  $p2 = ($data_provinsi['data_kpu'][22]/$jml1)*100;
                  $p1color = ($p1>=50 ? 'bgcolor="#00FF00"' : '');
                  $p2color = ($p2>=50 ? 'bgcolor="#00FF00"' : '');
                }
                $total1+=$data_provinsi['data_kpu'][21];
                $total2+=$data_provinsi['data_kpu'][22];
                
             }   

             

             if(isset($data_provinsi['data_kawal']['sum'])){
                $jml2=$data_provinsi['data_kawal']['sum']['pas1']+$data_provinsi['data_kawal']['sum']['pas2'];
                if($jml2!=0){  
                  $p3 = ($data_provinsi['data_kawal']['sum']['pas1']/$jml2)*100;
                  $p4 = ($data_provinsi['data_kawal']['sum']['pas2']/$jml2)*100;
                  $p3color = ($p3>=50 ? 'bgcolor="#FF0000"' : '');
                  $p4color = ($p4>=50 ? 'bgcolor="#FF0000"' : '');
                }
                $total3+=$data_provinsi['data_kawal']['sum']['pas1'];
                $total4+=$data_provinsi['data_kawal']['sum']['pas2'];
                
              }  
             
             

             $str.='<tr>';
               $str.="<td>$i</td>";               
               $str.="<td><a href='tps.php?p1=$data_provinsi[kode_provinsi]&p2=$data_provinsi[kode_kabkota]&p3=$data_provinsi[kode_kec]&p4=$data_provinsi[kode]'>$data_provinsi[nama]</a></td>";
               if(isset($data_provinsi['data_kpu']) and ($jml1!=0)){  
                 $str.='<td align="right">'.number_format($data_provinsi['data_kpu'][21],0,',','.').'</td>';
                 $str.='<td align="right" '.$p1color.'>'.number_format($p1,2,',','.').'</td>';
                 $str.='<td align="right">'.number_format($data_provinsi['data_kpu'][22],0,',','.').'</td>';;
                 $str.='<td align="right"'.$p2color.'>'.number_format($p2,2,',','.').'</td>';
               }else{
                  $str.='<td colspan="4" align="center" >Tidak Ada Data</td>';  
               }

               if(isset($data_provinsi['data_kawal']['sum'])and ($jml2!=0)){
                 $str.='<td align="right">'.number_format($data_provinsi['data_kawal']['sum']['pas1'],0,',','.').'</td>';
                 $str.='<td align="right"'.$p3color.'>'.number_format($p3,2,',','.').'</td>';
                 $str.='<td align="right">'.number_format($data_provinsi['data_kawal']['sum']['pas2'],0,',','.').'</td>';;
                 $str.='<td align="right"'.$p4color.'>'.number_format($p4,2,',','.').'</td>';
               }else{
                 $str.='<td colspan="4" align="center" >Tidak Ada Data</td>'; 
               }
             $str.='</tr>'; 
             $i++;
           }
           
           $p1total=0;
           $p2total=0;
           $p3total=0;
           $p4total=0;
           $jml1=$total1+$total2;
           if($jml1!=0){
               $p1total = ($total1/$jml1)*100;
               $p2total = ($total2/$jml1)*100;
           }
           $jml2=$total3+$total4;
           if($jml2!=0){
               $p3total = ($total3/$jml2)*100;
               $p4total = ($total4/$jml2)*100;
            }

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

</body>
</html>