<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LihatJadwalDosen extends CI_Controller {

	private $excel;
    public function __construct() {
        parent::__construct();
        try {
            $this->Auth_model->checkModuleAllowed(get_class());
        } catch (Exception $ex) {
            $this->session->set_flashdata('error', $ex->getMessage());
            header('Location: /');
        }
        $this->excel = new PHPExcel();
        $this->load->library('bluetape');
        $this->load->library('session');
        $this->load->model('JadwalDosen_model');
        $this->load->database();
    }

	//method yang dipanggil ketika halaman LihatJadwalDosen dibuka
    public function index() {
        // Retrieve logged in user data
        $userInfo = $this->Auth_model->getUserInfo();

        $dataJadwal = $this->JadwalDosen_model->getAllJadwal();
        $dataJadwalPerUser = array();
        foreach ($dataJadwal as $key => $indexValue) {
            $dataJadwalPerUser[$indexValue->user][$key] = $indexValue;  // dimensi pertama indexnya adalah user
        }
        ksort($dataJadwalPerUser);
		$this->session->set_userdata( 'dataJadwalPerUser', $dataJadwalPerUser );
        $namaHari = $this->JadwalDosen_model->getNamaHari();
		$namaBulan = $this->JadwalDosen_model->getNamaBulan();
        $this->load->view('LihatJadwalDosen/main', array(
            'currentModule' => get_class(),
            'dataJadwalPerUser' => $dataJadwalPerUser,
            'namaHari' => $namaHari,
			'namaBulan'=> $namaBulan
        ));
    }

	//method untuk mengubah jadwal di database kedalam file .xls
    public function export() {
        $dataToExport = $this->session->userdata('dataJadwalPerUser');
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
            $objWorkSheet = $this->excel->createSheet($sheetIdx);
            $this->excel->setActiveSheetIndex(0);
            foreach ($currRow as $oneData) {
                $name = $oneData->name;
                break;
            }; 
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
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
			$outlineStyle = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)	
				)
			);


            //Menulis bagian keterangan
            $this->excel->getActiveSheet()->setCellValue('A' . $keteranganRow, 'Keterangan :');
            $this->excel->getActiveSheet()->getStyle('B' . $keteranganRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FEFF00');
            $this->excel->getActiveSheet()->getStyle('B' . ($keteranganRow + 1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D14F');
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
            $this->excel->getActiveSheet()->getStyle('A' . $dayRow . ':F' . ($dayRow + 10))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('A' . $dayRow . ':F' . ($dayRow + 10))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            for ($col = ord('A'); $col <= ord('F'); $col++) {
                //mengatur lebar setiap kolom $col
                $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setWidth(15);
            }
            for ($row = $dayRow; $row <= ($dayRow + 10); $row++) {
                //mengatur tinggi setiap baris $row
                $this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
            }
            //--------------------------------------------------------------------------------END TEMPLATE---------------------------------------------------------------------------
            //menulis jam-jam dalam tabel jadwal dosen
            $jam = 7;
            for ($i = $startHourRow; $i < ($endHourRow); $i++) {
                $column = 'A' . $i;
                $this->excel->getActiveSheet()->setCellValue($column, ($jam) . '-' . ($jam + 1));
                $jam++;
            }

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
                    $this->excel->getActiveSheet()->getStyle($jadwalStartCell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color); //mewarnai cell
                    $this->excel->getActiveSheet()->setCellValue($jadwalStartCell, $dataHariIni->label);
					$this->excel->getActiveSheet()->getStyle($jadwalStartCell)->getAlignment()->setWrapText(true); ; //agar tulisan tidak keluar dari area cell
                }
            }
            $idx++;
            $sheetIdx;
        }
		
		$this->excel->setActiveSheetIndexByName('Worksheet');	//Mencari default worksheet 'worksheet'
		$sheetIndex = $this->excel->getActiveSheetIndex();		//worksheet aktif dibuah ke default worksheet tadi
		$this->excel->removeSheetByIndex($sheetIndex);			//menghapus worksheet aktif
		
        $filename = 'JadwalDosen-'.date("Ymd").'.xls'; //Nama file XLS yang akan dibuat
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $filepath = APPPATH . "/third_party/";
        $objWriter->save('php://output');  //membuat file langsung di download
    }

}
