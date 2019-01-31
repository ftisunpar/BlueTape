<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class unittest extends CI_Controller{

       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
            $this->load->library('BlueTape');
            $this->load->database();
       }

       private function division($a,$b){
            return $a/$b;
       }

       public function index(){
         $this->test();
         $this->testBlueTapeGetNPM();
        //  $this->testDb();
       }

       public function test(){
        echo "COBA COBA TEST? ";
         $test=$this->division(6,2);
         $expected_result=3;
         $test_name="Divison 6: 3";
         echo $this->unit->run($test, $expected_result, $test_name);
       }

       public function testBlueTapeGetNPM(){
        echo $this->unit->run(
            $this->bluetape->getNPM('7313013@student.unpar.ac.id'),
            '2013730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan < 2017'
        );
       }

    //    public function testDb(){


    //    }

    }
?>
  