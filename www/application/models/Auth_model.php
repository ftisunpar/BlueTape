<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    private $client;

    public function __construct() {
        parent::__construct();

        $this->load->config('auth');
        $this->load->config('modules');
        $this->client = new Google_Client();
        $this->client->setClientId($this->config->item('google-clientid'));
        $this->client->setClientSecret($this->config->item('google-clientsecret'));
        $this->client->setRedirectUri($this->config->item('google-redirecturi'));
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.profile');
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
        $this->client->fetchAccessTokenWithAuthCode($oauthCode);
        $oauth2Service = new Google_Service_Oauth2($this->client);
        $userinfo = $oauth2Service->userinfo->get();
        $email = $userinfo['email'];
        $name = $userinfo['name'];

        $roles = array();
        foreach ($this->config->item('roles') as $role => $pattern) {
            $allowed = FALSE;
            switch (gettype($pattern)) {
                case 'string':
                    $allowed = preg_match("/$pattern/", $email);
                    break;
                case 'array':
                    $allowed = in_array($email, $pattern);
                    break;
            }
            if ($allowed) {
                $roles[] = $role;
            }
        }
        $modules = array();
        foreach ($this->config->item('modules') as $module => $module_roles) {
            $accessible = FALSE;
            foreach ($roles as $role) {              
                
                if (in_array($role, $module_roles)) {
                    $accessible = TRUE;
                }
            }
            if ($accessible) {
                $modules[] = $module;
            }
            
        }        
        if (sizeof($roles) === 0 || sizeof($modules) === 0) {
            throw new Exception("Email $email tidak memiliki hak akses!");
        }
        $this->load->database();
        $this->db->replace('Bluetape_Userinfo', array(
            'email' => $email,
            'name' => $name,
            'lastUpdate' => strftime('%Y-%m-%d %H:%M:%S')
        ));
        $this->session->set_userdata('auth', array(
            'email' => $email,
            'name' => $name,
            'roles' => $roles,
            'modules' => $modules
        ));
    }

    public function getUserInfo() {
        return $this->session->userdata('auth');
    }

    public function checkModuleAllowed($module) {
        $userInfo = $this->getUserInfo();
        if ($userInfo === NULL) {
            throw new Exception("Mohon login terlebih dahulu.");
        }
        if (!in_array($module, $this->session->userdata('auth')['modules'])) {
            throw new Exception($userInfo['email'] . " tidak memiliki hak akses ke $module");
        }
    }

    /**
     * Melakukan logout
     */
    public function logout() {
        $this->session->unset_userdata('auth');
    }

}
