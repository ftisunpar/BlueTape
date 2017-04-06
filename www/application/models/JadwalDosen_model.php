<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalDosen_model extends CI_Model {

    public $user;
	public $hari;
	public $jam_mulai;
	public $durasi;
	public $jenis;
	public $label;
	
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

    /**
     * Memeriksa jenis request apa saja yang diperbolehkan.
     * @param $requests array hasil dari requestsBy($email)
     * @return mixed array daftar requestType yang tidak diperbolehkan,
     * atau string berisi alasan kenapa tidak diperbolehkan jika semuanya tidak
     * diperbolehkan.
     */
    public function addJadwal($data) {
		$this->user = $data['user'];
		$this->hari = $data['hari'];
		$this->jam_mulai = $data['jam_mulai'];
		$this->durasi = $data['durasi'];
		$this->jenis = $data['jenis_jadwal'];
		$this->label = $data['label_jadwal'];
		
		$this->db->insert('jadwal', $this);
    }
	
	public function getAllJadwal(){
		$query = $this->db->get('jadwal', 10);
                return $query->result();
	}
	
	public function getJadwalByUsername($user){
		$query = $this->db->get_where('jadwal',array('user'=>$user));
                return $query->result();
	}
	
}
