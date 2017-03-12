<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LihatJadwalDosen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('bluetape');
        $this->load->model('Transkrip_model');
        $this->load->database();
    }

    public function index() {
	
         // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
          $this->load->view('LihatJadwalDosen/main', array(
            'currentModule' => get_class(),
			'request_add_jadwal' => $this->session->userdata('request_add_jadwal')
        ));
    }
}
