<!-- CUSTOMER INFORMATION START -->
<div class="card col-12 p-0" id="scrollspyHeading1">
	<div class="d-flex justify-content-between">
		<!-- CUST DATA -->
	
		<div class="col-8">
			<div class="container" style="height: 300px; overflow: auto;">
			<center>
				<b>PROFILE</b>
			</center>
				<div class="row p-2 d-flex flex-wrap">
					<!-- mulai loop -->
					<?php 
								 foreach($contracts as $row){
							?>
					<input type="hidden" id="hotNotes<?=$row["CM_CARD_NMBR"];?>" value="<?=$row["hot_notes"];?>">
					<input type="hidden" id="status_pengajuan<?=$row["CM_CARD_NMBR"];?>"
						value="<?=$row["status_pengajuan"];?>">
					<input type="hidden" id="tgl_pengajuan<?=$row["CM_CARD_NMBR"];?>"
						value="<?=$row["tgl_pengajuan"];?>">
					<input type="hidden" id="assignedAgent<?=$row["CM_CARD_NMBR"];?>" value="<?=$row["agent_name"];?>">
					<input type="hidden" id="lastNotes<?=$row["CM_CARD_NMBR"];?>" value="<?=$row["last_notes"];?>">
					<input type="hidden" id="agency_status<?=$row["CM_CARD_NMBR"];?>"
						value="<?=$row["CF_AGENCY_STATUS_ID"];?>">
					<div class="col-6 mb-2">
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								CIF No.
							</label>
							<input type="text" class="form-control form-control-sm" name="cif" id="cif"
								value="<?=$row["CM_CUSTOMER_NMBR"];?>" readonly
								style="pointer-events: none; ">
						</div>
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								Loan No.
							</label>
							<input type="text" class="form-control form-control-sm" name="loan" id="loan"
								value="<?=$row["CM_CARD_NMBR"];?>" readonly
								style="pointer-events: none; ">
						</div>
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								Name
							</label>
							<input type="text" class="form-control form-control-sm" name="name" id="name"
								value="<?=$row["CR_NAME_1"];?>" readonly
								style="pointer-events: none; ">
						</div>
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								DPD
							</label>
							<input type="text" class="form-control form-control-sm" name="dpd" id="dpd"
								value="<?=$row["DPD"];?>" readonly
								style="pointer-events: none; ">
						</div>
					</div>
					<div class="col-6 mb-2">
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								Product Type
							</label>
							<input type="text" class="form-control form-control-sm" name="product_type"
								id="product_type" value="<?=$row["CM_TYPE"];?>" readonly
								style="pointer-events: none; ">
						</div>
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								Due Date
							</label>
							<input type="text" class="form-control form-control-sm" name="due_date" id="due_date"
								value="<?=$row["CM_DTE_PYMT_DUE"];?>" readonly
								style="pointer-events: none; ">
						</div>
						<div class="mb-2">
							<label class="fs-6 text-capitalize">
								OS Balance
							</label>
							<input type="text" class="form-control form-control-sm" name="name" id="name"
								value="<?=$row["CR_NAME_1"];?>" readonly
								style="pointer-events: none; ">
						</div>
					</div>
					<?php 
								}
							 ?>
				</div>
			</div>
		</div>
		<!-- Profile Picture -->
		<div class="col-4 d-flex p-1">
			<div class="card" style="width:100%">
				<div class="card-header text-center fw-bold ">
					AGENT SCRIPT [<?=$subject?>]
				</div>
				<div class="card-body" style="font-size: 12px;">
				<?=$script?>
				</div>
			</div>
	</div>
</div>