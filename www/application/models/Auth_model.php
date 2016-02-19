<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    private $client;

    public function __construct() {
        parent::__construct();

        require_once 'application/libraries/ext/google-api-php-client/src/Google/autoload.php';
        $this->load->config('auth');
        $this->load->library('session');
        $this->client = new Google_Client();
        $this->client->setClientId($this->config->item('google-clientid'));
        $this->client->setClientSecret($this->config->item('google-clientsecret'));
        $this->client->setRedirectUri($this->config->item('google-redirecturi'));
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');
    }

    /**
     * Panggil method ini untuk mendapatkan hyperlink untuk melakukan OAuth.
     * Biasanya digunakan saat akan menampilkan halaman "Login with Google".
     * @return string URL untuk login
     */
    public function createAuthURL() {
        return $this->client->createAuthUrl();
    }

    /**
     * Panggil ini untuk menerima kode autentikasi hasil redirect dari Google,
     * dan menentukan email dan role user yang berhasil login.
     * @param string $oauthCode kode oauth, didapat dari parameter GET "code".
     * @return boolean TRUE selalu.
     * @throws Exception jika autentikasi gagal (ditolak). Exception message
     * berisi penjelasan kenapa.
     */
    public function authenticateOauthCode($oauthCode) {
        $this->client->authenticate($oauthCode);
        $oauth2Service = new Google_Service_Oauth2($this->client);
        $userinfo = $oauth2Service->userinfo->get();
        $email = $userinfo['email'];
        
        $roles = array();
        foreach ($this->config->item('roles') as $role => $pattern) {
            if (preg_match("/$pattern/", $email)) {
                $roles[] = $role;
            }
        }
        if (sizeof($roles) === 0) {
            throw new Exception("Email $email tidak memiliki hak akses!");
        }
        $this->session->set_userdata('roles', $roles);
    }
    
    /**
     * Melakukan logout
     */
    public function logout() {
        $this->session->unset_userdata('roles');
    }

}
