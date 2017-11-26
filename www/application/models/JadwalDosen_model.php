<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalDosen_model extends CI_Model {

	const DAY_NAME = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
	const MONTH_NAME = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    /**
     * Mendapatkan seluruh request dari email tertentu
     * @param type $email email yang melakukan request atau NULL untuk semua
     * @return array hasil dari $query->result()
     */
    public function requestsBy($email, $rows = NULL, $start = NULL) {
        if ($email !== NULL) {
            $this->db->where('requestByEmail', $email);
        }
        if ($start !== NULL && $rows !== NULL) {
            $this->db->limit($rows, $start);
        }
        $this->db->from('jadwal_dosen');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

	
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
        return JadwalDosen_model::DAY_NAME;
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


