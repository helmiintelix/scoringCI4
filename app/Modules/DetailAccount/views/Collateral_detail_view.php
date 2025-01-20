<div class="row">
	<div class="col-sm-12" style="margin-bottom:2px;">
		<div class="col-sm-12 table-responsive" style="margin-bottom:2px;">
			<table class="table table-striped table table-bordered ">
				<thead>
					<tr>
						<th scope="col">collateral type</th>
						<th scope="col">Loan</th>
						<th scope="col">Market value</th>
						<th scope="col">liquidation value</th>
						<th scope="col">colleteral expiry date</th>
						<th scope="col">colleteral description</th>
						<th scope="col">latest appraisal date</th>
						<th scope="col">net realizable value</th>
					</tr>
				</thead>
				<tbody>
					<?php 
                            foreach ($collaterals as $key => $value) {
                                echo "<tr>";
                                echo "<td>".$value['collateral_type']."</td>";
                                echo "<td>".$value['loan_no']."</td>";
                                echo "<td>".number_format($value['market_value'])."</td>";
                                echo "<td>".number_format($value['liquidation_value'])."</td>";
                                echo "<td>".$value['colleteral_expiry_date']."</td>";
                                echo "<td>".$value['colleteral_description']."</td>";
                                echo "<td>".$value['latest_appraisal_date']."</td>";
                                echo "<td>".number_format($value['net_realizable_value'])."</td>";
                                echo "</tr>";
                            }
                        ?>
				</tbody>
			</table>
		</div>
	</div>
</div>