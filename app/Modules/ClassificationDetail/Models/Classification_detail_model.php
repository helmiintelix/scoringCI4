<?php
namespace App\Modules\ClassificationDetail\models;
use CodeIgniter\Model;

Class Classification_detail_model Extends Model 
{

    function get_record_list($fieldName, $tableName, $criteria, $orderBy)
	{	
		$arr_data = array();
        
		$builder = $this->db->table($tableName);
		$builder->select($fieldName);
		
		if ($criteria)
		{
			$builder->where($criteria);
		}
		
		if ($orderBy)
		{
			$builder->orderBy($orderBy);
		}
		
		$query = $builder->get();
		
		if ($query->getNumRows())
		{
			foreach ($query->getResult() as $row)
			{
				$arr_data[$row->value] = $row->item;
			}
		}
		
		return $arr_data;
	}
    function get_parameter_list($data){
        $param = $data['param'];
        $table = $data['table'];
        $where = $data['where'];
        if (empty($param)) {
			return;
		}
        if (!empty($table)) {
            if (!empty($where)) {
                $list = $this->get_record_list("DISTINCT(" . $param . ") value, " . $param . " AS item", $table, $param . " !='null' AND CM_ADDRESS_TYPE = '" . $where . "'", $param);
            } else {
                $list = $this->get_record_list("DISTINCT(" . $param . ") value, " . $param . " AS item", $table, $param . " !='null'", $param);
            }
            
        } else {
            switch ($param) {
                case "TIERING_ID":
					$list = $this->get_record_list("distinct(tiering_id) AS value, tiering_id AS item", "cpcrd_new", "", "tiering_id", FALSE);
					break;
				case "ACCOUNT_TAGGING":
					$list = $this->get_record_list("value, description AS item", "cms_reference", "reference = 'ACCOUNT_TAGGING' and flag='1'", "description", false);
					// $list = $this->get_record_list("distinct(tiering_id) AS value, tiering_id AS item", "cpcrd_new", "", "tiering_id", FALSE);
					break;
				case "TIERING_LABEL":
					$list = $this->get_record_list("distinct(tiering_label) AS value, tiering_label AS item", "cpcrd_new", "", "tiering_label", FALSE);
					break;
				case "CLASS_ID":
					$list = $this->get_record_list("classification_id AS value, concat(classification_id,'-',classification_name) AS item", "cms_classification", "1=1", "classification_name");
					break;
				case "CM_ZIP_REC":
				case "REGIONAL":
					$list = $this->get_record_list("real_name AS value, real_name AS item", "cms_master_regional", "flag=1 group by real_name", "id");
					break;
				case "DPDREMINDER":
					$list = $this->get_record_list("DPD_reminder", "cpcrd_new", "", "");
					break;
				case "AGENT_ID":
					$list = $this->get_record_list("id AS value, concat(id,'-',name) AS item", "cc_user", "id is not null", "name");
					break;

				case "USER_DEFINED_STATUS":
					$list = $this->get_record_list("id AS value, concat(id,'-',short_description) AS item", "cms_user_defined_status", "id is not null ", "id");
					break;
				case "CAMPAIGN_ID":
					$list = $this->get_record_list("id AS value, concat(id,'-',description) AS item", "cms_campaign_table", "id is not null ", "id");
					break;
				case "CM_COLLECTION_CENTER":
					$list = $this->get_record_list("kcu_id AS value, concat(kcu_id,'-',kcu_name) AS item", "cms_kcu", "kcu_id is not null ", "kcu_id");
					break;
				case "BRANCH_CODE":
					$list = $this->get_record_list("kcu_id value, concat(kcu_id,'-',kcu_name) AS item", "cms_cabang", "kcu_id !='null'", "kcu_id");
					break;
				case "CM_DOMICILE_BRANCH":
					$list = $this->get_record_list("branch_id value, concat(branch_id,'-',branch_name) AS item", "cms_branch", "branch_id !='null'", "branch_id");

					break;
				case "BRANCH_ID_NAME":
					$list = $this->get_record_list("branch_id AS value, concat(branch_id,'-',branch_name) AS item", "cms_branch", "is_active='1' ", "id");
					break;
				case "AREA_ID_NAME":
					$list = $this->get_record_list("area_id AS value, concat(area_id,'-',area_name) AS item", "cms_area_branch", "is_active='1' ", "id");
					break;
				case "AREATAGIH_ID_NAME":
					$list = $this->get_record_list("area_tagih_name AS value, concat(area_tagih_id,'-',area_tagih_name) AS item", "cms_area_tagih", "is_active='1' ", "id");
					break;
				case "CATEGORY_LOV":
					$list = $this->get_record_list("id AS value, concat(id,'-',category_name) AS item", "cms_lov_registration", "is_active='1' ", "id");
					break;
                case "CALL_VISIT_RESULT":
                    $this->builder = $this->db->table('cms_lov_relation');
                    $this->builder->select('GROUP_CONCAT(lov3_category) AS lov3_category');
                    $this->builder->where('lov3_category IS NOT NULL', null, false);
                    $this->builder->where('is_active', '1');
                    $this->builder->where('type_collection', 'FieldColl');
                    $query = $this->builder->get()->getResultArray();
                    $list = array();
                    foreach ($query as $row) {
						$lov_category_id_row = explode(',', $row['lov3_category']);
						foreach ($lov_category_id_row as $value) {
							// $label_id = $this->common_model->get_record_values("category_name", "cms_lov_registration", "id = '".$value."'");
							$label_id = $this->get_record_list("id value, category_name AS item", "cms_lov_registration", "id='" . $value . "'", "id");
							// array_push($list);
							$list[$value] = $label_id;
							// var_dump($label_id);
						}
					}
                    break;
                case "CALL_RESULT":
                    $this->builder = $this->db->table('cms_lov_relation');
                    $this->builder->select('GROUP_CONCAT(lov3_category) AS lov3_category');
                    $this->builder->where('lov3_category IS NOT NULL', null, false);
                    $this->builder->where('is_active', '1');
                    $this->builder->where('type_collection', 'TeleColl');
                    $query = $this->builder->get()->getResultArray();
                    $list = array();
                    foreach ($query as $row) {
						$lov_category_id_row = explode(',', $row['lov3_category']);
						foreach ($lov_category_id_row as $value) {
							// $label_id = $this->common_model->get_record_values("category_name", "cms_lov_registration", "id = '".$value."'");
							$label_id = $this->get_record_list("id value, category_name AS item", "cms_lov_registration", "id='" . $value . "'", "id");
							// array_push($list);
							$list[$value] = $label_id;
							// var_dump($label_id);
						}
					}
                    break;
                case "TIERING_TABLE":
                    $list = $this->get_record_list("id AS value, concat(id,'-',name) AS item", "sc_scoring_tiering", "is_active='1' ", "id");
                    break;
                case "PRODUK_KODE_NAMA":
                    $list = $this->get_record_list("Kode_Produk value, concat(Kode_Produk,'-',Nama_Produk) AS item", "stg_cc_masterproduct", "Kode_Produk!='null'", "Kode_Produk");
                    break;
                case "KATEGORI_KODE_NAMA":
                    $list = $this->get_record_list("DISTINCT(Kode_Kategori) value, concat(Kode_Kategori,'-',Nama_Kategori) AS item", "stg_cc_masterproduct", "Kode_Produk!='null'", "Kode_Kategori");
                    break;
                case "BUCKET":
                    // $list = $this->get_record_list("value, description AS item", "cms_reference", "reference ='".$this->input->get_post('param', true)."'", "value");    
                    $list = $this->get_record_list("bucket_id value, bucket_label AS item", "cms_bucket", "is_active ='1'", "bucket_label");
                    break;
                case "CM_STATUS":
                    $list = $this->get_record_list("value, concat(value,'-',description) AS item", "cms_reference", "reference ='STATUS_REKENING_PL'", "value") + $this->get_record_list("value, concat(value,'-',description) AS item", "cms_reference", "reference ='STATUS_REKENING_CC'", "value");
                    break;
                case "PRODUCT":
                    $this->builder = $this->db->table('acs_cms_product');
                    $this->builder->select('productcode');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["productcode"]] = $value["productcode"];
                    }
                    break;
                case "PRODUCTSUBCATEGORY":
                    $this->builder = $this->db->table('acs_cms_product');
                    $this->builder->select('productsubcategory');
                    $this->builder->groupBy('productsubcategory');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["productsubcategory"]] = $value["productsubcategory"];
                    }
                    break;
                case "PRODUCTTYPE":
                    $this->builder = $this->db->table('acs_cms_product');
                    $this->builder->select('productcode');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["productcode"]] = $value["productcode"];
                    }
                    break;
                case "ORIGINATION":
                    $this->builder = $this->db->table('acs_cms_channel_id');
                    $this->builder->select('originationid, originationiddesc');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["originationid"]] = $value["originationiddesc"];
                    }
                    break;
                case "APPLICATIONTYPE":
                    $this->builder = $this->db->table('acs_cms_application_type');
                    $this->builder->select('applicationtype, applicationtypedesc');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["applicationtype"]] = $value["applicationtypedesc"];
                    }
                    break;
                case "TRANSACTIONTYPE":
                    $this->builder = $this->db->table('acs_cms_transaction_type');
                    $this->builder->select('transactiontype, transactiontypedesc');
                    $data = $this->builder->get()->getResultArray();
                    foreach ($data as $value) {
                        $list[$value["transactiontype"]] = $value["transactiontypedesc"];
                    }
                    break;
                case "NEW_TO_M1_FLAG":
                    $list = array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    );
                    // $sql = "select transactiontype,transactiontypedesc from acs_cms_transaction_type";
                    // $data = $this->db->query($sql)->result_array();
                    // foreach($data as $value)
                    // {
                    // 	$list[$value["transactiontype"]] = $value["transactiontypedesc"];
                    // }
                    break;
                default:
					$list = $this->get_record_list("value, concat(value,'-',description) AS item", "cms_reference", "reference ='" . $param . "'", "value");
                    break;
            }
        }
		return $list;
        
    }
    function get_parameter_list_khusus_lov($data)
	{
        $param = $data['param'];
        $table = $data['table'];
        $where = $data['where'];
		if (empty($param)) {
			return;
		}

		if (!empty($table)) {
			if (!empty($where)) {
                $this->builder = $this->db->table($table);
                $this->builder->select($param);
                $this->builder->where('type_collection', $where);
                $query = $this->builder->get()->getRow();

				$list = $this->get_record_list("DISTINCT id value, category_name AS item", "cms_lov_registration", "id in  ('" . str_replace(",", "','", $query->lov3_category) . "') AND is_active = '1'", "id");
			} else {
				$list = $this->get_record_list("DISTINCT(" . $param . ") value, " . $param . " AS item", $table, $param . " !='null'", $param);
			}
		}
		return $list;
	}

	function get_parameter_list_tree($data)
	{
        $param = $data['param'];
		if (empty($param)) {
			return;
		}
		switch ($param) {
			case "CLASS_ID":
                $this->builder = $this->db->table('cms_classification');
                $this->builder->select('classification_id as id,classification_name as description');
				//      $this->db->where("product_group", $this->input->get_post('segmentasi', true));
				break;
			case "CM_ZIP_REC":
			case "CIF_REGIONAL":
                $this->builder = $this->db->table('cms_zip_reg');
                $this->builder->select('zip_reg as id,zip_reg as description');
				$this->builder->where("zip_reg is not null");
				$this->builder->groupBy("zip_reg");
				break;
		}
		$list = "";
		$rResult = $this->builder->get()->getResultArray();
		//var_dump($rResult->result_array());
		foreach ($rResult as $aRow) {
			$list .= '<li>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="ace" value="' . $aRow['id'] . '" id="' . $aRow['id'] . '" name="product-list[]" />
										<span class="lbl"> ' . $aRow['description'] . '</span>
									</label>
								</div>
							
							';

			$list .=  '</li>';
		}
		return $list;
	}
    
}