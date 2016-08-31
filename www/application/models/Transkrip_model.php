<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transkrip_model extends CI_Model {

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
     * Memeriksa apakah request transkrip diperbolehkan.
     * @param $requests array hasil dari requestsBy($email)
     * @return mixed boolean TRUE jika diperbolehkan, atau string berisi
     * alasan kenapa tidak diperbolehkan.
     */
    public function isRequestAllowed($requests) {
        $this->load->library('bluetape');
        $semesters = array();
        foreach ($requests as $request) {
            if ($request->answer === NULL) {
                return 'Anda tidak bisa meminta cetak karena ada permintaan lain yang belum selesai.';
            }
            $year = intval(substr($request->requestDateTime, 0, 4));
            $month = intval(substr($request->requestDateTime, 6, 2));
            if ($request->answer === 'printed') {
                $semesters[$this->bluetape->yearMonthToSemesterCodeSimplified($year, $month)] = TRUE;
            }
        }
        $date = getdate();
        $currentYear = $date['year'];
        $currentMonth = $date['mon'];
        $currentSemester = $this->bluetape->yearMonthToSemesterCodeSimplified($currentYear, $currentMonth);
        if (isset($semesters[$currentSemester])) {
            return 'Anda tidak bisa meminta cetak karena sudah pernah dikabulkan di semester ini (' . $this->bluetape->semesterCodeToString($currentSemester) . ').';
        }
        return TRUE;
    }

}
