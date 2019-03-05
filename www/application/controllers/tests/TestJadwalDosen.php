<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestJadwalDosen extends CI_Controller
{


    public $coverage;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('unit_test');
        $this->unit->use_strict(TRUE);
        $this->load->database();
        $this->load->model('JadwalDosen_model');

    }

    /**
     * Run all tests
     */
    public function index()
    {

        $this->testAll();

        $this->report();
    }

    public function testAll()
    {
        $this->testGetAllJadwal();
        $this->testGetJadwalByUserName();
        $this->testGetNamaHari();
        $this->testGetNamaBulan();
        $this->testKolomKeHari();
        $this->testHariKeKolom();
        $this->testCekJadwalByJamMulai();
        //$this->testRequestBy();
        $this->testAddJadwal();
        $this->testUpdateJadwal();
        $this->testDeleteJadwal();
        $this->testDeleteByUsername();

    }

    public function testGetAllJadwal()
    {
        $query = $this->db->query('SELECT jadwal_dosen.*, bluetape_userinfo.name
         FROM jadwal_dosen
         INNER JOIN bluetape_userinfo ON jadwal_dosen.user=bluetape_userinfo.email');

        $testCase = $this->JadwalDosen_model->getAllJadwal();
        $result = $query->result();
        $this->unit->run($testCase, $result, __FUNCTION__,"method untuk mendapatkan semua jadwal ");

    }

//still bugged

    public function testGetJadwalByUserName()
    {
        $user = 'jojo';
        $testCase = $this->JadwalDosen_model->getJadwalByUserName($user);
        $result = $this->getJadwalByUserName($user);
        $this->unit->run($testCase, $result, __FUNCTION__,"method untuk mendapatkan jadwal melalui username ");
    }

    public function getJadwalByUserName($user)
    {
        $query = $this->db->get_where('jadwal_dosen', array('user' => $user));
        return $query->result();
    }

    public function testGetNamaHari()
    {
        $testCase = $this->JadwalDosen_model->getNamaHari();
        $result = $this->getNamaHari();
        $this->unit->run($testCase, $result, __FUNCTION__,"method untuk mendapatkan nama hari");
    }

    public function getNamaHari()
    {
        return JadwalDosen_model::DAY_NAME;
    }

    public function testGetNamaBulan()
    {
        $testCase = $this->JadwalDosen_model->getNamaBulan();
        $result = $this->getNamaBulan();
        $this->unit->run($testCase, $result, __FUNCTION__,"method untuk mendapatkan nama bulan");
    }

    public function getNamaBulan()
    {
        return JadwalDosen_model::MONTH_NAME;
    }

    public function testKolomKeHari()
    {
        $testCase = $this->JadwalDosen_model->kolomKeHari("B");
        $result = 0;
        $this->unit->run($testCase, $result, __FUNCTION__,"hasil tes method untuk mengubah kolom ke hari");
    }

    public function testHariKeKolom()
    {
        $testCase = $this->JadwalDosen_model->hariKeKolom(1);
        $result = "C";
        $this->unit->run($testCase, $result, __FUNCTION__,"hasil tes method untuk mengubah hari ke kolom");
    }

    public function testCekJadwalByJamMulai()
    {
        $testCase = $this->JadwalDosen_model->cekJadwalByJamMulai(12, 1, 'jojo');
        $result = $this->cekJadwalByJamMulai(12, 1, 'jojo');
        $this->unit->run($testCase, $result, __FUNCTION__,"hasil tes method untuk mendapatkan jadwal menggunakan jam mulai");
    }

    public function cekJadwalByJamMulai($jam_mulai, $hari, $user)
    {
        $query = $this->db->get_where('jadwal_dosen', array('jam_mulai' => $jam_mulai, 'hari' => $hari, 'user' => $user));
        return $query->result();
    }

    public function testAddJadwal()
    {
        $user = 'jaki';
        $hari = 1;
        $jam_mulai = 1;
        $durasi = 1;
        $jenis = 'responsi';
        $label = 'entahlah';
        $lastUpdate = '2019-02-25 09:48:20';
        $data = array(
            'user' => $user,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'durasi' => $durasi,
            'jenis_jadwal' => $jenis,
            'label_jadwal' => $label,
            'lastupdate' => $lastUpdate
        );
        $this->JadwalDosen_model->addJadwal($data);

        $testCase = $this->db->affected_rows();
        $ex = 1;

        $this->unit->run($testCase, $ex, __FUNCTION__,"hasil tes method untuk menambah jadwal");
        $this->db->delete('jadwal_dosen', array('user' => 'jaki'));
    }

    private function report()
    {


        file_put_contents('../TestDocuments/TestPlan/TestJadwalDosen.html', $this->unit->report());


        // Output result to screen
        $statistics = [
            'Pass' => 0,
            'Fail' => 0
        ];
        $results = $this->unit->result();
        foreach ($results as $result) {


            foreach ($result as $key => $value) {
                echo "$key: $value\n";
            }
            echo "\n";
            if ($result['Result'] == 'Passed') {
                $statistics['Pass']++;
            } else {
                $statistics['Fail']++;
            }
        }
        echo "==========\n";
        foreach ($statistics as $key => $value) {
            echo "$value test(s) $key\n";
        }

        if ($statistics['Fail'] > 0) {
            exit(1);
        }
    }
// can't be tested
    public function testRequestBy()
    {

        $this->db->where('requestByEmail', 'fikrizzaki');
        $this->db->from('jadwal_dosen');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        $ex = $query->result();
        $testCase = $this->JadwalDosen_model->requestBy('fikrizzaki', null, null);
        $this->unit->run($testCase, $ex, __FUNCTION__);
    }

    public function kolomKeHari($namaHari)
    {
        return strpos("BCDEF", $namaHari);
    }

//still bugged on travis

    public function testUpdateJadwal()
    {
        $user = 'jaki';
        $hari = 1;
        $jam_mulai = 1;
        $durasi = 1;
        $jenis = 'responsi';
        $label = 'entahlah';
        $lastUpdate = '2019-02-25 09:48:20';

        $data = array(
            'user' => $user,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'durasi' => $durasi,
            'jenis_jadwal' => $jenis,
            'label_jadwal' => $label,
            'lastupdate' => $lastUpdate
        );

        $this->JadwalDosen_model->addJadwal($data);
        $insert_id = $this->db->insert_id();
        $newData = array(
            'user' => 'testcase',
        );
        $this->JadwalDosen_model->updateJadwal($insert_id, $newData);
        $this->db->where('id', $insert_id);
        $this->db->from('jadwal_dosen');
        $query = $this->db->get();
        $row = $query->row();
        $testCase = $row->user;
        $ex = 'testcase';
        $this->unit->run($testCase, $ex, __FUNCTION__,"hasil tes method untuk update jadwal");
        $this->db->delete('jadwal_dosen', array('id' => $insert_id));
    }

    public function testDeleteJadwal()
    {
        $user = 'jaki';
        $hari = 1;
        $jam_mulai = 1;
        $durasi = 1;
        $jenis = 'responsi';
        $label = 'entahlah';
        $lastUpdate = '2019-02-25 09:48:20';

        $data = array(
            'user' => $user,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'durasi' => $durasi,
            'jenis_jadwal' => $jenis,
            'label_jadwal' => $label,
            'lastupdate' => $lastUpdate
        );
        $this->JadwalDosen_model->addJadwal($data);
        $this->JadwalDosen_model->deleteByUsername('jaki');
        $testCase = $this->db->affected_rows();
        $ex = 1;

        $this->unit->run($testCase, $ex, __FUNCTION__,"hasil tes method untuk menghapus jadwal ");

    }

    public function testDeleteByUsername()
    {
        $user = 'jaki';
        $hari = 1;
        $jam_mulai = 1;
        $durasi = 1;
        $jenis = 'responsi';
        $label = 'entahlah';
        $lastUpdate = '2019-02-25 09:48:20';

        $data = array(
            'user' => $user,
            'hari' => $hari,
            'jam_mulai' => $jam_mulai,
            'durasi' => $durasi,
            'jenis_jadwal' => $jenis,
            'label_jadwal' => $label,
            'lastupdate' => $lastUpdate
        );
        $this->JadwalDosen_model->addJadwal($data);
        $insert_id = $this->db->insert_id();

        $this->JadwalDosen_model->deleteJadwal($insert_id);
        $testCase = $this->db->affected_rows();
        $ex = 1;

        $this->unit->run($testCase, $ex, __FUNCTION__,"hasil tes method untuk menghapus jadwal menggunakan username");

    }
}
