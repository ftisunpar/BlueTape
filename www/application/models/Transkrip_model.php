<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transkrip_model extends CI_Model {

    const REQUEST_TYPES = [
        'DPS' => 'DPS (Seluruh Semester, Bilingual)',
    ];
    
    const DAY_NAME = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
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
     * @return JSON format yang dibagi berdasarkan tahun/hari/jam
     * dengan key -23 tahun/hari/jam sebelumnya dan value 
     * berupa JSONArray yang berisi count,requestDateTime,answer
     * 
     */
    public function requestTranskripStatistic(){
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->library('bluetape');
        $currentDateTime = strftime('%Y-%m-%d %H:%M:%S');
        $historyByYear = new Datetime($currentDateTime);
        $historyByYear->modify('-22 year');        
        $historyByYear = $historyByYear->format('Y-01-01 00:00:00');
        $queryByYear = $this->db->select('COUNT(answer) as "count",answer,
            YEAR(requestDateTime) as "year"')            
            ->group_by('year, answer')
            ->order_by('year','ASC')
            ->order_by('answer','DESC')            
            ->where('requestDateTime >="'.$historyByYear.'" AND answer IS NOT NULL')
            ->get('transkrip');
        
        $this->db->reset_query();
        $queryByDay = $this->db->select('COUNT(answer) as "count",answer,
            DAYNAME(requestDateTime) as "day"')            
            ->group_by('day, answer')
            ->order_by('day','ASC')
            ->order_by('answer','DESC')
            ->where('answer IS NOT NULL')
            ->get('transkrip');

        $historyByHour = new Datetime(strftime('%Y-%m-%d 00:00:%S'));
        $this->db->reset_query();
        $queryByHour = $this->db->select('COUNT(answer) as "count",answer,
            DATE_FORMAT(requestDateTime,"%H") as "jam"')        
            ->where('answer IS NOT NULL')
            ->group_by('jam, answer')
            ->order_by('jam','ASC')
            ->order_by('answer','DESC')
            ->get('transkrip');

        $requestByYear = [];    
        $requestByDay = [];
        $requestByHour=[];        
        $historyByYear = new Datetime($historyByYear);   
        $result = json_encode(array(
            "requestByYear" => $requestByYear,
            "requestByDay" => $requestByDay,
            "requestByHour" => $requestByHour,
            "startingYear" => '',
            "endYear" => ''
        )); 
          
        if(count($queryByYear->result())!=0 && count($queryByDay->result())!=0 && count($queryByHour->result())!=0){
            $startingYear =  $queryByYear->result()[0]->year - $historyByYear->format('Y');
            $endYear = $queryByYear->result()[count($queryByYear->result())-1]->year;
            if($startingYear > 0){
                $historyByYear->modify('+'.$startingYear.' year');
                $startingYear = $queryByYear->result()[0]->year;
            }
            else{
                $startingYear = $historyByYear->format('Y');
            }

            for($i=$startingYear;$i<$endYear;$i++){            
                $requestByYear[$historyByYear->format('Y')]=[];
                $historyByYear->modify('+1 year');
            }
            foreach(Bluetape::DAY_NAME as $dayName){
                $requestByDay[$dayName]=[];
            }
            for($i=0;$i<24;$i++){
                $requestByHour[$historyByHour->format('H:i')]=[];
                $historyByHour->modify('+1 hour');
            }

            foreach($queryByYear->result() as $row){
                $requestByYear[$row->year][] = $row;
            }
            foreach($queryByDay->result() as $row){
                $requestByDay[Bluetape::DAY_NAME[$row->day]][] = $row;
            }
            foreach($queryByHour->result() as $row){
                $requestByHour[$row->jam.':00'][]=$row;
            }
            $result = array(
                "requestByYear" => $requestByYear,
                "requestByDay" => $requestByDay,
                "requestByHour" => $requestByHour,            
                "startingYear" => $startingYear,
                "endYear" => $endYear
            );
            $result = json_encode($result);
        }
        
        return $result;
        
    }

}
