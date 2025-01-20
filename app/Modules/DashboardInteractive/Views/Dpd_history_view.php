<div class="row">
	<div class="col-sm-12">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>DPD</th>
					<th>delq</th>
					<th>date</th>
				</tr>
			</thead>
			<tbody>
				<?php
                foreach ($data as $key => $value) {
                    echo "<tr>";
                    echo "<td>".$value['dpd']."</td>";
                    echo "<td>".$value['delq']."</td>";
                    echo "<td>".$value['data_date']."</td>";
                    echo "</tr>";
                }
                ?>
			</tbody>
		</table>
	</div>
</div>