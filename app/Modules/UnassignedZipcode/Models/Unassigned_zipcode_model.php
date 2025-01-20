<?php
namespace App\Modules\UnassignedZipcode\models;
use CodeIgniter\Model;

Class Unassigned_zipcode_model Extends Model 
{
    function get_unassigned_list(){
        $this->builder = $this->db->table('cms_zipcode_area_mapping');
        $this->builder->select('zip_code');
        $temp = $this->builder->get()->getResultArray();
		$zip = "";
		// print_r($temp);
		foreach($temp as $key => $value)
		{	
			if ($key != 0) {
				$zip .= ",";
			}
			$array_uniq = array_unique($value);
			
			$zip .= $array_uniq["zip_code"];//str_replace(",","','",);
		}
		$this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $this->builder->select('zip_code');
        $temp1 = $this->builder->get()->getResultArray();
		$zip1 = "";
		// print_r($temp1);
		foreach($temp1 as $value1)
		{
			$array_uniq1 = array_unique($value1);
			
			$zip1 .= $array_uniq1["zip_code"];//str_replace(",","','",);
		}
		$zip1 = substr($zip1, 0, -1);
		// print_r($zip1);

		$this->builder = $this->db->table('cpcrd_new');
        $this->builder->select('distinct(cr_zip_code)');
		if ($zip1) {
			$this->builder->whereNotIn('cr_zip_code', [$zip]);
			$this->builder->whereNotIn('cr_zip_code', [$zip1]);
		} else {
			$this->builder->whereNotIn('cr_zip_code', [$zip]);
		}
		$rResultBod = $this->builder->get()->getResultArray();
		// $query = $this->db->getLastQuery();
		// print_r($rResultBod);
		// print_r($query);
		if (!empty($rResultBod)) {
			$builder4 = $this->db->table('cms_zipcode_area_mapping');
			$builder4->select('zip_code');
			$builder4->where('is_active', '1');
			$rResultZipMapping = $builder4->get();

			// print_r($rResultZipMapping);
			$zipcodeList = array();

			foreach($rResultBod as $aRowBod){
				foreach($rResultZipMapping->getResultArray() as $rResultZip){
					if(!empty($rResultZip)){
						$zipCodeExplode = explode(',', $rResultZip['zip_code']); 
						$zipCodeExplodeNext = explode('-', $rResultZip['zip_code']); 
						foreach($zipCodeExplode as $azipCodeExplode =>$key){
							if($aRowBod != $azipCodeExplode){
								if(stripos ($azipCodeExplode,"-")){
									$zipCodeExplodeDown= $zipCodeExplodeNext[0];
									$zipCodeExplodeUp= $zipCodeExplodeNext[-1];
									foreach($zipCodeExplodeUp->getResultArray() as $rowZipCode){
										if($aRowBod != $zipCodeExplodeDown){
											array_push($zipcodeList, $aRowBod);
										}
										$zipCodeExplodeDown = $zipCodeExplodeDown+1;
									}
								}
								array_push($zipcodeList, $aRowBod);
							}
						}
					}
				}
			}
		}
    
        if ($zipcodeList > 0) {
            foreach ($zipcodeList[0] as $key => $value) {
                $result['header'][] = array('field' => $key);
            }
            $result['data'] = $zipcodeList;
        } else {
            $result['header'] = array();
            $result['data'] = array();
        }
        
        $rs =  $result;
        return $rs;
    }
    function isExistzipcode_area_mappingId($id){
        $this->builder = $this->db->table('cms_zipcode_area_mapping');
        $this->builder->where(array(
            'sub_area_id' => $id
        ));
        $query = $this->builder->get();
        if ($query->getNumRows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    function save_zipcode_area_mapping_assign($data){
        $this->builder = $this->db->table('cms_zipcode_area_mapping_temp');
        $return = $this->builder->insert($data);
        return $return;
    }
}