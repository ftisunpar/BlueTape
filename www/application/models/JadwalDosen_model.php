<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalDosen_model extends CI_Model {

	const DAY_NAME = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
	const MONTH_NAME = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
 
    public function addJadwal($data) {
        $this->user = $data['user'];
        $this->hari = $data['hari'];
        $this->jam_mulai = $data['jam_mulai'];
        $this->durasi = $data['durasi'];
        $this->jenis = $data['jenis_jadwal'];
        $this->label = $data['label_jadwal'];
		$this->lastUpdate=date('Y-m-d H:i:s');
        $this->db->insert('jadwal_dosen', $this);
    }


    public function getAllJadwal() {
        $query = $this->db->query('SELECT jadwal_dosen.*, bluetape_userinfo.name
			FROM jadwal_dosen
			INNER JOIN bluetape_userinfo ON jadwal_dosen.user=bluetape_userinfo.email');
        return $query->result();
    }

    public function getJadwalByUsername($user) {
        $query = $this->db->get_where('jadwal_dosen', array('user' => $user));
        return $query->result();
    }

    public function updateJadwal($id_jadwal, $data) {
       $this->db->where('id', $id_jadwal)->update('jadwal_dosen', $data);
    }
	
	public function deleteJadwal($id_jadwal) {
        $this->db->where('id',$id_jadwal)->delete('jadwal_dosen');
    }

    public function getNamaHari() {
        $this->load->library('bluetape');
        return array_values(Bluetape::DAY_NAME);
    }
	
	public function getNamaBulan() {
        return JadwalDosen_model::MONTH_NAME;
    }

    public function kolomKeHari($namaHari) {
        return strpos("BCDEF",$namaHari);
    }

    public function hariKeKolom($col) {
        return substr("BCDEF" , $col, 1);
    }
	
	public function deleteByUsername($username){
		$this->db->where('user',$username)->delete('jadwal_dosen');
	}
	
	public function cekJadwalByJamMulai($jam_mulai,$hari,$user){
		 $query = $this->db->get_where('jadwal_dosen', array('jam_mulai' => $jam_mulai, 'hari' =>$hari, 'user' =>$user ));
		 return $query->result();
		 
	}
}


