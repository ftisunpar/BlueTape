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
	
	//method yang dipanggil ketika halaman EntriJadwalDosen dibuka
    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
        $dataJadwal = $this->JadwalDosen_model->getJadwalByUsername($userInfo['email']);
        $namaHari = $this->JadwalDosen_model->getNamaHari();
		$namaBulan = $this->JadwalDosen_model->getNamaBulan();
        $this->load->view('EntriJadwalDosen/main', array(
            'currentModule' => get_class(),
            'request_add_jadwal' => $this->session->userdata('request_add_jadwal'),
            'dataJadwal' => $dataJadwal,
            'namaHari' => $namaHari,
			'namaBulan'=> $namaBulan
        ));
    }

	//fungsi untuk menambah jadwal ke dalam database dari input user
    public function add() {
        $userInfo = $this->Auth_model->getUserInfo();
		
		$jam_mulai = $this->input->post('jam_mulai');
		$durasi = $this->input->post('durasi');
		$jam_akhir = $jam_mulai + $durasi ;
		$hari = $this->input->post('hari');
		$bisaMasuk = TRUE;
		for($i=7 ; $i<17 ; $i++){
			//memeriksa apakah ada jadwal lain di antara jam mulai dan jam akhir pada jadwal yang dimasukan oleh user
			$exist=$this->JadwalDosen_model->cekJadwalByJamMulai($i,$hari,$userInfo['email']);
			if($exist!=null){  //ada potensi jadwal bentrok
				$existJamAkhir = $exist[0]->jam_mulai+$exist[0]->durasi; //mendapatkan jam akhir dari jadwal yang berpotensi bentrok
				if(($exist[0]->jam_mulai<=$jam_mulai && $existJamAkhir>$jam_mulai) || ($exist[0]->jam_mulai<$jam_akhir && $existJamAkhir>=$jam_akhir)){
					$bisaMasuk = FALSE;
					break;
				}
			}
		}
		if($bisaMasuk){
			$data = array(
            'user' => $userInfo['email'],
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'durasi' => $this->input->post('durasi'),
            'jenis_jadwal' => $this->input->post('jenis_jadwal'),
            'label_jadwal' => $this->input->post('label_jadwal')
			);
			$this->JadwalDosen_model->addJadwal($data);
			header('Location: /EntriJadwalDosen');
		}
		else{
			$this->session->set_flashdata('error', 'Jadwal gagal dimasukan karena sudah ada jadwal pada waktu tersebut, silahkan pilih waktu lain.');
			header('Location: /EntriJadwalDosen');
		}
    }

	//fungsi untuk mengupdate jadwal di dalam database dari input user
    public function update($id_jadwal) {
        $userInfo = $this->Auth_model->getUserInfo();
		
		$jam_mulai = $this->input->post('jam_mulai');
		$durasi = $this->input->post('durasi');
		$jam_akhir = $jam_mulai + $durasi ;
		$hari = $this->input->post('hari');
		$bisaMasuk = TRUE;
		for($i=7 ; $i<17 ; $i++){
			//memeriksa apakah ada jadwal lain di antara jam mulai dan jam akhir pada jadwal yang dimasukan oleh user
			$exist=$this->JadwalDosen_model->cekJadwalByJamMulai($i,$hari,$userInfo['email']);
			if($exist!=null){  //ada potensi jadwal bentrok
				$existJamAkhir = $exist[0]->jam_mulai+$exist[0]->durasi; //mendapatkan jam akhir dari jadwal yang berpotensi bentrok
				if( ($exist[0]->jam_mulai<=$jam_mulai && $existJamAkhir>$jam_mulai) || ($exist[0]->jam_mulai<$jam_akhir && $existJamAkhir>=$jam_akhir)){ 
					if(strcmp($exist[0]->id,$id_jadwal)){ //memeriksa apakah jadwal tersebut merupakan dirinya sendiri atau bukan
						$bisaMasuk = FALSE;
						break;
					}
				}
			}
		}
		if($bisaMasuk){
			$data = array(
				'hari' => $this->input->post('hari'),
				'jam_mulai' => $this->input->post('jam_mulai'),
				'durasi' => $this->input->post('durasi'),
				'jenis' => $this->input->post('jenis_jadwal'),
				'label' => $this->input->post('label_jadwal'),
				'lastUpdate' => date('Y-m-d H:i:s')
			);
			$this->JadwalDosen_model->updateJadwal($id_jadwal, $data);
			header('Location: /EntriJadwalDosen');
		}
		else{
			$this->session->set_flashdata('error', 'Jadwal gagal di-update karena menimbulkan konflik dengan jadwal lain.');
			header('Location: /EntriJadwalDosen');
		}
    }
	
	//menghapus jadwal berdasarkan id jadwal yang dipilih user
	public function delete($id_jadwal) {
        $this->JadwalDosen_model->deleteJadwal($id_jadwal);
        header('Location: /EntriJadwalDosen');
    }
	
	//menghapus semua jadwal milik user
	public function deleteAll(){
		$userInfo = $this->Auth_model->getUserInfo();
		$this->JadwalDosen_model->deleteByUsername($userInfo['email']);
        header('Location: /EntriJadwalDosen');
	}

	//mendapatkan data-data milik user
    public function getDataJadwal($id_jadwal) {
        echo $this->JadwalDosen_model->getJadwalByIdJadwal($id_jadwal);
    }
}
