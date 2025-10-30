<?php
if ( ! function_exists('generate_dropdown')){	
	function generate_dropdown($obj_id, $data_list, $default_value, $is_multiple){
		$str_obj = null;
		
		if($is_multiple)
			$attributes = 'multiple="multiple" class="form-control-itx" id="'.$obj_id.'"';
		else
			$attributes = 'class="form-control-itx" id="'.$obj_id.'"';
		
		$str_obj = form_dropdown($obj_id, $data_list, $default_value, $attributes);
		
		return $str_obj;
	}
}