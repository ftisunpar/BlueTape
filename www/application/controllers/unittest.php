<?php
    defined('BASEPATH') OR exit('No direct script access allowed');


    class UnitTest extends CI_Controller{

       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('BlueTape');
            $this->load->model('Transkrip_model');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
       }
       public function index(){
         $this->cekAddjadwal();
         print_r($this->unit->result());
       }

    public function cekAddjadwal(){
       $jenis='Praktek';
       $data=array("user"=>"Samuel", "hari"=>"3", "jam_mulai"=>"8","durasi"=>"2","jenis_jadwal"=>"Praktek","label_jadwal"=>"Mantap");
       $query=$this->db->query("SELECT * from jadwal_dosen");
       $res=$query->result();
       $jumlahAwal=sizeof($res);
    //    echo "jumlah awal $jumlahAwal";

       $this->JadwalDosen_model->addJadwal($data);

       $query2=$this->db->query("SELECT * from jadwal_dosen");
       $res2=$query2->result();
       $jumlahAkhir=sizeof($res2);
    //    echo "jumlah Akhir $jumlahAkhir";

         $this->unit->run(
           $jumlahAkhir,
           $jumlahAwal+1,
            __FUNCTION__,
            'Test ini mengecek apakah data masuk atau tidak'
         );
    }
}


  