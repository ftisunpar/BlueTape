<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestAuth extends CI_Controller {
    public $coverage;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('auth');
        $this->load->config('modules');
        $this->load->library('unit_test');
        $this->load->model('Auth_model', 'Auth');
        $this->load->database();
        $this->load->dbforge();
        $this->load->library('session') ;
        
        $this->client = new Google_Client();
        $this->client->setClientId($this->config->item('755661919348-3b2u44e804efh2mghpadttnqh3u4ujd9.apps.googleusercontent.com'));
        $this->client->setClientSecret($this->config->item('4dAVtOJPlTaFEkm3RbwBY7Vw'));
        $this->client->setRedirectUri($this->config->item('http://localhost/auth/oauth2callback'));
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.profile');
    
        $role = array(
            'mahasiswa-informatika' => '7316057@student.unpar.ac.id',
        ) ;


        $this->session->set_userdata('auth', array(
            'email' => '7316057@student.unpar.ac.id',
            'name' => 'CHRISSANDI SUTRISNO',
            'roles' => $role,
            'modules' => array()
        )) ;
    }

    /**
     * Run all tests
     */
    public function index()
    {
        $this->testAll();


        $this->report();
    }

    private function report()
    {


        file_put_contents('../TestDocuments/TestPlan/test_Library.html', $this->unit->report());

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

    function TestCreateAuthURL() {
        $testCase = $this->Auth->createAuthURL() ;
        $ex = "https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2Fauth%2Foauth2callback&client_id=755661919348-3b2u44e804efh2mghpadttnqh3u4ujd9.apps.googleusercontent.com&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&access_type=online&approval_prompt=auto";
        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengetes createAuthURL") ;
    }

    function testGetUserInfo() {

        $testCase = $this->Auth->getUserInfo() ;
        $ex =  $this->session->userdata('auth');
        //var_dump($ex);
        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengecek getUserInfo") ;
    }
    
    function testLogout() {
        $testCase = $this->Auth->logout() ;
        $ex =  $this->session->unset_userdata('auth');
        
        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengecek logout") ;
    }
    
    function testAll() {
        $this->TestCreateAuthURL() ;
        $this->testGetUserInfo() ;
        $this-> testLogout() ;
    }
    

}