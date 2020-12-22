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
    /**
     * Mendapatkan statistik yang dibagi berdasarkan tahun, hari, dan jam
     * @return JSON format dibagi berdasarkan tahun,bulan, dan jam
     * 
     */
    public function requestStatistic(){
        date_default_timezone_set("Asia/Jakarta"); 
        $currentDateTime = strftime('%Y-%m-%d %H:%M:%S');
        $historyByYear = new Datetime($currentDateTime);
        $historyByYear->modify('-22 year');
        $historyByYear = $historyByYear->format('Y-m-d H:i:s');
        $queryByYear = $this->db->select('COUNT(changeType) as "count", changeType,
            YEAR(requestDateTime) as "year"')
            ->group_by('YEAR(requestDateTime), changeType')
            ->order_by('YEAR(requestDateTime)','ASC')
            ->order_by('changeType','DESC')
            ->where('requestDateTime >=',$historyByYear)
            ->get('perubahankuliah');
        
        $historyByDay = new Datetime($currentDateTime);
        $historyByDay->modify('-22 day');
        $historyByDay = $historyByDay->format('Y-m-d H:i:s');
        $this->db->reset_query();
        $queryByDay = $this->db->select('COUNT(changeType) as "count",changeType, 
            DATE_FORMAT(requestDateTime,"%d-%m") as "day_month"')
            ->group_by('DAY(requestDateTime), changeType')
            ->order_by('DAY(requestDateTime)','ASC')
            ->order_by('changeType','DESC')
            ->where('requestDateTime >=',$historyByDay)
            ->get('perubahankuliah');
        $historyByHour = new Datetime($currentDateTime);
        $historyByHour->modify('-22 hour');
        $historyByHour = $historyByHour->format('Y-m-d H:i:s');
        $this->db->reset_query();
        $queryByHour = $this->db->select('COUNT(changeType) as "count",changeType,
            DATE_FORMAT(requestDateTime,"%H") as "jam"')
            ->group_by('DAY(requestDateTime), changeType')
            ->order_by('DAY(requestDateTime)','ASC')
            ->order_by('changeType','DESC')
            ->where('requestDateTime >=',$historyByHour)
            ->get('perubahankuliah');

        $requestByYear = [];    
        $requestByDay = [];
        $requestByHour=[];
        $historyByYear = new Datetime($historyByYear);        
        $historyByDay = new Datetime($historyByDay);
        $historyByHour = new Datetime($historyByHour);
        for($i=0;$i<23;$i++){
            $requestByYear[$historyByYear->format('Y')]=[];
            $historyByYear->modify('+1 year');
            $requestByDay[$historyByDay->format('d-m')]=[];            
            $historyByDay->modify('+1day');
            $requestByHour[$historyByHour->format('H')]=[];
            $historyByHour->modify('+1 hour');
        }
        foreach($queryByYear->result() as $row){
            $requestByYear[$row->year][] = $row;
        }
        foreach($queryByDay->result() as $row){
            $requestByDay[$row->day_month][] = $row;
        }
        foreach($queryByHour->result() as $row){
            $requestByHour[$row->jam][]=$row;
        }
        $result = array(
            "requestByYear" => $requestByYear,
            "requestByDay" => $requestByDay,
            "requestByHour" => $requestByHour
        );
        $result = json_encode($result);
        return $result;
    }
}
