<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TestLibrary extends CI_Controller {

    const ENABLE_COVERAGE = true; // Requires xdebug

    private $coverage;

    public function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
        $this->unit->use_strict(TRUE);

        if (self::ENABLE_COVERAGE) {
            $this->coverage = new SebastianBergmann\CodeCoverage\CodeCoverage;
            $this->coverage->filter()->addDirectoryToWhitelist('application/controllers');
            $this->coverage->filter()->removeDirectoryFromWhitelist('application/controllers/tests');
            $this->coverage->filter()->addDirectoryToWhitelist('application/libraries');
            $this->coverage->filter()->addDirectoryToWhitelist('application/models');
            $this->coverage->filter()->addDirectoryToWhitelist('application/views');
            $this->coverage->start('UnitTests');
        }

        $this->load->library('BlueTape');
    }

    private function report() {
        if (self::ENABLE_COVERAGE) {
            $this->coverage->stop();        
            $writer = new  \SebastianBergmann\CodeCoverage\Report\Html\Facade;
            $writer->process($this->coverage, '../reports/code-coverage');
        }

        // Generate Test Report HTML
        file_put_contents('../reports/test_report.html', $this->unit->report());

        // Output result to screen
        $statistics = [
            'Pass' => 0,
            'Fail' => 0
        ];
        $results = $this->unit->result();
        foreach ($results as $result) {

            echo "=== " . $result['Test Name'] . " ===\n";
            foreach ($result as $key => $value) {
                echo "$key: $value\n";
            }
            echo "\n";
            if ($result['Result'] === 'Passed') {
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
    public function index() {
        // $this->unit->
        $this->unit->set_test_items(array('test_name', 'test_datatype' , 'res_datatype' , 'result'));
        $this->testBlueTapeLibraryGetNPM();
        $this->testBlueTapeLibraryGetNPM_2017();
        $this->testGetSemester();
        // $this->testGetName();
        $this->testGetSemesterSimple();


        $this->report();
    }

    public function testBlueTapeLibraryGetNPM() {
        $this->unit->run(
            $this->bluetape->getNPM('7313013@student.unpar.ac.id'),
            '2013730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan <  2017'
        );
    }

    public function testBlueTapeLibraryGetNPM_2017() {
        $this->unit->run(
            $this->bluetape->getNPM('2017730013@student.unpar.ac.id'),
            '2017730013',
            __FUNCTION__,
            'Ensure e-mail to NPM conversion works, for angkatan >= 2017'
        );
    }

    function testGetSemester(){
    $this->unit->run(
        $this->bluetape->yearMonthToSemesterCode("2016",1),"162", __FUNCTION__ , "Untuk mengecek semester"

    );
    }
    function testGetSemesterSimple(){
        $this->unit->run(
            $this->bluetape->yearMonthToSemesterCodeSimplified("2016",1),"162", __FUNCTION__ , "Untuk mengkonversi tahun dan bulan sekarang menjadi code smester sederhana"
    
        );
        }

    function testGetName(){
        $this->unit->run(
            $this->bluetape->getName("7316084@student.unpar.ac.id"),"DINI PUSPITA SUKMA ARIYANTI", __FUNCTION__ , "Untuk mendapatkan nama mahasiswa dari email"
    
        );
        }





}
