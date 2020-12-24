<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transkrip_model extends CI_Model {

    const REQUEST_TYPES = [
        'DPS' => 'DPS (Seluruh Semester, Bilingual)',
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
    /** 
     * Mengambil statistik transkrip ditolak dan diterima berdasarkan tahun/hari/jam
     * 
     */
    public function requestTranskripStatistic(){
        date_default_timezone_set("Asia/Jakarta"); 
        $currentDateTime = strftime('%Y-%m-%d %H:%M:%S');
        $historyByYear = new Datetime($currentDateTime);
        $historyByDay = new Datetime($currentDateTime);
        $historyByHour = new Datetime($currentDateTime);
        $historyByYear->modify('-22 year');
        $historyByDay->modify('-22 day');
        $historyByHour->modify('-22 hour');

        $historyByYear= $historyByYear->format('Y-m-d H:i:s');
        $queryByYear = $this->db->select('COUNT(answer) as "count",answer,
            YEAR(requestDateTime) as "year"')            
            ->where('requestDateTime >= "'.$historyByYear.'" AND answer IS NOT NULL' )
            ->group_by('"year", answer')
            ->order_by('"year"','ASC')
            ->order_by('answer','DESC')
            ->get('transkrip');

        $historyByDay = $historyByDay->format('Y-m-d H:i:s');
        $queryByDay = $this->db->select('COUNT(answer) as "count",answer,
        DATE_FORMAT(requestDateTime,"%d-%m")  as "day_month"')            
            ->where('requestDateTime >= "'.$historyByDay.'" AND answer IS NOT NULL')
            ->group_by('day_month, answer')
            ->order_by('day_month','ASC')
            ->order_by('answer','DESC')
            ->get('transkrip');

        $historyByHour = $historyByHour->format('Y-m-d H:i:s');
        $queryByHour = $this->db->select('COUNT(answer) as "count",answer,
        DATE_FORMAT(requestDateTime,"%H") as "jam"')        
        ->where('requestDateTime >="'.$historyByHour.'" AND answer IS NOT NULL')
        ->group_by('jam, answer')
        ->order_by('jam','ASC')
        ->order_by('answer','DESC')
        ->get('transkrip');

        // foreach($queryByDay->result() as $row){
        //     echo json_encode($row).'<br>';
        // }
    }

}
