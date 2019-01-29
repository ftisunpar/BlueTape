<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class unittest extends CI_Controller{

       public function __construct(){
            parent::__construct();
            $this->load->library('unit_test');
       }

       private function division($a,$b){
            return $a/$b;
       }

       public function index(){
           echo "COBA COBA TEST? ";

           $test=$this->division(6,2);
           $expected_result=3;
           $test_name="Divison 6: 3";

           echo $this->unit->run($test, $expected_result, $test_name);
       }

    }

?>
  