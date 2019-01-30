<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
    }

    function  bagi ( $a , $b){
        return $a/$b;
    }

    public function index() {


      $expected = 2;
      $name = 'coba';
      $desc = 'percobaan';
      $tests = [
          'expected' => $expected,
          'result' => $this->unit->report(),
      ];
      foreach ($tests as $test ){
          if(is_array($test)){
              echo 'array';
          }
          else{
              echo 'not';
          }

        }
    }


}
