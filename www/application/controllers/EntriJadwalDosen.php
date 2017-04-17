<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EntriJadwalDosen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('bluetape');
        $this->load->model('JadwalDosen_model');
		$this->load->model('Auth_model');
        $this->load->database();
    }

    public function index() {
         // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
		$dataJadwal = $this->JadwalDosen_model->getJadwalByUsername($userInfo['email']);
        $this->load->view('EntriJadwalDosen/main', array(
            'currentModule' => get_class(),
			'request_add_jadwal' => $this->session->userdata('request_add_jadwal'),
			'dataJadwal'	=> $dataJadwal
        ));
    }
	
	public function add() {
		$userInfo = $this->Auth_model->getUserInfo();
		$data = array(
			'user'	=> $userInfo['email'],
			'hari' => $this->input->post('hari'),
			'jam_mulai' => $this->input->post('jam_mulai'),
			'durasi' => $this->input->post('durasi'),
			'jenis_jadwal' => $this->input->post('jenis_jadwal'),
			'label_jadwal' => $this->input->post('label_jadwal')
		);
		//$this->session->set_userdata('request_add_jadwal',$data);
		$this->JadwalDosen_model->addJadwal($data);
		 header('Location: /EntriJadwalDosen');
	}
	
	public function update() {
		$userInfo = $this->Auth_model->getUserInfo();
		$id_jadwal_parameter = $this->input->post('id_jadwal_parameter');
		$data = array(
			'hari' => $this->input->post('hari'),
			'jam_mulai' => $this->input->post('jam_mulai'),
			'durasi' => $this->input->post('durasi'),
			'jenis' => $this->input->post('jenis_jadwal'),
			'label' => $this->input->post('label_jadwal')
		);
		$this->JadwalDosen_model->updateJadwal($id_jadwal_parameter, $data);
		 header('Location: /EntriJadwalDosen');
	}
	
	public function getDataJadwal($id_jadwal){
		echo $this->JadwalDosen_model->getJadwalByIdJadwal($id_jadwal);
	}
}
