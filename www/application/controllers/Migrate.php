<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    /**
     * Jika dibuka di http://localhost/migrate akan mengupdate ke db terbaru.
     * Jika ada data sendiri, mohon dibackup dulu.
     */
    public function index() {
        $this->load->config('migration');
        if ($this->config->item('migration_enabled') === TRUE) {
            $this->load->library('migration');
            set_time_limit(300);
            $version = $this->migration->latest();
            if ($version === FALSE) {
                show_error($this->migration->error_string());
            } else {
                echo "Migrate success! DB Version: $version";
            }
        } else {
            echo 'Migration not enabled in config!';
        }
    }
}
