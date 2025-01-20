<div class="post3">
 <form>
 <table>
 	<tr>
 		<td>Class </td>
 		<td>
 			<div id="label_class_name" name="label_class_name"></div>
 			<input type="hidden" id="class_id" name="class_id" value="<?=$id?>"></td>
 	</tr>
 	<tr>
 		<td>Team</td>
 		<td>
 			<select class="form-control-itx" id="outbound_select" name="outbound_select" multiple="multiple" style="width:170px;height:110px;">
 			<option value="">[none]</option>
 			</select>
 		</td>
 	</tr>
 	<tr style="display: none;">
 		<td>Work Shift</td>
 		<td> <input type="text" class="form-control-itx" name="work_shift" value="1" style="width:150px" /> </td>
	</tr>
</table>
</form>

</div>
<script type="text/javascript" src="<?=base_url();?>modules/assignment/js/outbound_class_work_assignment_form.js"></script>