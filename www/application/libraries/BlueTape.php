<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BlueTape {
    
    const DAY_NAME = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu'
    ];

    public function getNPM($email, $default = NULL) {
        if (preg_match('/^\\d{7}@student\\.unpar\\.ac\\.id$/', $email)) {
            // Sebelum 2017
            return '20' . substr($email, 2, 2) . substr($email, 0, 2) . '0' . substr($email, 4, 3);
        } else if (preg_match('/^\\d{10}@student\\.unpar\\.ac\\.id$/', $email)) {
            // 2017 ke atas
            return substr($email, 0, 10);
        }
        return $default;
    }
    
    public function getEmail($npm, $default = NULL) {
        if (preg_match('/20\\d{8}/', $npm) && intval(substr($npm, 0, 4)) <= 2017) {
            // Sebelum 2017
            return substr($npm, 4, 2) . substr($npm, 2, 2) . substr($npm, 7, 3) . '@student.unpar.ac.id';
        } else {
            // 2017 ke atas
            return $npm . '@student.unpar.ac.id';
        }
        return $default;
    }
    
    public function getName($email, $default = NULL) {
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->where('email', $email);
        $CI->db->from('Bluetape_Userinfo');
        $query = $CI->db->get();
        $row = $query->row();
        if ($row === NULL) {
            return $default;
        } else {
            return $row->name;
        }
    }

    /**
     * Konversi tahun dan bulan ke kode semester
     * @param int $year tahun (20xx)
     * @param int $month bulan (1..12)
     */
    public function yearMonthToSemesterCode($year, $month) {
        if ($month >= 1 && $month <= 5) {
            $semester = 2;
        } else if ($month >= 8 && $month <= 12) {
            $semester = 1;
        } else {
            $semester = 4;
        }
        return substr($year, 2, 2) . $semester;
    }

    /**
     * Konversi tahun dan bulan ke kode semester, disederhanakan menjadi dua semester.
     * @param int $year tahun (20xx)
     * @param int $month bulan (1..12)
     */
    public function yearMonthToSemesterCodeSimplified($year, $month) {
        if ($month >= 1 && $month <= 6) {
            $semester = 2;
        } else {
            $semester = 1;
        }
        return substr($year, 2, 2) . $semester;
    }    
    
    /**
     * Konversi kode semester ke string (contoh: "141" menjadi "Padat 2014/2015")
     * @param string $semesterCode kode semester
     * @return representasi string atau FALSE jika gagal
     */
    public function semesterCodeToString($semesterCode) {
        $year = intval('20' . substr($semesterCode, 0, 2));
        switch (substr($semesterCode, 2, 1)) {
            case '1': return 'Ganjil ' . $year . '/' . ($year + 1);
            case '2': return 'Genap ' . ($year - 1) . '/' . $year;
            case '4': return 'Padat ' . ($year - 1). '/' . $year;
        }
        return FALSE;
    }
    
    /**
     * Konversi datetime dari DB 'YYYY-MM-DD HH:MM:SS' ke format yang dapat
     * dibaca manusia.
     * @param string $dbDateTime datetime dalam format DB.
     * @return string format baru atau NULL jika parameter NULL
     */
    public function dbDateTimeToReadableDate($dbDateTime) {
        setlocale(LC_TIME, 'ind');
        return $dbDateTime === NULL ? NULL : strftime('%A, %#d %B %Y', (new DateTime($dbDateTime))->getTimestamp());
    }    
}
