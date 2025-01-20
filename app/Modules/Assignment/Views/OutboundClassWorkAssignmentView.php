<div class="row">
	<div class="card">
		<i class="text-muted"><small>*double click for assign</small></i>
		<div id="showloading" style="display:none">
			<div  class="d-flex align-items-center" >
				<strong role="status">Loading...</strong>
				<div class="spinner-border spinner-border-sm ms-auto text-success" aria-hidden="true"></div>
			</div>
		</div>
		<table class="table table-striped table-hover">
			<thead>
			<tr>
				<th>Class</th>
			<?php
				foreach ($team as $key => $value) {
					echo "<th><center>". $value['team_name']. "</center></th>";
				}
			?>
			</tr>
			</thead>
			<tbody>
			<?php
				foreach ($class as $key => $value) {
					echo "<tr ondblclick='setAssignment(".$value['classification_id'].")'>";
					echo "<td>". $value['classification_name']. "</td>";

					foreach ($team as $key => $valuex) {
						if(isset($assignment[$value['classification_id']])){
							if($assignment[$value['classification_id']]==$valuex['team_id']){
								echo "<td id='".$value['classification_id'].'_'.$valuex['team_id']."'><center><i class='bi bi-check-lg text-success'></i></center></td>";
							
							}else{
								echo "<td id='".$value['classification_id'].'_'.$valuex['team_id']."'></td>";
							}
						}else{
							echo "<td id='".$value['classification_id'].'_'.$valuex['team_id']."'></td>";
						}
					}

					echo "</tr>";
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	var set_loading = false;

	function setAssignment(id){
		var selr = id;
		if(set_loading){
			return false;
		}

		GLOBAL_MAIN_VARS['class_id'] = id;
		console.log('selr', selr);
		if (selr) {
			// if(can_call != "") {

			

			var buttons = {
				"success":
				{
					"label": "Submit",
					"className": "btn btn-primary btn-xs",
					"callback": function () {
						loading(true);
						
						$.ajax({
							type: "POST",
							url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/assignment/update_class_work_assignment/",
							async: true,
							data: { row: $('form').serializeArray() },
							dataType: "json",
							success:
								function (msg) {
									if (msg.success == true) {
										laodData();
										showInfo(msg.message, 1500);
									} else if (msg.success == false) {
										warningDialog(300, 100, "Warning!", msg.message);
									}
									loading(false);
								},
							error: function () { 
								loading(false);
								alert("Failed: update_class_work_assignment") ;
							}
						});
					}
				},
				"button":
				{
					"label": "Close",
					"className": "btn-sm"
				}
			}

			showCommonDialog(280, 290, 'Class Assignment', GLOBAL_MAIN_VARS["SITE_URL"] + 'assignment/assignment/editAssignment?id=' + id, buttons);
			// }
		} else {
			alert("No selected row");
		}
		//}
	}

	function laodData(){
		$.ajax({
			type: "GET",
			url: GLOBAL_MAIN_VARS["SITE_URL"] + "assignment/assignment/loadAssignment",
			async: true,
			dataType: "json",
			success:function (msg) {
					if (msg.success == true) {
						$('tbody center').remove();

						$.each(msg.data,function(i,val){
							$('#'+i+"_"+val).html("<center><i class='bi bi-check-lg text-success'></i></center>");
						})
					} else if (msg.success == false) {
						warningDialog(300, 100, "Warning!", msg.message);
					}
				},
			error: function () { 

			 }
		});
	}

	function loading(param){
		set_loading = param;
		if(param){
			$("#showloading").show();
		}else{
			$("#showloading").hide();
		}
	}
</script>
