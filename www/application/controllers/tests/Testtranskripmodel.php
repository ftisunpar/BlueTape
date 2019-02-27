<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testtranskripmodel extends CI_Controller {



    public $coverage;

    public function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
        $this->load->database();
        $this->load->model('Transkrip_model');
        $this->load->model('JadwalDosen_model' , 'jadwaldosen');
    }


    private function report() {
        $str = '
        <table border="0"  cellpadding="4" cellspacing="1">
        {rows}
                <tr>
                        <td>{item}</td>
                        <td>{result}</td>
                </tr>

                <br>


        </table>';
                $this->unit->set_template($str);

        file_put_contents('../www/application/views/TestDocuments/TestTranskrip.html', $this->unit->report());
        // file_put_contents('../www/application/views/TestDocuments/test_Library.php', $this->unit->report());

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
    public function index() {

        $this->unit->set_test_items(array('test_name', 'test_datatype' , 'res_datatype' , 'result'));
        $this->testRequestBy();
        $this->testRequestByID();
        $this->report();
    }


    function testRequestBy(){
        $this->db->insert('Transkrip', array(
            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' =>'dummytype',
            'requestUsage' => 'tes'
        ));
        $this->db->where('requestByEmail', 'dummyemail');
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestsBy('dummyemail' ,null , null);
        $excpeted_result = $query->result();
        $this->unit->run($testCase , $excpeted_result , __FUNCTION__  );
        $this->db->delete('Transkrip', array('requestByEmail' => 'dummyemail'));
    }

    function testRequestByID(){
        $this->db->insert('Transkrip', array(

            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' =>'dummytype',
            'requestUsage' => 'tes'
        ));
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestByID($insert_id ,null , null);
        $excpeted_result = $query->result()[0];
        $this->unit->run($testCase , $excpeted_result , __FUNCTION__  );
        $this->db->delete('Transkrip', array('id' => $insert_id));
    }

    function requestTypesForbidden(){
        $this->db->insert('Transkrip', array(

            'requestByEmail' => 'dummyemail',
            'requestDateTime' => strftime('%Y-%m-%d %H:%M:%S'),
            'requestType' =>'dummytype',
            'requestUsage' => 'tes'
        ));
        $insert_id = $this->db->insert_id();
        $this->db->where('id', $insert_id);
        $this->db->from('Transkrip');
        $this->db->order_by('requestDateTime', 'DESC');
        $query = $this->db->get();

        $testCase = $this->Transkrip_model->requestByID($insert_id ,null , null);
        $excpeted_result = $query->result()[0];
        $this->unit->run($testCase , $excpeted_result , __FUNCTION__  );
        $this->db->delete('Transkrip', array('id' => $insert_id));
    }
}
