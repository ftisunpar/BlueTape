<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PerubahanKuliahManage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('bluetape');
        $this->load->model('PerubahanKuliah_model');
        $this->load->database();
    }

    public function index() {
        $this->load->config('perubahankuliah');

        // Paging
        $rowsPerPage = $this->config->item('rowsPerPage');
        $numberOfPages = intval(ceil($this->db->count_all('PerubahanKuliah') / $rowsPerPage));
        $page = $this->input->get('page');
        if ($page === NULL || $page<1) {
            $page = 1;
        }
        $page = intval($page);
        $startPage = max($page - 5, 1);
        $endPage = min($page + 5, $numberOfPages);
        $requests = $this->PerubahanKuliah_model->requestsBy(NULL, $rowsPerPage, (($page - 1) * $rowsPerPage));

        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'MENUNGGU';
                $request->labelClass = 'warning';
            } else if ($request->answer === 'confirmed') {
                $request->status = 'TERKONFIRMASI';
                $request->labelClass = 'success';
            } else if ($request->answer === 'rejected') {
                $request->status = 'DITOLAK';
                $request->labelClass = 'danger';
            }
            $request->requestByName = $this->bluetape->getName($request->requestByEmail, '(belum tersedia)');
            $request->requestDateString = $this->bluetape->dbDateTimeToReadableDate($request->requestDateTime);
            $request->answeredDateString = $this->bluetape->dbDateTimeToReadableDate($request->answeredDateTime);
        }
        unset($request);

        $userInfo = $this->Auth_model->getUserInfo();
        $statistic = $this->PerubahanKuliah_model->requestPerubahanKuliahStatistic();
        $this->load->view('PerubahanKuliahManage/main', array(
            'answeredByEmail' => $userInfo['email'],
            'currentModule' => get_class(),
            'requests' => $requests,
            'page' => $page,
            'numOfPages' => $numberOfPages,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'statistic'=> json_decode($statistic)
        ));
    }

    public function answer() {
        date_default_timezone_set("Asia/Jakarta");
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST'){
                $userInfo = $this->Auth_model->getUserInfo();
                $this->db->where('id', $this->input->post('id'));
                $this->db->update('PerubahanKuliah', array(
                    'answer' => $this->input->post('answer'),
                    'answeredByEmail' => $userInfo['email'],
                    'answeredDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
                    'answeredMessage' => htmlspecialchars($this->input->post('answeredMessage'))
                ));

                $this->load->model('Email_model');
                $query = $this->db->get_where('PerubahanKuliah', ['id' => $this->input->post('id')]);
                $row = $query->row();
                $subject = "Status Permohonan Perubahan Kuliah Anda";
                $message = $this->load->view('PerubahanKuliahManage/email', array(
                    'answeredByName' => $this->bluetape->getName($userInfo['email']),
                    'requestByName' => $this->bluetape->getName($row->requestByEmail),
                    'id' => $row->id,
                    'mataKuliahCode' => $row->mataKuliahCode,
                    'mataKuliahName' => $row->mataKuliahName,
                    'answer' => $row->answer,
                    'answeredMessage' => $row->answeredMessage
                ), TRUE);
                $this->Email_model->send_email($row->requestByEmail, $subject, $message);
                
                $this->session->set_flashdata('info', 'Permintaan perubahan kuliah sudah dijawab dan email notifikasi sudah dikirimkan.');
            } else {
                throw new Exception("Can't call method from GET request!");
            }
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /PerubahanKuliahManage');
    }

    public function remove() {
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST'){
                $id = $this->input->post('id');
                $this->db->where('id', $id);
                $this->db->delete('PerubahanKuliah');
                $this->session->set_flashdata('info', "Permohonan #$id telah dihapus.");
            } else {
                throw new Exception("Can't call method from GET request!");
            }
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /PerubahanKuliahManage');
    }

    public function printview($id) {
        try {
            $query = $this->db->get_where('PerubahanKuliah', array('id' => $id));
            $result = $query->row();
            if ($result->fromDateTime !== null) {
                $result->fromDateTime = date_create_from_format('Y-m-d H:i:s', $result->fromDateTime)->getTimeStamp();
            }
            if ($result->to !== null) {
                $result->to = json_decode($result->to);
                foreach ($result->to as $to) {
                    $to->dateTime = date_create_from_format('Y-m-d H:i:s', $to->dateTime)->getTimeStamp();
                }
            }
            $this->load->view('PerubahanKuliahManage/printview', array(
                'perubahan' => $result
            ));
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
            header('Location: /PerubahanKuliahManage');
        }
    }

}
