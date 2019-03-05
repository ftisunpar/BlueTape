<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestEmail extends CI_Controller{
  public function __construct(){
    parent::__construct();
    $this->load->config('auth');
    $config = $this->config->item('email-config');
      $this->load->library('email',$config);
    $this->load->model('Email_model' , 'emailmod');
      $this->load->library('unit_test');
  }

  private function report()
  {


      file_put_contents('../TestDocuments/TestPlan/TestEmail.html', $this->unit->report());

      // Output result to screen
      $statistics = [
          'Pass' => 0,
          'Fail' => 0
      ];
      $results = $this->unit->result();
      foreach ($results as $result) {


          foreach ($result as $key => $value) {
              echo "$key: $value\n";
          }
          echo "\n";
          if ($result['Result'] == 'Passed') {
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
      //$this->testSendEmail_notsend();
      $this->testSendEmail_DebugTrue();
      $this->report();

  }
  public function testSendEmail_notsend(){
      try{
          $testcase = $this->emailmod->send_email('7316081@student.unpar.ac.id' , 'test' , 'tis');
          $temp = "masuk pak eko";
      }
      catch(Exception $e){
          $temp = (string) $e->getMessage();

      }
      $ex =  "Maaf, gagal mengirim email notifikasi.";
      $this->unit->run($temp,$ex ,__FUNCTION__,'test email when it not send');


  }



  public function testSendEmail_DebugTrue(){
      try{
          
          $testcase = $this->emailmod->send_email('7316081@student.unpar.ac.id' , 'head' , 'what',true);
          echo "wo";

      }
      catch(Exception $e){
          $e->getMessage();
      }
      
      //$ex = 'message';
    // $toString = (string) $testcase;
   // $ex =  $this->expectOutputString('what');
    $this->unit->run($testcase,'what' ,__FUNCTION__,'test email when it not send');
  
     
  }

}
