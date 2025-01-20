<?php
namespace App\Modules\WorkflowPengajuan\models;

use Config\Database;
use CodeIgniter\Model;

Class Payment_plan_model Extends Model 
{
    function effective($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium)
	{
		helper(['math_helper']);
		$pokok = $sisa_pokok_pinjaman;
		$jumlah_cicilan_pokok = 0;
		$output = array();

		for ($i=0; $i < $tenor ; $i++) { 
			$installment_no = $i+1;
			
			$installment_principle = ppmt(($bunga/100)/12 , $installment_no , $tenor , -$sisa_pokok_pinjaman ,1 , false );
			$interest = ipmt(($bunga/100)/12 , $installment_no , $tenor , -$sisa_pokok_pinjaman ,1 , false );
			$installment_amount = $installment_principle + $interest;

			$jumlah_cicilan_pokok = $jumlah_cicilan_pokok + $installment_principle;

			$saldo = $sisa_pokok_pinjaman-$jumlah_cicilan_pokok;

			if($i>0){
				$pokok = $pokok - $output[$i-1]['installment_principle'];
			}else{
				$pokok = $pokok;
			}


			if($moratorium==''){
				$count_moratorium = 0;
				
				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}else{
				$count_moratorium = $moratorium/$tenor;
				$installment_amount = $installment_amount + $count_moratorium;

				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'moratorium' 			=> ceil($count_moratorium),
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}
			
		}

		return $output;
	}

	function flat($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium)
	{
		$jumlah_cicilan_pokok = 0;
		$saldo = $sisa_pokok_pinjaman;
		$output = array();
		for ($i=0; $i < $tenor ; $i++) { 
			$installment_no = $i+1;
			$installment_principle = $sisa_pokok_pinjaman/$tenor;
			$interest = $sisa_pokok_pinjaman*(($bunga/100)/12);
			$installment_amount = $installment_principle+$interest;

			$saldo = $saldo - $installment_principle;

			if($i>0){
				$pokok = $output[$i-1]['saldo'];
			}else{
				$pokok = $sisa_pokok_pinjaman;
			}

			if($moratorium==''){
				$count_moratorium = 0;
				
				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}else{
				$count_moratorium = $moratorium / $tenor;
				$installment_amount = $installment_amount + $count_moratorium;

				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'moratorium' 			=> ceil($count_moratorium),
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}

		}

		
		return $output;
	}

	function sliding($tipe, $sisa_pokok_pinjaman, $bunga, $tenor, $moratorium)
	{
		$jumlah_cicilan_pokok = 0;
		$saldo = $sisa_pokok_pinjaman;
		$output = array();
		for ($i=0; $i < $tenor ; $i++) { 
			$installment_no = $i+1;
			$installment_principle = $sisa_pokok_pinjaman/$tenor;
			
			

			$saldo = $saldo - $installment_principle;

			if($i>0){
				$pokok = $output[$i-1]['saldo'];
			}else{
				$pokok = $sisa_pokok_pinjaman;
			}
			$interest = $pokok*(($bunga/100)/12);
			$installment_amount = $installment_principle+$interest;

			if($moratorium==''){
				$count_moratorium = 0;
				
				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}else{
				$count_moratorium = $moratorium/$tenor;
				$installment_amount = $installment_amount + $count_moratorium;

				$output[$i]=array(
					'installment_no' 		=> ceil($installment_no),
					'principle'				=> ceil($pokok),  
					'installment_principle' => ceil($installment_principle),
					'interest'				=> ceil($interest) ,
					'moratorium' 			=> ceil($count_moratorium),
					'installment_amount' 	=> ceil($installment_amount),
					'saldo' 				=> ceil($saldo)
				);
			}

		}

		return $output;
	}

}