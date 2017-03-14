<?php
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class LihatJadwalDosen extends CI_Controller {
		
		public function __construct() {
			parent::__construct();
			try {
				$this->Auth_model->checkModuleAllowed(get_class());
				} catch (Exception $ex) {
				$this->session->set_flashdata('error', $ex->getMessage());
				header('Location: /');
			}
			$this->load->library('bluetape');
			$this->load->library('excel');
			$this->load->model('Transkrip_model');
			$this->load->database();
		}
		
		public function index() {
			
			// Retrieve logged in user data
			$userInfo = $this->Auth_model->getUserInfo();
			$this->load->view('LihatJadwalDosen/main', array(
            'currentModule' => get_class(),
			'request_add_jadwal' => $this->session->userdata('request_add_jadwal')
			));
		}
		
		public function export(){
			$request_print = $this->session->userdata('request_add_jadwal');
			
			$this->excel->setActiveSheetIndex(0);
			$this->excel->getActiveSheet()->setTitle('Jadwal Dosen');
			
			//Menulis header file XLS
			$this->excel->getActiveSheet()->setCellValue('A1', 'JADWAL AKTIVITAS DOSEN');
			$this->excel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
			$this->excel->getActiveSheet()->setCellValue('A2', 'Dosen :');
			$this->excel->getActiveSheet()->getStyle("A2")->getFont()->setBold(true);
			
			//Menulis hari-hari dalam tabel jadwal dosen
			$this->excel->getActiveSheet()->setCellValue('B4', 'Senin');
			$this->excel->getActiveSheet()->setCellValue('C4', 'Selasa');
			$this->excel->getActiveSheet()->setCellValue('D4', 'Rabu');
			$this->excel->getActiveSheet()->setCellValue('E4', 'Kamis');
			$this->excel->getActiveSheet()->setCellValue('F4', 'Jumat');
			
			//menulis jam-jam dalam tabel jadwal dosen
			$jam=7;
			$column_hari=$this->hari_ke_kolom($request_print['hari']);
			if($request_print['jenis_jadwal']=="konsultasi"){
				$color="FEFF00";
			}
			else{
				$color="92D14F";
			}
			for($i=5 ; $i<15 ; $i++){
				$column='A'.$i;
				$this->excel->getActiveSheet()->setCellValue($column, ($jam).'-'.($jam+1));
				
				$temp=idate('H',strtotime($request_print['jam_mulai']))+idate('H',strtotime($request_print['durasi']));
				if($jam>=idate('H', strtotime($request_print['jam_mulai'])) && $jam<$temp){
					$column_hari_used=$column_hari.$i;
					$this->excel->getActiveSheet()->getStyle($column_hari_used)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
				}
				
				$jam++;
			}
			
			
			//Menentukan style dari border
			$borderStyleArray = array(
			'borders' => array(
			'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
			)
			)
			);
			
			//Menulis bagian keterangan
			$this->excel->getActiveSheet()->setCellValue('A17', 'Keterangan :');
			$this->excel->getActiveSheet()->getStyle('B17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FEFF00');
			$this->excel->getActiveSheet()->getStyle('B18') ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D14F');
			$this->excel->getActiveSheet()->setCellValue('C17', 'Waktu Konsultasi');
			$this->excel->getActiveSheet()->setCellValue('C18', 'Jika Dijadwalkan');
			
			//Menentukan letak border
			$this->excel->getActiveSheet()->getStyle('A4:F14')->applyFromArray($borderStyleArray);
			$this->excel->getActiveSheet()->getStyle('B17')->applyFromArray($borderStyleArray);
			$this->excel->getActiveSheet()->getStyle('B18')->applyFromArray($borderStyleArray);
			unset($borderStyleArray);
			
			//Membuat semua tulisan dalam tabel menggunakan align center
			$this->excel->getActiveSheet()->getStyle('A4:F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			for($col = ord('A'); $col <= ord('F'); $col++){
				//mengatur lebar setiap kolom $col
				$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setWidth(15);
			}
			for($row = 4; $row <= 14; $row++){
				//mengatur tinggi setiap baris $row
				$this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
			}
			
			
			$filename='Jadwal Dosen.xlsx'; //Nama file XLS yang akan dibuat
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'. $filename.'"');
			header('Cache-Control: max-age=0');
			
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
			ob_end_clean();
			$filepath=APPPATH."/third_party/";
			$objWriter->save('php://output');  //membuat file langsung di download
		}
		
		public function hari_ke_kolom($hari){
			switch ($hari) {
				case "Senin":
				return "B";
				case "Selasa":
				return "C";
				case "Rabu":
				return "D";
				case "Kamis":
				return "E";
				case "Jumat":
				return "F";
			}
		}
	}
