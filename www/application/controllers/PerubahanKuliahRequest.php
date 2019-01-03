<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PerubahanKuliahRequest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('BlueTape');
        $this->load->model('PerubahanKuliah_model');
        $this->load->database();
    }

    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
        // Retrieve requests for this user
        $requests = $this->PerubahanKuliah_model->requestsBy($userInfo['email']);
        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'TUNGGU';
                $request->labelClass = 'secondary';
            } else if ($request->answer === 'confirmed') {
                $request->status = 'TERKONFIRMASI';
                $request->labelClass = 'success';
            } else if ($request->answer === 'rejected') {
                $request->status = 'DITOLAK';
                $request->labelClass = 'alert';
            }
            $request->requestDateString = $this->bluetape->dbDateTimeToReadableDate($request->requestDateTime);
            $request->requestByName = $this->bluetape->getName($request->requestByEmail);
            $request->answeredDateString = $this->bluetape->dbDateTimeToReadableDate($request->answeredDateTime);
        }
        unset($request);

        $this->load->view('PerubahanKuliahRequest/main', array(
            'currentModule' => get_class(),
            'requestByEmail' => $userInfo['email'],
            'requestByName' => $userInfo['name'],
            'requests' => $requests,
        ));
    }

    public function add() {
        try {
            if ($this->input->server('REQUEST_METHOD') == 'POST'){
                date_default_timezone_set("Asia/Jakarta");
                $userInfo = $this->Auth_model->getUserInfo();
                $tos = [];
                $rooms = $this->input->post('toRoom');
                $dateTimes = $this->input->post('toDateTime');
                if ($rooms !== NULL && $dateTimes !== NULL) {
                    foreach ($rooms as $i => $room) {
                        $tos[] = [
                            'dateTime' => $dateTimes[$i] . ':00',
                            'room' => $room 
                        ];
                    }
                }
                $this->db->insert('PerubahanKuliah', array(
                    'requestByEmail' => $userInfo['email'],
                    'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
                    'mataKuliahName' => htmlspecialchars($this->input->post('mataKuliahName')),
                    'mataKuliahCode' => htmlspecialchars($this->input->post('mataKuliahCode')),
                    'class' => $this->input->post('class'),
                    'changeType' => $this->input->post('changeType'),
                    'fromDateTime' => $this->input->post('fromDateTime'),
                    'fromRoom' => htmlspecialchars($this->input->post('fromRoom')),
                    'to' => json_encode($tos),
                    'remarks' => htmlspecialchars($this->input->post('remarks')),
                ));
                $this->session->set_flashdata('info', 'Permohonan perubahan kuliah sudah dikirim. Silahkan cek statusnya secara berkala di situs ini.');

                $this->load->model('Email_model');
                $recipients = $this->config->item('roles')['tu.ftis'];
                if (is_array($recipients)) {
                    foreach ($recipients as $email) {
                        $requestByName = $this->bluetape->getName($userInfo['email']);
                        $subject = "Permohonan Perubahan Kuliah dari $requestByName";
                        $message = $this->load->view('PerubahanKuliahRequest/email', array(
                            'name' => $this->bluetape->getName($email),
                            'requestByName' => $requestByName
                        ), TRUE);
                        $this->Email_model->send_email($email, $subject, $message);
                    }
                }
            } else {
                throw new Exception("Can't call method from GET request!");
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        header('Location: /PerubahanKuliahRequest');
    }

}
