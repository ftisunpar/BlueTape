<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class TestAll extends CI_Controller
{


    public $coverage;




    public function __construct()
    {
        parent::__construct();
        $this->load->library('unit_test');
        $this->coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
        $this->coverage->filter()->addDirectoryToWhitelist('application/libraries');
        $this->coverage->filter()->addDirectoryToWhitelist('application/models');
        $this->coverage->start('UnitTests');


        $this->load->model('JadwalDosen_model');
        $this->load->library('BlueTape');
        $this->load->config('auth');
        $this->load->config('modules');
        $this->load->library('unit_test');
        $this->load->model('Auth_model', 'Auth');
        $this->load->database();
        $this->load->dbforge();
        $this->load->library('session') ;
        $this->client = new Google_Client();
        $this->client->setClientId($this->config->item('755661919348-3b2u44e804efh2mghpadttnqh3u4ujd9.apps.googleusercontent.com'));
        $this->client->setClientSecret($this->config->item('4dAVtOJPlTaFEkm3RbwBY7Vw'));
        $this->client->setRedirectUri($this->config->item('http://localhost/auth/oauth2callback'));
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        $role = array(
            'mahasiswa-informatika' => '7316057@student.unpar.ac.id',
        ) ;


        $this->session->set_userdata('auth', array(
            'email' => '7316057@student.unpar.ac.id',
            'name' => 'CHRISSANDI SUTRISNO',
            'roles' => $role,
            'modules' => array()
        )) ;

        $this->load->model('Transkrip_model');
        $this->load->model('PerubahanKuliah_model');
        $this->load->database();
        $this->load->dbforge();
        $str = '
<table border="0"  cellpadding="4" cellspacing="1">

        <tr>
                <td></td>
                <td></td>
        </tr>

        <br>


</table>';


        $this->unit->set_template($str);


    }

    private function report()
    {

        $this->coverage->stop();
        $writer = new  \SebastianBergmann\CodeCoverage\Report\Html\Facade;
        $writer->process($this->coverage, '../TestDocuments/codecoverageAll');
        file_put_contents('../TestDocuments/TestPlan/TestAll.html', $this->unit->report());

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



        $this->testBlueTapeLibraryGetNPM();
        $this->testBlueTapeLibraryGetNPM_2017();
        $this->testBlueTapeLibraryGetNPM_Null();
        $this->testGetSemestergenap();
        $this->testGetSemesterSimplegenap();
        $this->testGetSemesterSimpleganjil();
        $this->testSmesterCodeToStringGanjil();
        $this->testSmesterCodeToStringGenap();
        $this->testSmesterCodeToStringPadat();
        $this->testGetSemesterganjil();
        $this->testGetName();
        $this->testGetSemester_pendek();
        $this->testGetEmailBawah();
        $this->testGetEmailAtas();
        $this->testSmesterCodeToStringFalse();
        $this->testGetAllJadwal();
        $this->testGetJadwalByUserName();
        $this->testGetNamaHari();
        $this->testGetNamaBulan();
        $this->testKolomKeHari();
        $this->testHariKeKolom();
        $this->testCekJadwalByJamMulai();
        // $this->testRequestBy();
        $this->testAddJadwal();
        $this->testUpdateJadwal();
        $this->testRequest();
        $this->testRequestByTranskrip();
        $this->testRequestByID();
        $this->TestCreateAuthURL() ;
        $this->testGetUserInfo() ;
        $this-> testLogout() ;


    }

    public function testBlueTapeLibraryGetNPM()
    {
        $this->unit->run(
            $this->bluetape->getNPM('7316081@student.unpar.ac.id'),
            '2016730081',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan <  2017'
        );
    }

    public function testBlueTapeLibraryGetNPM_2017()
    {
        $this->unit->run(
            $this->bluetape->getNPM('2017730013@student.unpar.ac.id'),
            '2017730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan >= 2017'
        );
    }

    public function testBlueTapeLibraryGetNPM_Null()
    {
        $this->unit->run(
            $this->bluetape->getNPM('fikrizzaki@gmail.com'),
            NULL,
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for dosen, etc'
        );
    }

    function testGetSemestergenap()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCode("2016", 1), "162", __FUNCTION__, "Untuk mengecek semester genap"

        );
    }

    function testGetSemesterSimplegenap()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCodeSimplified("2016", 1), "162", __FUNCTION__, "Untuk mengkonversi tahun dan bulan sekarang menjadi code smester sederhana (genap)"

        );
    }

    function testGetSemesterSimpleganjil()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCodeSimplified("2016", 8), "161", __FUNCTION__, "Untuk mengkonversi tahun dan bulan sekarang menjadi code smester sederhana (ganjil)"

        );
    }

    function testSmesterCodeToStringGanjil()
    {
        $this->unit->run(
            $this->bluetape->semesterCodeToString("141"), "Ganjil 2014/2015", __FUNCTION__, "mengubah smester Ganjil code menjadi string"
        );

    }

    function testSmesterCodeToStringGenap()
    {
        $this->unit->run(
            $this->bluetape->semesterCodeToString("152"), "Genap 2014/2015", __FUNCTION__, "mengubah smester Genap code menjadi string"
        );

    }

    function testSmesterCodeToStringPadat()
    {
        $this->unit->run(
            $this->bluetape->semesterCodeToString("164"), "Padat 2015/2016", __FUNCTION__, "mengubah smester Padat code menjadi string"
        );

    }

//still bugged

    function testGetSemesterganjil()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCode("2016", 9), "161", __FUNCTION__, "Untuk mengecek semester ganjil"

        );
    }

    function testGetName()
    {
        if (!$this->db->table_exists('bluetape_userinfo')) {
            $fields = array(
                'email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '128'
                ),
                'name' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '256'
                ),
                'lastUpdate' => array(
                    'type' => 'DATETIME'
                ),
            );
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('email', TRUE);
            $this->dbforge->create_table('bluetape_userinfo');

        }


        $data = array(
            'email' => '7316081@student.unpar.ac.id',
            'name' => 'JONATHAN LAKSAMANA PURNOMO',
            'lastUpdate' => '2019-02-25 09:48:20'
        );
        $this->db->insert('bluetape_userinfo', $data);

        $this->unit->run(
            $this->bluetape->getName("7316081@student.unpar.ac.id"), "JONATHAN LAKSAMANA PURNOMO", __FUNCTION__, "Untuk mendapatkan nama mahasiswa dari email"

        );
        $this->db->delete('bluetape_userinfo', array('email' => '7316081@student.unpar.ac.id'));
    }

    function testGetSemester_pendek()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCode("2016", 6), "164", __FUNCTION__, "Untuk mengecek semester pendek"

        );
    }

    function testGetEmailBawah()
    {
        $this->unit->run(
            $this->bluetape->getEmail("2016730025"), "7316025@student.unpar.ac.id", __FUNCTION__, "mendapatkan email dari npm mhs angkatan sebelum 2017"
        );
    }

    function testGetEmailAtas()
    {
        $this->unit->run(
            $this->bluetape->getEmail("2018730048"), "2018730048@student.unpar.ac.id", __FUNCTION__, "mendapatkan email dari npm mhs angkatan 2017 keatas"
        );
    }

    function testSmesterCodeToStringFalse()
    {
        $this->unit->run(
            $this->bluetape->semesterCodeToString("169"), FALSE, __FUNCTION__, "mengubah smester Padat code menjadi string"
        );

    }

    //EMAIL MHS SEBELUM 2017

    public function testGetAllJadwal()
    {
        $query = $this->db->query('SELECT jadwal_dosen.*, bluetape_userinfo.name
             FROM jadwal_dosen
             INNER JOIN bluetape_userinfo ON jadwal_dosen.user=bluetape_userinfo.email');

        $testCase = $this->JadwalDosen_model->getAllJadwal();
        $result = $query->result();
        $this->unit->run($testCase, $result, __FUNCTION__);

    }

    //EMAIL MHS 2017 KEATAS

    public function testGetJadwalByUserName()
    {
        $user = 'jojo';
        $testCase = $this->JadwalDosen_model->getJadwalByUserName($user);
        $result = $this->getJadwalByUserName($user);
        $this->unit->run($testCase, $result, __FUNCTION__);
    }

    //still bugged

    public function getJadwalByUserName($user)
    {
        $query = $this->db->get_where('jadwal_dosen', array('user' => $user));
        return $query->result();
    }

    public function testGetNamaHari()
    {
        $testCase = $this->JadwalDosen_model->getNamaHari();
        $result = $this->getNamaHari();
        $this->unit->run($testCase, $result, __FUNCTION__);
    }

    public function getNamaHari()
    {
        return JadwalDosen_model::DAY_NAME;
    }

    public function testGetNamaBulan()
    {
        $testCase = $this->JadwalDosen_model->getNamaBulan();
        $result = $this->getNamaBulan();
        $this->unit->run($testCase, $result, __FUNCTION__);
    }

    public function getNamaBulan()
    {
        return JadwalDosen_model::MONTH_NAME;
    }

    public function testKolomKeHari()
    {
        $testCase = $this->JadwalDosen_model->kolomKeHari("B");
        $result = 0;
        $this->unit->run($testCase, $result, __FUNCTION__);
    }

    public function testHariKeKolom()
    {
        $testCase = $this->JadwalDosen_model->hariKeKolom(1);
        $result = "C";
        $this->unit->run($testCase, $result, __FUNCTION__);
    }

    public function testCekJadwalByJamMulai()
    {
        $testCase = $this->JadwalDosen_model->cekJadwalByJamMulai(12, 1, 'jojo');
        $result = $this->cekJadwalByJamMulai(12, 1, 'jojo');
        $this->unit->run($testCase, $result, __FUNCTION__);
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

        $this->unit->run($testCase, $ex, __FUNCTION__);
        $this->db->delete('jadwal_dosen', array('user' => 'jaki'));
    }

    public function testRequest()
    {

        $data = array(
            'requestByEmail' => '7316081@student.unpar.ac.id'
        );
        $this->db->insert('PerubahanKuliah', $data);

        $this->db->where('requestByEmail', '7316081@student.unpar.ac.id');
        $this->db->from('PerubahanKuliah');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();
        $ex = $query->result();


        $testCase = $this->PerubahanKuliah_model->requestsBy('7316081@student.unpar.ac.id', null, null);
        $this->db->delete('PerubahanKuliah', array('requestByEmail' => '7316081@student.unpar.ac.id'));


        $this->unit->run($testCase, $ex, __FUNCTION__, "get all record by email");

    }

    function testRequestByTranskrip()
    {
        $this->db->insert('Transkrip', array(
            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' => 'dummytype',
            'requestUsage' => 'tes'
        ));
        $this->db->where('requestByEmail', 'dummyemail');
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestsBy('dummyemail', null, null);
        $excpeted_result = $query->result();
        $this->unit->run($testCase, $excpeted_result, __FUNCTION__);
        $this->db->delete('Transkrip', array('requestByEmail' => 'dummyemail'));
    }

    function testRequestByID()
    {
        $this->db->insert('Transkrip', array(

            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' => 'dummytype',
            'requestUsage' => 'tes'
        ));
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestByID($insert_id, null, null);
        $excpeted_result = $query->result()[0];
        $this->unit->run($testCase, $excpeted_result, __FUNCTION__);
        $this->db->delete('Transkrip', array('id' => $insert_id));
    }


    //still bugged on travis

    function testDateTimeToHumanFormat()
    {
        $this->unit->run(
            $this->bluetape->dbDateTimeToReadableDate("2008-11-11 13:23:44"), "Selasa, 11 November 2008 ", __FUNCTION__, "mengubah format datetime pada database menjadi sesuatu yang bisa di baca manusia"
        );
    }

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
        $this->unit->run($testCase, $ex, __FUNCTION__);
        $this->db->delete('jadwal_dosen', array('id' => $insert_id));
    }

    function requestTypesForbidden()
    {
        $this->db->insert('Transkrip', array(

            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' => 'dummytype',
            'requestUsage' => 'tes'
        ));
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestByID($insert_id, null, null);
        $excpeted_result = $query->result()[0];
        $this->unit->run($testCase, $excpeted_result, __FUNCTION__);
        $this->db->delete('Transkrip', array('id' => $insert_id));
    }
    function TestCreateAuthURL() {
        $testCase = $this->Auth->createAuthURL() ;
        $ex = "https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2Fauth%2Foauth2callback&client_id=755661919348-3b2u44e804efh2mghpadttnqh3u4ujd9.apps.googleusercontent.com&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&access_type=online&approval_prompt=auto";
        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengetes createAuthURL") ;
    }

    function testGetUserInfo() {

        $testCase = $this->Auth->getUserInfo() ;
        $ex =  $this->session->userdata('auth');
        //var_dump($ex);
        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengecek getUserInfo") ;
    }

    function testLogout() {
        $testCase = $this->Auth->logout() ;
        $ex =  $this->session->unset_userdata('auth');

        $this->unit->run($testCase,$ex,__FUNCTION__,"method untuk mengecek logout") ;
    }


}
