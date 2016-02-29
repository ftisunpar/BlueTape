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
        $this->load->library('bluetape');
        $this->load->model('Transkrip_model');
        $this->load->database();
    }

    public function index() {
        $requests = $this->Transkrip_model->requestsBy(NULL);
        foreach ($requests as &$request) {
            if ($request->answer === NULL) {
                $request->status = 'TUNGGU JAWABAN';
                $request->labelClass = 'warning';
            } else if ($request->answer === 'printed') {
                $request->status = 'SUDAH DICETAK';
                $request->labelClass = 'success';
                $request->answeredDateTime = substr($request->answeredDateTime, 0, 10);
            } else if ($request->answer === 'rejected') {
                $request->status = 'SUDAH DITOLAK';
                $request->labelClass = 'alert';
                $request->answeredDateTime = substr($request->answeredDateTime, 0, 10);
            }
            $request->requestDateTime = substr($request->requestDateTime, 0, 10);
        }
        unset($request);

        $this->load->view('TranskripManage/main', array(
            'currentModule' => get_class(),
            'requests' => $requests,
        ));
    }
}
