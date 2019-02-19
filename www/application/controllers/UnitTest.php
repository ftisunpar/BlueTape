<?php
    defined('BASEPATH') OR exit('No direct script access allowed');


    class UnitTest extends CI_Controller{
      const ENABLE_COVERAGE = true; // Requires xdebug
      private $coverage;
       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('BlueTape');
            $this->load->model('Transkrip_model');
            $this->load->model('JadwalDosen_model');
            $this->load->database();
            $this->unit->use_strict(TRUE);
            if (self::ENABLE_COVERAGE) {
                $this->coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
                $this->coverage->filter()->addDirectoryToWhitelist('application/controllers');
                $this->coverage->filter()->removeDirectoryFromWhitelist('application/controllers/tests');
                $this->coverage->filter()->addDirectoryToWhitelist('application/libraries');
                $this->coverage->filter()->addDirectoryToWhitelist('application/models');
                $this->coverage->filter()->addDirectoryToWhitelist('application/views');
                $this->coverage->start('UnitTests');
            }
       }

       private function report() {
        if (self::ENABLE_COVERAGE) {
            $this->coverage->stop();        
            $writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
            $writer->process($this->coverage, '../reports/code-coverage');
        }
        // Generate Test Report HTML
        file_put_contents('../reports/test_report.html', $this->unit->report());
        // Output result to screen
        $statistics = [
            'Pass' => 0,
            'Fail' => 0
        ];
        $results = $this->unit->result();
        foreach ($results as $result) {
            echo "=== " . $result['Test Name'] . " ===\n";
            foreach ($result as $key => $value) {
                echo "$key: $value\n";
            }
            echo "\n";
            if ($result['Result'] === 'Passed') {
                $statistics['Pass']++;
            } else {
                $statistics['Fail']++;
            }
        }
        echo "==========\n";
        foreach ($statistics as $key => $value) {
            echo "$value test(s) $key\n";
        }
        if ($statistics['Fail'] > 0) {
            exit(1);
        }        
    }

       public function index(){
         $this->cekAddjadwal();
         $this->cekGetNpm();
         $this->cekYearMonthToSemesterCodeSimplified();
         $this->report();
         print_r($this->unit->result());
       }
    //Model -addJadwal
    public function cekAddjadwal(){
       $jenis='Praktek';
       $data=array("user"=>"gemini2911f665@gmail.com", "hari"=>"0", "jam_mulai"=>"7","durasi"=>"1","jenis_jadwal"=>"konsultasi","label_jadwal"=>"");
       $query=$this->db->query("SELECT * from jadwal_dosen");
       $res=$query->result();
       $jumlahAwal=sizeof($res);
       //echo "jumlah awal $jumlahAwal";

       $this->JadwalDosen_model->addJadwal($data);

       $query2=$this->db->query("SELECT * from jadwal_dosen");
       $res2=$query2->result();
       $jumlahAkhir=sizeof($res2);
       //echo "jumlah Akhir $jumlahAkhir";

          $this->unit->run(
           $jumlahAkhir,
           $jumlahAwal+1,
            __FUNCTION__,
            'Test ini mengecek apakah data masuk atau tidak'
         );
    }
    //Libraries-BlueTape
    public function cekGetNpm(){
      //test case 1
      $result= $this->bluetape->getNPM('7316054@student.unpar.ac.id');
      $expected='2016730054';
      $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");

      //test case 2
       $result= $this->bluetape->getNPM('7317004@student.unpar.ac.id');
      $expected='2017730004';
      $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");


      //test case 3 
       $result= $this->bluetape->getNPM('6181801025@student.unpar.ac.id');
      $expected='6181801025';
      $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek apakah NPM valid atau tidak");
    }

    //libraries-yearMonthToSemesterCodeSimplifeid
    public function cekYearMonthToSemesterCodeSimplified(){

      //test case 1
      $year=2019;
      $month=12;
       $result= $this->bluetape->yearMonthToSemesterCodeSimplified($year,$month);
      $expected='191';
     $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester");


      //test case 2
      $year2=2080;
      $month2=7;
       $result= $this->bluetape->yearMonthToSemesterCodeSimplified($year2,$month2);
      $expected='801';
       $this->unit->run($result,$expected,__FUNCTION__,"Test ini mengecek Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester");


    }


    
}


  
