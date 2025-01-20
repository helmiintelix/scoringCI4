<?php
namespace App\Modules\SuratPeringatan\models;
use App\Models\Common_model;
use CodeIgniter\Model;


Class Surat_peringatan_model Extends Model 
{
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new Common_model;
    }
    function get_sp_due_list(){
        $this->builder = $this->db->table('cpcrd_new a');
        $select = array(
            'no_sp',
			'sp.id',
			'a.CM_CUSTOMER_NMBR',
			'a.CR_NAME_1',
			'a.CM_CARD_NMBR',
			'sp.type_sp jenis_sp',
			'a.ACCOUNT_TAGGING',
			'a.CM_STATUS_DESC',
			'C.CM_SUB_PRDK_CTG',
			'a.CM_DOMICILE_BRANCH',
			'a.CM_CYCLE',
			'DATE_FORMAT(a.CM_DTE_PYMT_DUE,"%d-%M-%Y")CM_DTE_PYMT_DUE',
			'a.CM_BUCKET',
			'a.CM_TOT_BALANCE',
			'a.CM_BLOCK_CODE',
			'DATE_FORMAT(a.CM_DTE_BLOCK_CODE,"%d-%M-%Y")CM_DTE_BLOCK_CODE',
			'a.CLASS',
			'IF(b.flag_tmp = "1", "<span class=\"label label-sm label-success\">APPROVED</span>", IF(b.flag_tmp = "2", "<span class=\"label label-sm label-danger\">REJECTED</span>", IF(b.flag_tmp = "0", "<span class=\"label label-sm label-warning\">WAITING APPROVAL</span>", ""))) AS flag_tmp'
        );
        $this->builder->join('cms_letter_due AS sp', 'a.CM_CARD_NMBR = sp.account_no');
        $this->builder->join('cpcrd_ext AS C', 'a.CM_CARD_NMBR = C.CM_CARD_NMBR');
        $this->builder->join('cms_account_last_status b', 'b.account_no = a.CM_CARD_NMBR');
        $this->builder->where('no_sp is not null');
        $this->builder->select( str_replace(' , ', ' ', implode(', ', $select)), false);
        $rResult = $this->builder->get();
        $return = $rResult->getResultArray();
        if ($rResult->getNumRows() > 0) {
            foreach ($rResult->getResultArray()[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $return;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
        // return $return;
    }

    function create_sp_html($no_sp, $type){
        $arr_no_sp = explode("xsplitx", $no_sp ?? '');
		$no_sp = str_replace("xsplitx", "','", $no_sp ?? '');

        $this->builder = $this->db->table('cpcrd_new a');
        $this->builder->select("a.*, b.*, 
        DATE_FORMAT(CM_DTE_LST_PYMT, '%d %M %Y') as last_payment_date, 
        CURDATE() as tgl, 
        a.DPD as DPD_CETAK");
        $this->builder->join('cms_letter_due b', 'a.cm_card_nmbr = b.account_no');
        $this->builder->join('cpcrd_ext c', ' c.CM_CARD_NMBR=a.CM_CARD_NMBR');
        $this->builder->whereIn('no_sp', $no_sp);
        $this->builder->orderBy('no_sp');
        $query = $this->builder->get();
        $html_content = "";
		$total_acc = count($query->getResultArray());
		$jumlah = 0;

        foreach ($query->getResultArray() as $cust_data) {
			$jumlah++;

			$type_sp = $cust_data["type_sp"];
			$ahtml = $this->Common_model->get_record_values("content", "cms_letter_template", "letter_id='" . $type_sp . "'", "");
			$address = $this->Common_model->get_record_values("*", "cpcrd_ext", "cm_card_nmbr	='" . $cust_data["CM_CARD_NMBR"] . "'", "");

			$query = $this->db->query("select count(*)jml from cms_letter_history where jenis_sp ='" . $type_sp . "'");
			foreach ($query->getResultArray() as $data) {
				$jml_sp = $data["jml"] + 1;
			}
			$no_sp = $cust_data["no_sp"];

			$query = $this->db->query("select no_sp,date_format(created_time,'%d %M %Y')created_time from cms_letter_history where jenis_sp ='SP1' and account_no = '" . $cust_data["CM_CARD_NMBR"] . "' order by created_time desc limit 1");
			$no_sp1 = "";
			$tgl_sp1 = "";
			foreach ($query->getResultArray() as $data) {
				$no_sp1 = $data["no_sp"];
				$tgl_sp1 = $data["created_time"];
			}

			$query = $this->db->query("select no_sp,date_format(created_time,'%d %M %Y')created_time from cms_letter_history where jenis_sp ='SP2' and account_no = '" . $cust_data["CM_CARD_NMBR"] . "' order by created_time desc limit 1");
			$no_sp2 = "";
			$tgl_sp2 = "";
			foreach ($query->getResultArray() as $data) {
				$no_sp2 = $data["no_sp"];
				$tgl_sp2 = $data["created_time"];
			}
			$query2 = $this->db->query("select * from cms_payment_history where CM_CARD_NMBR in ('" . $cust_data["CM_CARD_NMBR"] . "') ");
			$data_payment = $query2->getResultArray();

			$content = "<style>.page_break { page-break-before: always; }</style>" . $ahtml["content"];

			$content = str_replace("[[CM_CARD_NMBR]]", $cust_data["CM_CARD_NMBR"] ?? '', $content ?? '');
			$content = str_replace("[[CR_NAME_1]]", $cust_data["CR_NAME_1"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CUSTOMER_NMBR]]", $cust_data["CM_CUSTOMER_NMBR"] ?? '', $content ?? '');
			$content = str_replace("[[no_sp]]", $no_sp ?? '', $content ?? '');
			$content = str_replace("[[no_sp1]]", $no_sp1 ?? '', $content ?? '');
			$content = str_replace("[[no_sp2]]", $no_sp2 ?? '', $content ?? '');
			$content = str_replace("[[tgl_sp1]]", $this->Common_model->MonthIndo($tgl_sp1) ?? '', $content ?? '');
			$content = str_replace("[[tgl_sp2]]", $this->Common_model->MonthIndo($tgl_sp2) ?? '', $content ?? '');
			$content = str_replace("[[date]]", $this->Common_model->MonthIndo($cust_data["tgl"]) ?? '', $content ?? '');
			$content = str_replace("[[CM_AMOUNT_DUE]]", 'Rp. ' . number_format($cust_data["CM_AMOUNT_DUE"]) ?? '', $content ?? '');
			$content = str_replace("[[CM_TOT_BALANCE]]", 'Rp. ' . number_format($cust_data["CM_TOT_BALANCE"]) ?? '', $content ?? '');
			$content = str_replace("[[CM_COLLECTIBILITY]]", $cust_data["CM_COLLECTIBILITY"] ?? '', $content ?? '');
			$content = str_replace("[[CR_NAME_3]]", $cust_data["CR_NAME_3"] ?? '', $content ?? '');
			$content = str_replace("[[CR_ZIP_CODE]]", $cust_data["CR_ZIP_CODE"] ?? '', $content ?? '');
			$content = str_replace("[[CM_DTE_LST_PYMT]]", $cust_data["last_payment_date"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CURR_ADDR]]", $address["CM_CURR_ADDR"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CURR_CITY]]", $address["CM_CURR_CITY"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CURR_SUBDIST]]", $address["CM_CURR_SUBDIST"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CURR_DISTRICT]]", $address["CM_CURR_DISTRICT"] ?? '', $content ?? '');
			$content = str_replace("[[CM_CURR_PROVINCE]]", $address["CM_CURR_PROVINCE"] ?? '', $content ?? '');
			$content = str_replace("[[total_os]]",  'Rp. ' . number_format($cust_data["outstanding"]) ?? '', $content ?? '');
			$content = str_replace("[[due_date]]",  $this->Common_model->MonthIndo($cust_data["CM_DTE_PYMT_DUE"]) ?? '', $content ?? '');
			$content = str_replace("[[home_phone]]",  $cust_data["CR_HOME_PHONE"] ?? '', $content ?? '');
			$content = str_replace("[[mobile_phone]]",  $cust_data["CR_HANDPHONE"] ?? '', $content ?? '');
			$content = str_replace("[[office_phone]]",  $cust_data["CR_OFFICE_PHONE"] ?? '', $content ?? '');
			if (@$data_payment["pay_amount"] != null) {
				$content = str_replace("[[pay_amount]]", $data_payment["pay_amount"] ?? '', $content ?? '');
			} else {
				$content = str_replace("[[pay_amount]]", "-", $content ?? '');
			}

			$html_content .= $content;

			if ($jumlah != $total_acc) {
				$html_content .= "<div class='page_break'></div>";
			}

			if ($type == "print") {
				$html_content_clean = addslashes($content);
                $builder = $this->db->table('cms_letter_due');
                $builder->select('uuid() as uuid, id, account_no, type_sp, ' . session()->get('USER_ID') . ' as user_id, now() as current_time, ' . $html_content_clean . ' as html_content_clean, ' . $no_sp . ' as no_sp, ' . $cust_data['DPD_CETAK'] . ' as DPD_CETAK, ' . $cust_data['CM_OS_BALANCE'] . ' as CM_OS_BALANCE, ' . $cust_data['CM_DTE_PYMT_DUE'] . ' as CM_DTE_PYMT_DUE, ' . $cust_data['CM_CUSTOMER_NMBR'] . ' as CM_CUSTOMER_NMBR');
                $builder->where('type_sp', $cust_data['type_sp']);
                $builder->where('no_sp', $cust_data['no_sp']);
                $datain = $builder->get()->getResultArray();
                
                $data['id'] = $datain['uuid'];
                $data['letter_id'] = $datain['id'];
                $data['account_no'] = $datain['account_no'];
                $data['jenis_sp'] = $datain['type_sp'];
                $data['user_id'] = $datain['user_id'];
                $data['created_time'] = $datain['current_time'];
                $data['html_content'] = $datain['html_content_clean'];
                $data['no_sp'] = $datain['no_sp'];
                $data['dpd'] = $datain['DPD_CETAK'];
                $data['outstanding'] = $datain['CM_OS_BALANCE'];
                $data['due_date'] = $datain['CM_DTE_PYMT_DUE'];
                $data['no_cif'] = $datain['CM_CUSTOMER_NMBR'];

                $builder2 = $this->db->table('cms_letter_history');
                $builder2->insert($data);
			}
		}

        return $html_content;
    }
}