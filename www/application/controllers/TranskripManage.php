<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TranskripManage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('BlueTape');
        $this->load->model('Transkrip_model');
        $this->load->database();
    }

    public function index() {
        $this->load->config('transkrip');

        $npmQuery = $this->input->get('npm');
        if ($npmQuery === NULL) {
            // Paging
            $rowsPerPage = $this->config->item('rowsPerPage');
            $numberOfPages = intval(ceil($this->db->count_all('Transkrip') / $rowsPerPage));
            $page = $this->input->get('page');
            if ($page === NULL) {
                $page = 1;
            }
            $page = intval($page);
            $startPage = max($page - 5, 1);
            $endPage = min($page + 5, $numberOfPages);
            $requests = $this->Transkrip_model->requestsBy(NULL, $rowsPerPage, (($page - 1) * $rowsPerPage));
        } else {
            $requests = $this->Transkrip_model->requestsBy($this->bluetape->getEmail($npmQuery));
            $page = $numberOfPages = $startPage = $endPage = 1;
        }
        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'MENUNGGU';
                $request->labelClass = 'warning';
            } else if ($request->answer === 'printed') {
                $request->status = 'TERCETAK';
                $request->labelClass = 'success';
            } else if ($request->answer === 'rejected') {
                $request->status = 'DITOLAK';
                $request->labelClass = 'alert';
            }
            $request->requestByName = $this->bluetape->getName($request->requestByEmail, '(belum tersedia)');
            $request->requestByNPM = $this->bluetape->getNPM($request->requestByEmail);
            $request->requestDateString = $this->bluetape->dbDateTimeToReadableDate($request->requestDateTime);
            $request->answeredDateString = $this->bluetape->dbDateTimeToReadableDate($request->answeredDateTime);
        }
        unset($request);

        $userInfo = $this->Auth_model->getUserInfo();
        $this->load->view('TranskripManage/main', array(
            'answeredByEmail' => $userInfo['email'],
            'currentModule' => get_class(),
            'requests' => $requests,
            'transkripURLs' => $this->config->item('url'),
            'page' => $page,
            'numOfPages' => $numberOfPages,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'npmQuery' => $npmQuery
        ));
    }

    public function answer() {
        date_default_timezone_set("Asia/Jakarta");
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST'){
                $userInfo = $this->Auth_model->getUserInfo();
                $this->db->where('id', $this->input->post('id'));
                $this->db->update('Transkrip', array(
                    'answer' => htmlspecialchars($this->input->post('answer')),
                    'answeredByEmail' => $userInfo['email'],
                    'answeredDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
                    'answeredMessage' => htmlspecialchars($this->input->post('answeredMessage'))
                ));
                
                $this->load->model('Email_model');
                
                $transcriptRequest = $this->Transkrip_model->requestByID($this->input->post('id'));
                $subject = "Tanggapan Permohonan Pencetakan Transkrip anda (#" . $this->input->post('id') . ")";
                $message = $this->load->view('TranskripManage/email', array(
                    'name' => $this->bluetape->getName($transcriptRequest->requestByEmail),
                    'requestByName' => $transcriptRequest->requestByEmail
                ), TRUE);
                
                $this->Email_model->send_email($transcriptRequest->requestByEmail, $subject, $message);
                
                $this->session->set_flashdata('info', 'Permintaan cetak transkrip sudah dijawab.');
            } else {
                throw new Exception("Can't call method from GET request!");
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /TranskripManage');
    }

    public function remove() {
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST'){
                $id = $this->input->post('id');
                $this->db->where('id', $id);
                $this->db->delete('Transkrip');
                $this->session->set_flashdata('info', "Permohonan #$id telah dihapus.");
            } else {
                throw new Exception("Can't call method from GET request!");
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /TranskripManage');
    }
}
