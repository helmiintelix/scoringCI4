<?php
namespace App\Modules\WorkflowPengajuan\models;

use App\Models\Common_model;
use CodeIgniter\Model;

class Anuitas_model extends Model 
{
    protected $Common_model;

    function __construct(){
        parent::__construct();
        $this->Common_model = new Common_model();
    }

    function anuitas($id_pengajuan = null, $hutang = 0, $interest = 0, $tenor = 0){
        if ($id_pengajuan == null) {
            return false;
        }

        $agreement_no = $this->Common_model->get_record_value('cm_card_nmbr', 'cms_workflow_view', 'id="' . $id_pengajuan . '"');
        
        $besar_pinjaman = $hutang;
        $bunga          = $interest;
        $jangka         = $tenor;
        
        $perbulan = $bunga / 12;

        $no = 0;
        $hutang = $besar_pinjaman;

        $this->builder = $this->db->table('cms_anuitas_restructure_detail');
        $this->builder->where('id_pengajuan', $id_pengajuan);
        $this->builder->delete();

        do {
            $no++;
            $ang_bunga = abs($this->ipmt_models($bunga / 100, $no, $jangka, $besar_pinjaman));
            $ang_pokok = abs($this->ppmt_models($bunga / 100, $no, $jangka, $besar_pinjaman));
            $hutang = $hutang - $ang_pokok;
            $cicilan = $ang_bunga + $ang_pokok;

            $data = array(
                'id_pengajuan'       => $id_pengajuan,
                'agreement_no'       => $agreement_no,
                'installment_no'     => $no,
                'angsuran_pokok'     => $ang_pokok,
                'angsuran_bunga'     => $ang_bunga,
                'installment_amount' => $cicilan,
                'hutang'             => $hutang
            );

            $this->builder = $this->db->table("cms_anuitas_restructure_detail");
            $this->builder->insert($data);

        } while ($no < $jangka);
    }

    function new_installment_amount($hutang=0,$interest=0,$tenor=0){
		$besar_pinjaman = $hutang;
		$bunga          = $interest;
		$jangka         = $tenor;
		
		$perbulan = $bunga/12;
	
		$no = 1;
		$hutang = $besar_pinjaman;
	
	
		do {
				$no++;
				$ang_bunga = abs($this->ipmt_models($bunga/100, $no,$jangka, $besar_pinjaman));
				$ang_pokok = abs($this->ppmt_models($bunga/100, $no,$jangka, $besar_pinjaman));
				$hutang = $hutang - $ang_pokok;
				$cicilan = $ang_bunga+$ang_pokok;
	

	
			} while ($no < 2);
	
		return $cicilan;
	}

	function hitung_kredit_models($besar_pinjaman, $jangka, $bunga)
	{
		$bunga_bulan      = ($bunga/12)/100;
		$pembagi          = 1-(1/pow(1+$bunga_bulan,$jangka));
		$hasil                = $besar_pinjaman/($pembagi/$bunga_bulan);
		return $hasil;
	}

	function rupiah_models($angka)
	{
		$jadi     = "Rp ".number_format($angka,2,',','.');
		return $jadi; 
	}

	function pmt_models($rate,  $periods, $present_value, $future_value = 0.0, $beginning = false)
	{
		$when = $beginning ? 1 : 0;

		if ($rate == 0) {
			return - ($future_value + $present_value) / $periods;
		}

		return - ($future_value + ($present_value * \pow(1 + $rate, $periods)))
			/
			((1 + $rate * $when) / $rate * (\pow(1 + $rate, $periods) - 1));
	}

	function ipmt_models($rate,  $period,  $periods, $present_value,  $future_value = 0.0,  $beginning = false)
	{
		if ($period < 1 || $period > $periods) {
			return \NAN;
		}

		if ($rate == 0) {
			return 0;
		}

		if ($beginning && $period == 1) {
			return 0.0;
		}

		$payment = $this->pmt_models($rate, $periods, $present_value, $future_value, $beginning);
		if ($beginning) {
			$erest = ($this->fv_models($rate, $period - 2, $payment, $present_value, $beginning) - $payment) * $rate;
		} else {
			$erest = $this->fv_models($rate, $period - 1, $payment, $present_value, $beginning) * $rate;
		}

		return $this->checkZero_models($erest);
	}

	function ppmt_models($rate,  $period,  $periods, $present_value,  $future_value = 0.0,  $beginning = false)
	{
		$payment = $this->pmt_models($rate, $periods, $present_value, $future_value, $beginning);
		$ipmt    = $this->ipmt_models($rate, $period, $periods, $present_value, $future_value, $beginning);

		return $payment - $ipmt;
	}

	function periods_models($rate, $payment, $present_value, $future_value,  $beginning = false)
	{
		$when = $beginning ? 1 : 0;

		if ($rate == 0) {
			return - ($present_value + $future_value) / $payment;
		}

		$initial = $payment * (1.0 + $rate * $when);
		return \log(($initial - $future_value * $rate) / ($initial + $present_value * $rate)) / \log(1.0 + $rate);
	}

	function fv_models($rate,  $periods, $payment, $present_value,  $beginning = false)
	{
		$when = $beginning ? 1 : 0;

		if ($rate == 0) {
			$fv = -($present_value + ($payment * $periods));
			return $this->checkZero_models($fv);
		}

		$initial  = 1 + ($rate * $when);
		$compound = \pow(1 + $rate, $periods);
		$fv       = - (($present_value * $compound) + (($payment * $initial * ($compound - 1)) / $rate));

		return $this->checkZero_models($fv);
	}

	function pv_models($rate,  $periods, $payment, $future_value,  $beginning = false)
	{
		$when = $beginning ? 1 : 0;

		if ($rate == 0) {
			$pv = -$future_value - ($payment * $periods);
			return $this->checkZero($pv);
		}

		$initial  = 1 + ($rate * $when);
		$compound = \pow(1 + $rate, $periods);
		$pv       = (-$future_value - (($payment * $initial * ($compound - 1)) / $rate)) / $compound;

		return $this->checkZero_models($pv);
	}

	function npv_models($rate, array $values)
	{
		$result = 0.0;

		for ($i = 0; $i < \count($values); ++$i) {
			$result += $values[$i] / (1 + $rate) ** $i;
		}

		return $result;
	}

	
	function checkZero_models($value, $epsilon =  1e-6)
	{
		return \abs($value) < $epsilon ? 0.0 : $value;
	}
}