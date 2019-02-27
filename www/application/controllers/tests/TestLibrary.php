<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class TestLibrary extends CI_Controller
{


    public $coverage;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('unit_test');
        $this->load->library('BlueTape');
        $this->load->database();
        $this->load->dbforge();
//         $str = '
// <table border="0"  cellpadding="4" cellspacing="1">
// {rows}
//         <tr>
//                 <td></td>
//                 <td></td>
//         </tr>
//
//         <br>
//
//
// </table>';
//         $this->unit->set_template($str);


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
        $this->testGetSemester_genap();
        $this->testGetSemesterSimple_genap();
        $this->testGetSemesterSimple_ganjil();
        $this->testSmesterCodeToStringGanjil();
        $this->testSmesterCodeToStringGenap();
        $this->testSmesterCodeToStringPadat();
        $this->testGetSemester_ganjil();
        $this->testGetName();
        $this->testGetSemester_pendek();
        $this->testGetEmailBawah();
        $this->testGetEmailAtas();
        $this->testSmesterCodeToStringFalse();


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

    function testGetSemester_genap()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCode("2016", 1), "162", __FUNCTION__, "Untuk mengecek semester genap"

        );
    }

    function testGetSemesterSimple_genap()
    {
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCodeSimplified("2016", 1), "162", __FUNCTION__, "Untuk mengkonversi tahun dan bulan sekarang menjadi code smester sederhana (genap)"

        );
    }

    function testGetSemesterSimple_ganjil()
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

    function testGetSemester_ganjil()
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

    private function report()
    {


        file_put_contents('../TestDocuments/TestPlan/test_Library.html', $this->unit->report());

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

    //EMAIL MHS 2017 KEATAS

    function testDateTimeToHumanFormat()
    {
        $this->unit->run(
            $this->bluetape->dbDateTimeToReadableDate("2008-11-11 13:23:44"), "Selasa, 11 November 2008 ", __FUNCTION__, "mengubah format datetime pada database menjadi sesuatu yang bisa di baca manusia"
        );
    }


}
