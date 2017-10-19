<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transkrip_model extends CI_Model {

    const REQUEST_TYPES = [
        'DPS_ID' => 'DPS Bahasa Indonesia (Seluruh Semester)',
        'DPS_EN' => 'DPS Bahasa Inggris (Seluruh Semester)',
        'LHS' => 'LHS (Semester Terakhir)',
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
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Mendapatkan request dari id tertentu
     * @param type $id id dari transkrip atau NULL untuk semua
     * @return object hasil dari $query->result()
     */
    public function requestByID($id, $rows = NULL, $start = NULL) {
        if ($id !== NULL) {
            $this->db->where('id', $id);
        }
        if ($start !== NULL && $rows !== NULL) {
            $this->db->limit($rows, $start);
        }
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        return $query->result()[0];
    }

    /**
     * Memeriksa jenis request apa saja yang diperbolehkan.
     * @param $requests array hasil dari requestsBy($email)
     * @return mixed array daftar requestType yang tidak diperbolehkan,
     * atau string berisi alasan kenapa tidak diperbolehkan jika semuanya tidak
     * diperbolehkan.
     */
    public function requestTypesForbidden($requests) {
        $this->load->library('bluetape');
        $date = getdate();
        $currentYear = $date['year'];
        $currentMonth = $date['mon'];
        $currentSemester = $this->bluetape->yearMonthToSemesterCodeSimplified($currentYear, $currentMonth);        
        $semesters = array();
        $forbiddenTypes = array();
        foreach ($requests as $request) {
            if ($request->answer === NULL) {
                return 'Anda tidak bisa meminta cetak karena ada permintaan lain yang belum selesai.';
            }
            $year = intval(substr($request->requestDateTime, 0, 4));
            $month = intval(substr($request->requestDateTime, 6, 2));
            if ($request->answer === 'printed' && $this->bluetape->yearMonthToSemesterCodeSimplified($year, $month) === $currentSemester) {
                $forbiddenTypes[$request->requestType] = TRUE;
            }
        }
        $forbiddenTypes = array_keys($forbiddenTypes);
        if (sizeof($forbiddenTypes) >= sizeof(Transkrip_model::REQUEST_TYPES)) {
            return 'Anda tidak bisa meminta cetak karena seluruh jenis transkrip sudah pernah dikabulkan di semester ini (' . $this->bluetape->semesterCodeToString($currentSemester) . ').';
        }
        return $forbiddenTypes;
    }

}
