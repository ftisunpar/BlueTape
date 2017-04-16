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
			$this->load->library('session');
			$this->load->model('JadwalDosen_model');
			$this->load->database();
		}
		
		public function index() {
			
			// Retrieve logged in user data
			$userInfo = $this->Auth_model->getUserInfo();
			
			$dataJadwal = $this->JadwalDosen_model->getAllJadwal();
			$dataJadwalPerUser=array();
			foreach($dataJadwal as $key => $indexValue){
				$dataJadwalPerUser[$indexValue->user][$key] = $indexValue;  // dimensi pertama indexnya adalah user
			}
			ksort($dataJadwalPerUser);
			$_SESSION['dataJadwalPerUser']=$dataJadwalPerUser; //agar dapat dibaca oleh controller LihatJadwalDosen fungsi export() 
			
			$this->load->view('LihatJadwalDosen/main', array(
            'currentModule' => get_class(),
			'dataJadwalPerUser'    =>$dataJadwalPerUser
			));
		}
		
		public function export(){
			$dataToExport = $_SESSION["dataJadwalPerUser"];
			
			// ------------------------------------------------------------ TEMPLATE TABEL JADWAL DOSEN -----------------------------------------------------------------------------
			$startHourRow=5;
			$endHourRow=$startHourRow+10;	
			$startColoredCell=-2; // minus dua karena perbedaan penomoran baris di excel dengan jam paling pagi bisa dicatatnya sebuah jadwal
			$titleRow=1;
			$nameRow=2;
			$dayRow=4;
			$keteranganRow=17;
			$nextTableRowsAdder=19;
			
			$idx=0;
			$sheetIdx=0;
			foreach($dataToExport as $currRow){	
				$objWorkSheet = $this->excel->createSheet($sheetIdx);
				$this->excel->setActiveSheetIndex(0);
				$this->excel->getActiveSheet()->setTitle('Jadwal '.$currRow[$idx]->name);
				
				//Menulis header tabel
				$titleCell='A'.$titleRow;
				$this->excel->getActiveSheet()->setCellValue($titleCell, 'JADWAL AKTIVITAS DOSEN');
				$this->excel->getActiveSheet()->getStyle($titleCell)->getFont()->setBold(true);
				$this->excel->getActiveSheet()->setCellValue('A'.$nameRow, 'Dosen :'.$currRow[$idx]->name); // menulis nama dosen yang bersangkutan 
				
				$this->excel->getActiveSheet()->getStyle('A'.$nameRow)->getFont()->setBold(true);
				
				//Menulis hari-hari dalam tabel jadwal dosen
				for($dayCol='B';$dayCol<='F';$dayCol++){
					$dayCell=$dayCol.$dayRow;
					$hari=$this->kolom_ke_hari($dayCol);
					$this->excel->getActiveSheet()->setCellValue($dayCell, $hari);
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
				$this->excel->getActiveSheet()->setCellValue('A'.$keteranganRow, 'Keterangan :');
				$this->excel->getActiveSheet()->getStyle('B'.$keteranganRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FEFF00');
				$this->excel->getActiveSheet()->getStyle('B'.($keteranganRow+1)) ->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('92D14F');
				$this->excel->getActiveSheet()->setCellValue('C'.$keteranganRow, 'Waktu Konsultasi');
				$this->excel->getActiveSheet()->setCellValue('C'.($keteranganRow+1), 'Jika Dijadwalkan');
				
				//membuat border pada tabel
				$this->excel->getActiveSheet()->getStyle('A'.$dayRow.':F'.($dayRow+10))->applyFromArray($borderStyleArray);
				$this->excel->getActiveSheet()->getStyle('B'.$keteranganRow)->applyFromArray($borderStyleArray);
				$this->excel->getActiveSheet()->getStyle('B'.($keteranganRow+1))->applyFromArray($borderStyleArray);
				unset($borderStyleArray);
				
				//Membuat semua tulisan dalam tabel menggunakan align center
				$this->excel->getActiveSheet()->getStyle('A'.$dayRow.':F'.($dayRow+10))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				for($col = ord('A'); $col <= ord('F'); $col++){
					//mengatur lebar setiap kolom $col
					$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setWidth(15);
				}
				for($row = $dayRow; $row <= ($dayRow+10); $row++){
					//mengatur tinggi setiap baris $row
					$this->excel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
				}
				//--------------------------------------------------------------------------------END TEMPLATE---------------------------------------------------------------------------
				
				//menulis jam-jam dalam tabel jadwal dosen
				$jam=7;
				for($i=$startHourRow ; $i<($endHourRow) ; $i++){
					$column='A'.$i;
					$this->excel->getActiveSheet()->setCellValue($column, ($jam).'-'.($jam+1));
					$jam++;
				}
				foreach($currRow as $dataHariIni){
					if($dataHariIni!=null){
						$colHari=$this->hari_ke_kolom($dataHariIni->hari);
						$row_jam_mulai= $dataHariIni->jam_mulai + $startColoredCell;
						$row_jam_selesai = $row_jam_mulai + $dataHariIni->durasi;
						if($dataHariIni->jenis=="konsultasi"){
							$color="FEFF00";
						}
						else{
							$color="92D14F"; //#c1c1c1
						}
						
						$counterForLabel=0;
						for($rowHour=$row_jam_mulai ; $rowHour<$row_jam_selesai; $rowHour++){
							$cellToBeColored=$colHari.$rowHour;
							$this->excel->getActiveSheet()->getStyle($cellToBeColored)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
							if( ($counterForLabel) == (int)($dataHariIni->durasi/2) || ($dataHariIni->durasi)==1){ 
								$this->excel->getActiveSheet()->setCellValue($cellToBeColored, $dataHariIni->label);
							}
							$counterForLabel++;
						}
						
					}
				}
				
				$idx++;
				$sheetIdx;
				
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
		
		public function kolom_ke_hari($col){
			switch ($col) {
				case "B":
				return "Senin";
				case "C":
				return "Senin";
				case "D":
				return "Rabu";
				case "E":
				return "Kamis";
				case "F":
				return "Jumat";
			}
		}
	}
