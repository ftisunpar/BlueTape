<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalDosen_model extends CI_Model {

	const DAY_NAME = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
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
        $this->db->from('jadwal');
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

        $this->db->insert('jadwal', $this);
    }


    public function getAllJadwal() {
        $query = $this->db->query('SELECT jadwal.*, bluetape_userinfo.name
			FROM jadwal
			INNER JOIN bluetape_userinfo ON jadwal.user=bluetape_userinfo.email');
        return $query->result();
    }

    public function getJadwalByUsername($user) {
        $query = $this->db->get_where('jadwal', array('user' => $user));
        return $query->result();
    }

    public function updateJadwal($id_jadwal, $data) {
       $this->db->where('id', $id_jadwal)->update('jadwal', $data);
    }
	
	public function deleteJadwal($id_jadwal) {
        $this->db->where('id',$id_jadwal)->delete('jadwal');
    }

    public function getNamaHari() {
        return JadwalDosen_model::DAY_NAME;
    }

    public function kolomKeHari($namaHari) {
        return strpos("BCDEF",$namaHari);
    }

    public function hariKeKolom($col) {
        return substr("BCDEF" , $col, 1);
    }

}


