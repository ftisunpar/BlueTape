<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EntriJadwalDosen extends CI_Controller {

    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
		$this->excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->load->library('bluetape');
        $this->load->model('JadwalDosen_model');
        $this->load->model('Auth_model');
        $this->load->database();
    }
	
	//method yang dipanggil ketika halaman EntriJadwalDosen dibuka
    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();
        $dataJadwal = $this->JadwalDosen_model->getJadwalByUsername($userInfo['email']);
        $namaHari = $this->JadwalDosen_model->getNamaHari();
		$namaBulan = $this->JadwalDosen_model->getNamaBulan();
        $this->load->view('EntriJadwalDosen/main', array(
            'currentModule' => get_class(),
            'request_add_jadwal' => $this->session->userdata('request_add_jadwal'),
            'dataJadwal' => $dataJadwal,
            'namaHari' => $namaHari,
			'namaBulan'=> $namaBulan
        ));
    }

	//fungsi untuk menambah jadwal ke dalam database dari input user
    public function add() {
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $userInfo = $this->Auth_model->getUserInfo();
            $jam_mulai = $this->input->post('jam_mulai');
            $durasi = $this->input->post('durasi');
            $jam_akhir = $jam_mulai + $durasi ;
            $hari = $this->input->post('hari');
            $bisaMasuk = TRUE;
            for($i=7 ; $i<17 ; $i++){
                //memeriksa apakah ada jadwal lain di antara jam mulai dan jam akhir pada jadwal yang dimasukan oleh user
                $exist=$this->JadwalDosen_model->cekJadwalByJamMulai($i,$hari,$userInfo['email']);
                if($exist!=null){  //ada potensi jadwal bentrok
                    $existJamAkhir = $exist[0]->jam_mulai+$exist[0]->durasi; //mendapatkan jam akhir dari jadwal yang berpotensi bentrok
                    if(($exist[0]->jam_mulai<=$jam_mulai && $existJamAkhir>$jam_mulai) || ($exist[0]->jam_mulai<$jam_akhir && $existJamAkhir>=$jam_akhir)){
                        $bisaMasuk = FALSE;
                        break;
                    }
                }
            }
            if($bisaMasuk){
                $data = array(
                'user' => $userInfo['email'],
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'durasi' => $this->input->post('durasi'),
                'jenis_jadwal' => $this->input->post('jenis_jadwal'),
                'label_jadwal' => $this->input->post('label_jadwal')
                );
                $this->JadwalDosen_model->addJadwal($data);
                header('Location: /EntriJadwalDosen');
            }
            else{
                $this->session->set_flashdata('error', 'Jadwal gagal dimasukan karena sudah ada jadwal pada waktu tersebut, silahkan pilih waktu lain.');
                header('Location: /EntriJadwalDosen');
            }
        } else {
            $this->session->set_flashdata('error', "Can't call method from GET request!");
                header('Location: /EntriJadwalDosen');
        }
        
    }

	//fungsi untuk mengupdate jadwal di dalam database dari input user
    public function update($id_jadwal) {
        if ($this->input->server('REQUEST_METHOD') == 'POST'){
            $userInfo = $this->Auth_model->getUserInfo();
    		
    		$jam_mulai = $this->input->post('jam_mulai');
    		$durasi = $this->input->post('durasi');
    		$jam_akhir = $jam_mulai + $durasi ;
    		$hari = $this->input->post('hari');
    		$bisaMasuk = TRUE;
    		for($i=7 ; $i<17 ; $i++){
    			//memeriksa apakah ada jadwal lain di antara jam mulai dan jam akhir pada jadwal yang dimasukan oleh user
    			$exist=$this->JadwalDosen_model->cekJadwalByJamMulai($i,$hari,$userInfo['email']);
    			if($exist!=null){  //ada potensi jadwal bentrok
    				$existJamAkhir = $exist[0]->jam_mulai+$exist[0]->durasi; //mendapatkan jam akhir dari jadwal yang berpotensi bentrok
    				if( ($exist[0]->jam_mulai<=$jam_mulai && $existJamAkhir>$jam_mulai) || ($exist[0]->jam_mulai<$jam_akhir && $existJamAkhir>=$jam_akhir)){ 
    					if(strcmp($exist[0]->id,$id_jadwal)){ //memeriksa apakah jadwal tersebut merupakan dirinya sendiri atau bukan
    						$bisaMasuk = FALSE;
    						break;
    					}
    				}
    			}
    		}
    		if($bisaMasuk){
    			$data = array(
    				'hari' => $this->input->post('hari'),
    				'jam_mulai' => $this->input->post('jam_mulai'),
    				'durasi' => $this->input->post('durasi'),
    				'jenis' => $this->input->post('jenis_jadwal'),
    				'label' => $this->input->post('label_jadwal'),
    				'lastUpdate' => date('Y-m-d H:i:s')
    			);
    			$this->JadwalDosen_model->updateJadwal($id_jadwal, $data);
    			header('Location: /EntriJadwalDosen');
    		}
    		else{
    			$this->session->set_flashdata('error', 'Jadwal gagal di-update karena menimbulkan konflik dengan jadwal lain.');
    			header('Location: /EntriJadwalDosen');
    		}
        } else {
            $this->session->set_flashdata('error', "Can't call method from GET request!");
            header('Location: /EntriJadwalDosen');
        }
    }
	
	//menghapus jadwal berdasarkan id jadwal yang dipilih user
	public function delete($id_jadwal) {
        $this->JadwalDosen_model->deleteJadwal($id_jadwal);
        header('Location: /EntriJadwalDosen');
    }
	
	//menghapus semua jadwal milik user
	public function deleteAll(){
		$userInfo = $this->Auth_model->getUserInfo();
		$this->JadwalDosen_model->deleteByUsername($userInfo['email']);
        header('Location: /EntriJadwalDosen');
	}

	//mendapatkan data-data milik user
    public function getDataJadwal($id_jadwal) {
        echo $this->JadwalDosen_model->getJadwalByIdJadwal($id_jadwal);
    }

	
	public function export(){
		$dataToExport = $this->session->userdata('dataJadwalPerUser');
		$userInfo = $this->Auth_model->getUserInfo();
		$curr_user = $userInfo['email'];
        // ------------------------------------------------------------ TEMPLATE TABEL JADWAL DOSEN || TIDAK PERLU DIUBAH LAGI-----------------------------------------------------------------------------
        $startHourRow = 5;
        $endHourRow = $startHourRow + 10;
        $startColoredCell = -2; // minus dua karena perbedaan penomoran baris di excel dengan jam paling pagi yang bisa dicatat di jadwal
        $titleRow = 1;
        $nameRow = 2;
        $dayRow = 4;
        $keteranganRow = 17;
        $nextTableRowsAdder = 19;

        $idx = 0;
        $sheetIdx = 0;
        foreach ($dataToExport as $currRow) {
			foreach ($currRow as $oneData) {
                $name = $oneData->name;
				$email = $oneData ->user;
                break;
            }; 
			if($email == $curr_user){
			$curr_name = $name;
			$curr_email = $email;
            $objWorkSheet = $this->excel->createSheet($sheetIdx);
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle($name);

            //Menulis header tabel
            $titleCell = 'A' . $titleRow;
            $this->excel->getActiveSheet()->setCellValue($titleCell, 'JADWAL AKTIVITAS DOSEN');
            $this->excel->getActiveSheet()->getStyle($titleCell)->getFont()->setBold(true);
            $this->excel->getActiveSheet()->setCellValue('A' . $nameRow, 'Dosen : ' . $name); 		// menulis nama dosen yang bersangkutan 
            $this->excel->getActiveSheet()->getStyle('A' . $nameRow)->getFont()->setBold(true);

            //Menulis hari-hari dalam tabel jadwal dosen
            $hari = $this->JadwalDosen_model->getNamaHari();
            $dayCol = 'B';
            foreach ($hari as $key) {
                $dayCell = $dayCol . $dayRow;
                $this->excel->getActiveSheet()->setCellValue($dayCell, $key);
                $dayCol++;
            }

            //Menentukan style dari border
            $borderStyleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    )
                )
            );
			$outlineStyle = array(
				'borders' => array(
					'outline' => array(
						'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
					)	
				)
			);


            //Menulis bagian keterangan
            $this->excel->getActiveSheet()->setCellValue('A' . $keteranganRow, 'Keterangan :');
            $this->excel->getActiveSheet()->getStyle('B' . $keteranganRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FEFF00');
            $this->excel->getActiveSheet()->getStyle('B' . ($keteranganRow + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('92D14F');
            $this->excel->getActiveSheet()->setCellValue('C' . $keteranganRow, 'Waktu Konsultasi');
            $this->excel->getActiveSheet()->setCellValue('C' . ($keteranganRow + 1), 'Jika Dijadwalkan');

            //Membuat border
            $this->excel->getActiveSheet()->getStyle('A4:A14')->applyFromArray($borderStyleArray);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('B4:F4')->applyFromArray($borderStyleArray);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('A4:F14')->applyFromArray($outlineStyle);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('B4:B14')->applyFromArray($outlineStyle);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('C4:C14')->applyFromArray($outlineStyle);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('D4:D14')->applyFromArray($outlineStyle);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('E4:E14')->applyFromArray($outlineStyle);    						//menambah outline pada body tabel
            $this->excel->getActiveSheet()->getStyle('B' . $keteranganRow)->applyFromArray($borderStyleArray);    			//menambah border pada kotak keterangan
            $this->excel->getActiveSheet()->getStyle('B' . ($keteranganRow + 1))->applyFromArray($borderStyleArray);   		//menambah border pada kotak keterangan yang kedua
            unset($borderStyleArray);

            //Membuat semua tulisan dalam tabel menggunakan align center
            $this->excel->getActiveSheet()->getStyle('A' . $dayRow . ':F' . ($dayRow + 10))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A' . $dayRow . ':F' . ($dayRow + 10))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            for ($col = ord('A'); $col <= ord('F'); $col++) {
                //mengatur lebar setiap kolom $col
                $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setWidth(15);
            }
            for ($row = $dayRow; $row <= ($dayRow + 10); $row++) {
                //mengatur tinggi setiap baris $row
                $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
            }
           
            //menulis jam-jam dalam tabel jadwal dosen
            $jam = 7;
            for ($i = $startHourRow; $i < ($endHourRow); $i++) {
                $column = 'A' . $i;
                $this->excel->getActiveSheet()->setCellValue($column, ($jam) . '-' . ($jam + 1));
                $jam++;
            }
			
			 //--------------------------------------------------------------------------------END TEMPLATE---------------------------------------------------------------------------

            //mewarnai tabel sesuai jadwal yang sudah dimasukkan
            foreach ($currRow as $dataHariIni) {
                if ($dataHariIni != null) {
                    $colHari = $this->JadwalDosen_model->hariKeKolom($dataHariIni->hari);     	//index kolom hari
                    $row_jam_mulai = $dataHariIni->jam_mulai + $startColoredCell;  				//$startColoredCell berfungsi agar nilai row_jam_mulai tepat berada pada index baris yang tepat (jam mulai dan index baris jam pada excelnya berselisih 2 , maka startColoredCell bernilai "-2". Karena misal jam_mulai = 7, tetapi pada excel baris yang bertanda "jam 7" ada pada baris ke 5, bukan baris ke 7)
                    $row_jam_selesai = $row_jam_mulai + $dataHariIni->durasi;
                    if ($row_jam_selesai > 15) {           										//agar pewarnaan maupun outline jadwal tidak melebihi tabel yang sudah ditentukan di atas
                        $row_jam_selesai = 15;
                    }
                    if ($dataHariIni->jenis == "konsultasi") {        							//pemilihan warna tergantung dari tipe jadwal
                        $color = "FEFF00";
                    } else if ($dataHariIni->jenis == "kelas") {
                        $color = "FFFFFF";
                    } else {
                        $color = "92D14F";
                    }

                    $jadwalStartCell = $colHari . $row_jam_mulai;                   		 //cell pertama penulisan jadwal pada tabel
                    $jadwalEndCell = $colHari . (($row_jam_selesai - 1));                    //cell terakhir dari jadwal
                    $cellsToBeMerged = $jadwalStartCell . ":" . $jadwalEndCell;
                    $this->excel->getActiveSheet()->mergeCells($cellsToBeMerged);
					$this->excel->getActiveSheet()->getStyle($cellsToBeMerged)->applyFromArray($outlineStyle);
                    $this->excel->getActiveSheet()->getStyle($jadwalStartCell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB($color); //mewarnai cell
                    $this->excel->getActiveSheet()->setCellValue($jadwalStartCell, $dataHariIni->label);
					$this->excel->getActiveSheet()->getStyle($jadwalStartCell)->getAlignment()->setWrapText(true); ; //agar tulisan tidak keluar dari area cell
                }
            }
            $idx++;
            $sheetIdx;
        }
		}
		$this->excel->setActiveSheetIndexByName('Worksheet');	//Mencari default worksheet 'worksheet'
		$sheetIndex = $this->excel->getActiveSheetIndex();		//worksheet aktif dibuah ke default worksheet tadi
		$this->excel->removeSheetByIndex($sheetIndex);			//menghapus worksheet aktif
		
		
        $filename = 'Jadwal-'.$curr_name.'-'.date("Ymd").'.xls'; //Nama file XLS yang akan dibuat
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->excel, 'Xls');
        $filepath = APPPATH . "/third_party/";
        $objWriter->save('php://output');  //membuat file langsung di download
        header('Location: /EntriJadwalDosen');
	}
	
}
