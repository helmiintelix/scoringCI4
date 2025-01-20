<?php

    use App\Models\Common_model;

    $Common_Model = new Common_model();
?>
<style>
    #table-wrapper {
        position: relative;
    }

    #table-scroll {
        height: 330px;
        overflow: auto;
        margin-top: 20px;
    }

    #table-wrapper table {
        width: 100%;

    }

    #table-wrapper table * {

        color: black;
    }

    #table-wrapper table thead th {
        background: yellow;
        vertical-align: middle;
    }

    #table-wrapper table thead th .text {
        position: absolute;
        top: -20px;
        z-index: 2;
        height: 20px;
        width: 90px;
        border: 1px solid black;
    }

    #yearpicker .ui-datepicker-calendar {
        display: none;
    }

    #yearpicker .ui-datepicker-month {
        display: none;
    }

    #yearpicker .ui-datepicker-prev {
        display: none;
    }

    #yearpicker .ui-datepicker-next {
        display: none;
    }
</style>

<div class="row">
    <div class="col-xs-12" align="center">
        <h3>Report <?=$title?></h3>
    </div>
    <form id="myReport">
        <?php
		$html = '';
		// print_r($filters);
		foreach($filters as $valFil){
			if($valFil['data_type'] == 'combo'){
				$html .= '<div class="col-sm-12 mb-2">';
				 $html .= '<div class="col-ld-4">';
					$html .= '<div class="form-group" id="">';
						$html .= '<label class="col-sm-2 control-label no-padding-right"> &nbsp;&nbsp;'.$valFil['label_name'].'</label>';
						$html .= '<div class="col-sm-2">';
						  
						if($valFil['data_content']){
								$x = explode(';', $valFil['data_content']);
								$arr_combo = array();
								foreach($x as $y=>$z){
									$arr_combo[$z] = $z;
								}
								$attributes = 'id="'.$valFil['field_name_id'].'" class="form-control"';
						$html .= form_dropdown($valFil['field_name_id'], $arr_combo, '', $attributes);
						}else{
							if(session()->get('LEVEL_GROUP')=='TEAM_LEADER'){
								$arrData = $Common_Model->get_record_list($valFil['select_query'], $valFil['table_reference'], $valFil['where_query'].' AND id="'.session()->get('USER_ID').'" ', $valFil['order_by_query']);
							}else{
								$arrData = $Common_Model->get_record_list($valFil['select_query'], $valFil['table_reference'], $valFil['where_query'], $valFil['order_by_query']);
							}
								
								$attributes = 'id="'.$valFil['field_name_id'].'" class="form-control"';
						$html .= form_dropdown($valFil['field_name_id'], $arrData, '', $attributes);
							
						}
						
						
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			  $html .= '</div>';
			}else if($valFil['data_type'] == 'text'){
				$html .= '<div class="col-sm-12 mb-2">';
				  $html .= '<div class="col-ld-3">';
					$html .= '<div class="form-group" id="">';
						$html .= '<label class="col-sm-2 control-label no-padding-right"> &nbsp;&nbsp;'.$valFil['label_name'].'</label>';
						$html .= '<div class="col-sm-2">';
							$html .= '<input type="text" class="form-control" name="'.$valFil['field_name_id'].'" id="'.$valFil['field_name_id'].'" />';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
			}else if($valFil['data_type'] == 'date'){
			 $html .= '<div class="col-sm-12 mb-2">';
				$html .= '<div class="col-ld-3">';
					$html .= '<div class="form-group" id="">';
						$html .= '<label class="col-sm-2 control-label no-padding-right"> &nbsp;&nbsp;'.$valFil['label_name'].'</label>';
						$html .= '<div class="col-sm-2">';
							$html .= '<input type="text" class="form-control date-picker" data-date-format="yyyy-mm-dd" name="'.$valFil['field_name_id'].'" id="'.$valFil['field_name_id'].'" placeholder="selected date" style="cursor: pointer" readonly />';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			 $html .= '</div>';
			}else if($valFil['data_type'] == 'year'){
			$html .= '<div class="col-sm-12 mb-2">';
				$html .= '<div class="col-ld-2">';
					$html .= '<div class="form-group" id="">';
						$html .= '<label class="col-sm-2 control-label no-padding-right"> &nbsp;&nbsp;'.$valFil['label_name'].'</label>';
						$html .= '<div class="col-sm-2">';
							$html .= '<select name="'.$valFil['field_name_id'].'" id="'.$valFil['field_name_id'].'">';
									$cur_year = date('Y');
									for($year = ($cur_year-5); $year <= ($cur_year+5); $year++) {
										if ($year == $cur_year) {
											$html .= '<option value="'.$year.'" selected="selected">'.$year.'</option>';
										} else {
											$html .= '<option value="'.$year.'">'.$year.'</option>';
										}
									}               
							$html .= '<select>';

						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
			}else if($valFil['data_type'] == 'month'){
			$html .= '<div class="col-sm-12 mb-2">';
				$html .= '<div class="col-ld-2">';
					$html .= '<div class="form-group" id="">';
						$html .= '<label class="col-sm-2 control-label no-padding-right"> &nbsp;&nbsp;'.$valFil['label_name'].'</label>';
						$html .= '<div class="col-sm-2">';
							
						$html .= '<select name="'.$valFil['field_name_id'].'" id="'.$valFil['field_name_id'].'" >';
							$html .= '<option value="">Select</option>';
								  $month = strtotime(date('Y').'-'.date('m').'-'.date('j').' - 12 months');
								  $end = strtotime(date('Y').'-'.date('m').'-'.date('j').' + 0 months');
								  while($month < $end){
									  $selected = (date('F', $month)==date('F'))? ' selected' :'';
									  $html .= '<option'.$selected.' value="'.date('m', $month).'">'.date('F', $month).'</option>'."\n";
									  $month = strtotime("+1 month", $month);
								  }
						$html .= '</select>';
						
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
			}
			
		}
		echo $html;
	?>
    </form>
    <div class="col-sm-12">
        <div class="col-xs-12">
            <button type="button" class="btn btn-secondary btn-sm" style="height: 32px;" id="submitButton">Search<i
                    class="icon-search icon-on-right bigger-110"></i></button>
            <button type="button" class="btn btn-success btn-sm" style="height: 32px;" id="btnSaveToExcel">Save to
                Excel<i class="icon-table icon-on-right bigger-110"></i></button>
        </div>
    </div>
    <br>
    <div class="col-sm-12" id="table-wrapper">
        <div class="col-xs-12 table-responsive" id="table-scroll">
            <?=$header_design?>
        </div>
    </div>
</div>
<br>


<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/escalation_monitoring_detail/js/escalation_monitoring_detail.js?v=<?= rand() ?>">
</script>