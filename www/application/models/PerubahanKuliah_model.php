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
     * @return JSON format dengan key 23 tahun/hari/jam sebelumnya dan value 
     * berupa JSONArray yang berisi count,requestDateTime,changeType
     * 
     */
    public function requestPerubahanKuliahStatistic(){
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->library('bluetape');
        $currentDateTime = strftime('%Y-%m-%d %H:%M:%S');
        $historyByYear = new Datetime($currentDateTime);
        $historyByYear->modify('-22 year');
        $historyByYear = $historyByYear->format('Y-01-01 00:00:00');
        $queryByYear = $this->db->select('COUNT(changeType) as "count", changeType,
            YEAR(requestDateTime) as "year"')
            ->group_by('year, changeType')
            ->order_by('year','ASC')
            ->order_by('changeType','DESC')
            ->where('requestDateTime >=',$historyByYear)
            ->get('perubahankuliah');

        $this->db->reset_query();
        $queryByDay = $this->db->select('COUNT(changeType) as "count",changeType, 
            DAYNAME(requestDateTime) as "day"')
            ->group_by('day, changeType')
            ->order_by('day','ASC')
            ->order_by('changeType','DESC')
            ->get('perubahankuliah');

        $historyByHour = new Datetime(strftime('%Y-%m-%d 00:00:%S'));
        $this->db->reset_query();
        $queryByHour = $this->db->select('COUNT(changeType) as "count",changeType,
            DATE_FORMAT(requestDateTime,"%H") as "jam"')
            ->group_by('jam, changeType')
            ->order_by('jam','ASC')
            ->order_by('changeType','DESC')
            ->get('perubahankuliah');

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
