<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class TestPerubahanKuliah extends CI_Controller
{


    public $coverage;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('unit_test');;
        $this->load->model('PerubahanKuliah_model');
        $this->load->database();
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
        $this->testRequest();

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

    private function report()
    {


        file_put_contents('../TestDocuments/TestPlan/TestPerubahanKuliah.html', $this->unit->report());


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
}
