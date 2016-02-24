<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BlueTape {

    public function emailToNPM($email, $default = NULL) {
        if (preg_match('/\\d{7}@student\\.unpar\\.ac\\.id/', $email)) {
            return '20' . substr($email, 2, 2) . substr($email, 0, 2) . '0' . substr($email, 4, 3);
        }
        return $default;
    }

}
