<?php
if (! function_exists('generate_input_object')) {
	function generate_input_object($parameter, $row_data, $ref_list)
	{
		$str_obj = null;
		$arr_value = json_decode($row_data['parameter_value']);

		switch ($row_data['value_content']) {
			case "MULTIPLE_VALUE":
				// if(!empty($row_data["reference_table"]))
				// 	if(!empty($row_data["parameter_reference"])){
				// 		//echo "<pre>";
				// 		//print_r($ref_list);
				// 		//echo "</pre>";

				// 		$arr_reference = $ref_list[$row_data["parameter_reference"]];
				// 	}
				// 	else
				// 		$arr_reference = $ref_list[$row_data["reference_table"]];

				// else
				// 	$arr_reference = json_decode($row_data['parameter_reference'], TRUE);

				if (!empty($row_data["reference_table"]))
					$arr_reference = @$ref_list[@$row_data["reference_table"]];
				else
					$arr_reference = json_decode($row_data['parameter_reference'], TRUE);

				//$arr_reference = json_decode($row_data['parameter_reference']);
				//$arr_value = json_decode($arr_value[0]);
				//echo $row_data['parameter_reference'];
				//print_r($arr_reference);
				$attributes = 'class="form-control-itx" multiple="multiple" id="par_' . $parameter . '_' . $row_data['param_id'] . '" style="width: 320px;" data-placeholder="Choose a value..."';
				$str_obj .= form_dropdown('par_' . $parameter . '_' . $row_data['param_id'] . '[]', $arr_reference, $arr_value, $attributes);

				break;
			case "SINGLE_VALUE":
				if (!empty($row_data["reference_table"]))
					$arr_reference = @$ref_list[@$row_data["reference_table"]];
				else
					$arr_reference = json_decode(@$row_data['parameter_reference'], TRUE);

				$attributes = 'class="form-control-itx" id="par_' . $parameter . '_' . $row_data['param_id'] . '" style="width: 320px;"';
				$str_obj .= form_dropdown('par_' . $parameter . '_' . $row_data['param_id'], $arr_reference, @$arr_value[0], $attributes);

				break;
			case "TEXT":
				$attributes = array(
					'type'  => 'text',
					'name'  => 'par_' . $parameter . '_' . $row_data['param_id'],
					'id'    => 'par_' . $parameter . '_' . $row_data['param_id'],
					'value' => @$arr_value[0],
					'class' => 'form-control-itx',
					'style' => 'width: 320px; text-align: left'
				);
				$str_obj .= form_input($attributes);

				break;
			case "NUMBER":
				$attributes = array(
					'type'  => 'text',
					'name'  => 'par_' . $parameter . '_' . $row_data['param_id'],
					'id'    => 'par_' . $parameter . '_' . $row_data['param_id'],
					'value' => @$arr_value[0],
					'class' => 'form-control-itx',
					'style' => 'width: 200px; text-align: left',
					'onKeypress' => 'return numbersOnly(this, event)'
				);
				$str_obj .= form_input($attributes);

				break;
			case "NUMBER_RANGE":
				//$arr_value = json_decode($arr_value[0]);

				$str_obj .= ' From ';
				$attributes = array(
					'type'  => 'text',
					'name'  => 'par_' . $parameter . '_' . $row_data['param_id'] . '_start',
					'id'    => 'par_' . $parameter . '_' . $row_data['param_id'] . '_start',
					'value' => @$arr_value[0],
					'class' => 'form-control-itx',
					'style' => 'width: 120px; text-align: left',
					'onKeypress' => 'return numbersOnly(this, event)'
				);
				$str_obj .= form_input($attributes);
				$str_obj .= ' to ';
				$attributes = array(
					'type'  => 'text',
					'name'  => 'par_' . $parameter . '_' . $row_data['param_id'] . '_end',
					'id'    => 'par_' . $parameter . '_' . $row_data['param_id'] . '_end',
					'value' => @$arr_value[1],
					'class' => 'form-control-itx',
					'style' => 'width: 120px; text-align: left',
					'onKeypress' => 'return numbersOnly(this, event)'
				);
				$str_obj .= form_input($attributes);

				break;
			case "DATE":
				if (!empty($arr_value[0])) {
					$separator = '-';
					$arr_f_date = explode($separator, @$arr_value[0]);
					$f_date = @$arr_f_date[2] . $separator . @$arr_f_date[1] . $separator . @$arr_f_date[0];
				} else {
					$f_date = "";
				}

				$attributes = array(
					'type'  => 'text',
					'name'  => 'par_' . $parameter . '_' . $row_data['param_id'],
					'id'    => 'par_' . $parameter . '_' . $row_data['param_id'],
					'value' => @$f_date,
					'class' => 'form-control-itx date-picker',
					'style' => 'width: 120px;',
					'data-date-format' => 'dd-mm-yyyy'
				);
				$str_obj .= form_input($attributes);

				break;
			case "DATE_RANGE":
				if (!empty($arr_value[0])) {
					$separator = '-';
					$arr_f_date = explode($separator, @$arr_value[0]);
					$f_date = @$arr_f_date[2] . $separator . @$arr_f_date[1] . $separator . @$arr_f_date[0];

					$arr_t_date = explode($separator, @$arr_value[1]);
					$t_date = @$arr_t_date[2] . $separator . @$arr_t_date[1] . $separator . @$arr_t_date[0];
				} else {
					$f_date = "";
					$t_date = "";
				}

				$str_obj = '<div class="input-daterange input-group" style="width: 260px;">
					<input type="text" class="form-control-itx" id="par_' . $parameter . '_' . $row_data['param_id'] . '_start"  name="par_' . $parameter . '_' . $row_data['param_id'] . '_start" value="' . @$f_date . '" style="width: 120px;" />
					<span class="input-group-addon">
						<i class="fa fa-exchange"></i>
					</span>

					<input type="text" class="form-control-itx" id="par_' . $parameter . '_' . $row_data['param_id'] . '_end"  name="par_' . $parameter . '_' . $row_data['param_id'] . '_end" value="' . @$t_date . '" style="width: 120px;" />
				</div>';

				/*
				$str_obj .= ' From ';
				$attributes = array(
						'type'  => 'text',
						'name'  => 'f_'.$row_data['param_id'],
						'id'    => 'f_'.$row_data['param_id'],
						'value' => @$arr_value[0],
						'class' => 'form-control-itx date-picker',
						'style' => 'width: 120px;',
						'data-date-format' => 'dd-mm-yyyy'
				);
				$str_obj .= form_input($attributes);
				$str_obj .= ' to ';
				$attributes = array(
						'type'  => 'text',
						'name'  => 't_'.$row_data['param_id'],
						'id'    => 't_'.$row_data['param_id'],
						'value' => @$arr_value[1],
						'class' => 'form-control-itx date-picker',
						'style' => 'width: 120px;',
						'data-date-format' => 'dd-mm-yyyy'
				);
				$str_obj .= form_input($attributes);
				*/
				break;
			case "DAY":
				/*
				if(!empty($row_data["reference_table"]))
					if(!empty($row_data["parameter_reference"])){
						//echo "<pre>";
						//print_r($ref_list);
						//echo "</pre>";
						
						$arr_reference = $ref_list[$row_data["parameter_reference"]];
					}
					else
						$arr_reference = $ref_list[$row_data["reference_table"]];
				
				else
					$arr_reference = json_decode($row_data['parameter_reference'], TRUE);
				
				*/

				$arr_reference = $ref_list['days'];

				//$arr_reference = json_decode($row_data['parameter_reference']);
				//$arr_value = json_decode($arr_value[0]);
				//echo $row_data['parameter_reference'];
				//print_r($arr_reference);
				$attributes = 'class="form-control-itx" multiple="multiple" id="par_' . $parameter . '_' . $row_data['param_id'] . '" style="width: 320px;" data-placeholder="Choose a value..."';
				$str_obj .= form_dropdown('par_' . $parameter . '_' . $row_data['param_id'] . '[]', $arr_reference, $arr_value, $attributes);

				break;
			case "MONTH":
				/*
				if(!empty($row_data["reference_table"]))
					if(!empty($row_data["parameter_reference"])){
						//echo "<pre>";
						//print_r($ref_list);
						//echo "</pre>";
						
						$arr_reference = $ref_list[$row_data["parameter_reference"]];
					}
					else
						$arr_reference = $ref_list[$row_data["reference_table"]];
				
				else
					$arr_reference = json_decode($row_data['parameter_reference'], TRUE);
				*/

				$arr_reference = $ref_list['months'];

				//$arr_reference = json_decode($row_data['parameter_reference']);
				//$arr_value = json_decode($arr_value[0]);
				//echo $row_data['parameter_reference'];
				//print_r($arr_reference);
				$attributes = 'class="form-control-itx" multiple="multiple" id="par_' . $parameter . '_' . $row_data['param_id'] . '" style="width: 320px;" data-placeholder="Choose a value..."';
				$str_obj .= form_dropdown('par_' . $parameter . '_' . $row_data['param_id'] . '[]', $arr_reference, $arr_value, $attributes);

				break;
			case "YEAR":
				/*
				if(!empty($row_data["reference_table"]))
					if(!empty($row_data["parameter_reference"])){
						//echo "<pre>";
						//print_r($ref_list);
						//echo "</pre>";
				
						$arr_reference = $ref_list[$row_data["parameter_reference"]];
					}
					else
						$arr_reference = $ref_list[$row_data["reference_table"]];
				
				else
					$arr_reference = json_decode($row_data['parameter_reference'], TRUE);
				*/

				$arr_reference = $ref_list['years'];

				//$arr_reference = json_decode($row_data['parameter_reference']);
				//$arr_value = json_decode($arr_value[0]);
				//echo $row_data['parameter_reference'];
				//print_r($arr_reference);
				$attributes = 'class="form-control-itx" multiple="multiple" id="par_' . $parameter . '_' . $row_data['param_id'] . '" style="width: 320px;" data-placeholder="Choose a value..."';
				$str_obj .= form_dropdown('par_' . $parameter . '_' . $row_data['param_id'] . '[]', $arr_reference, $arr_value, $attributes);

				break;
			default:
				$str_obj .= $row_data['value_content'];
				break;
		}

		return $str_obj;
	}
}
