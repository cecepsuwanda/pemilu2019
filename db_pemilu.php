<?php
require_once "mongodb_library.php";
/**
 * 
 */
class db_pemilu 
{
	private $tb_provinsi;
    private $tb_kabkota;
    private $tb_kec;
    private $tb_kelurahan;
    private $tb_tps;


	function __construct()
	{
		$this->tb_provinsi = new mongodb_library('kpu_pemilu2019','kd_provinsi');
		$this->tb_kabkota = new mongodb_library('kpu_pemilu2019','kd_kabkota');
		$this->tb_kec = new mongodb_library('kpu_pemilu2019','kd_kec');
		$this->tb_kelurahan = new mongodb_library('kpu_pemilu2019','kd_kelurahan');
		$this->tb_tps = new mongodb_library('kpu_pemilu2019','tps');
	}

	public function __call($name,$args)
	{
		$tmp = explode('_', $name);
        $nm = 'tb_'.$tmp[1];
        $hasil = array();

        if($tmp[0]=='get'){           
           
           $hasil=$this->$nm->find($args[0],$args[1])->toArray();

        }elseif($tmp[0]=='insert'){             
             $tmp_hasil=$this->$nm->find(array('kode'=>$args[0]['kode']),[])->toArray();            
             if(empty($tmp_hasil)){             	
               $hasil=$this->$nm->insertOne($args[0]);
             }	
        }elseif($tmp[0]=='update'){
        	$hasil=$this->$nm->updateMany($args[0],$args[1],$args[2]);
        }
        return $hasil;
	}
}

?>