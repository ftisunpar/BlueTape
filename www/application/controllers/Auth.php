<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }
    
    public function index() {
        $authURL = $this->Auth_model->createAuthURL();
        $this->load->view('auth/login', array('authURL' => $authURL));
    }

    public function oauth2callback() {
        try {
            $code = $this->input->get('code');
            if ($code !== NULL) {
                $this->Auth_model->authenticateOauthCode($code);
                $userInfo = $this->Auth_model->getUserInfo();
                echo json_encode($userInfo);
            } else {
                throw new Exception("Parameter code tidak ada, mohon mengikuti flow login yang benar.");
            }
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header("Location: /");
            return;
        }
    }

    public function logout() {
        $this->Auth_model->logout();
        $this->session->set_flashdata('info', 'Anda berhasil logout');
        header("Location: /");
    }

}
