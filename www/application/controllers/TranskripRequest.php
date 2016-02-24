<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TranskripRequest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->load->library('bluetape');
        $this->load->database();
    }

    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
        // Retrieve requests for this user
        $this->db->where('requestByEmail', $userInfo['email']);
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        $requests = $query->result();
        $disallowSubmit = NULL; // TODO use model, checked on "add" too.
        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'Tunggu';                
                $request->labelClass = 'secondary';                
                $request->answeredDateTime = '-';
                $request->answeredMessage = '-';
                $disallowSubmit = 'Anda tidak bisa meminta cetak sebelum permintaan terakhir dijawab.';
            } else if ($request->answer === 'printed') {
                $request->status = 'Tercetak';
                $request->labelClass = 'success';
            } else if ($request->answer === 'rejected') {
                $request->status = 'Ditolak';
                $request->labelClass = 'alert';
            }
        }
        unset($request);

        $this->load->view('TranskripRequest/main', array(
            'currentModule' => get_class(),
            'requestByEmail' => $userInfo['email'],
            'requestByNPM' => $this->bluetape->emailToNPM($userInfo['email'], '(bukan mahasiswa)'),
            'requestByName' => $userInfo['name'],
            'requests' => $requests,
            'disallowSubmit' => $disallowSubmit
        ));
    }

    public function add() {
        try {
            date_default_timezone_set("Asia/Jakarta");
            $userInfo = $this->Auth_model->getUserInfo();
            $this->db->insert('Transkrip', array(
                'requestByEmail' => $userInfo['email'],
                'requestByName' => $userInfo['name'],
                'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
                'requestUsage' => $this->input->post('requestUsage')
            ));
            $this->session->set_flashdata('info', 'Permintaan cetak transkrip sudah dikirim. Mohon tunggu satu hari kerja, dan cek kembali statusnya di sini.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e);
        }
        header('Location: /TranskripRequest');
    }

}
