<?php

         function jumlahkan($jml=array(),$color='bgcolor="#ffbf00"',&$total=array(0,0))
           {
             $total_jml = $jml[0]+$jml[1];
             $p=array(0,0);
             $pcolor = array('','');
             
             $str=''; 
	         if($total_jml>0){      
	               for ($i=0;$i<2;$i++) { 
	                  
	                  $str.='<td align="right">'.number_format($jml[$i],0,',','.').'</td>';
	                  if($total_jml>0){  
	                    $p[$i] = ($jml[$i]/$total_jml)*100;
	                    $pcolor[$i] = ($p[$i]>=50 ? $color : '');
	                  } 
	                  $str.='<td align="right" '.$pcolor[$i].'>'.number_format($p[$i],2,',','.').'</td>'; 
	                  $total[$i]+=$jml[$i];
	               }
            }else{
                 $str.='<td colspan="4" align="center" >Tidak Ada Data</td>';
             }    
               return $str;
           }

          function table_header($name='')
           {
           	  $str='<tr>
				           <th rowspan="3" >No</th>
				           <th rowspan="3">'.$name.'</th>
				           <th colspan="5">Data KPU</th>
				           <th colspan="5">Data KawalPemilu</th>           
				       </tr> 
				       <tr>
				         <th colspan="2"> Kandidat 1</th>
				         <th colspan="2"> Kandidat 2</th>
				         <th rowspan="2"> % suara masuk </th>
				         <th colspan="2"> Kandidat 1</th>
				         <th colspan="2"> Kandidat 2</th>
				         <th rowspan="2"> % suara masuk </th>
				       </tr>
				       <tr>
				         <th>Jumlah '.$name.'</th>
				         <th>%</th>
				         <th>Jumlah '.$name.'</th>
				         <th>%</th>
				         <th>Jumlah '.$name.'</th>
				         <th>%</th>
				         <th>Jumlah '.$name.'</th>
				         <th>%</th>
				       </tr>';
			 return $str; 	       
           }  
?>