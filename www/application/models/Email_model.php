<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email_model extends CI_Model {

    public function send_email($email, $subject, $message, $debug = FALSE) {
        if ($debug === TRUE) {
            echo $message;
            exit();
        }
        $this->load->config('auth');
        $config = $this->config->item('email-config');
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");
        $this->email->from('no-reply@bluetape.azurewebsites.net', 'BlueTape');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        if (!$this->email->send()) {
            throw new Exception("Maaf, gagal mengirim email notifikasi.");
        }
    }

}
