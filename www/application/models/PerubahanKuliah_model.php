<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PerubahanKuliah_model extends CI_Model {

    const CHANGETYPE_TYPES = [
        'G' => 'Diganti',
        'T' => 'Tambahan',
        'X' => 'Ditiadakan',
    ];
    
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
        $this->db->from('PerubahanKuliah');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
}
