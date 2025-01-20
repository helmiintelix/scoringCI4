<?php
   $DB = \Config\Database::connect();
   $this->db = db_connect();
?>
<style>
	ul.checktree-root,
	ul#tree ul {
		list-style: none;
	}

	ul.checktree-root label {
		font-weight: normal;
		position: relative;
	}

	ul.checktree-root label input {
		position: relative;
		top: 2px;
		left: -5px;
	}
</style>

<script>
	function validateForm() {
		var x = document.forms["form_add_user"]["txt-group-id"].value;
		if (x == "test") {
			alert("isinya test");
			return false;
		}
	}
</script>

<form role="form" class="needs-validation" id="form_add_user" name="form_add_user" onsubmit="return validateForm()" novalidate method="post">
	<div>
		<label for="form-field-select-2">ID</label>
		<input type="text" id="txt-group-id" name="txt-group-id" class="form-control mandatory" required />
	</div>
	<br />
	<div>
		<label for="form-field-select-2">USER GROUP NAME</label>
		<input type="text" id="txt-group-name" name="txt-group-name" class="form-control mandatory" required />
	</div>
	<br />

	<div>
		<label for="form-field-select-2">DESCRIPTION</label>
		<input type="text" id="txt-group-description" name="txt-group-description" class="form-control" />
	</div>
	<br />

	<div>
		<label for="form-field-select-2">LEVEL</label>
		<?php
            $attributes = 'class="form-control mandatory" id="opt-hierarki" data-plform-check-inputholder ="[select]" required';
            echo form_dropdown('opt-heirarki', $level_master, "", $attributes);
		?>
	</div>
	<br />
    <div>
        <label for="btnradio_typeCollection1" class="fs-6 text-capitalize">COLLECTION TYPE</label><br>
        <div class="btn-group" role="group" id="radiotipecollectin" aria-label="Basic radio toggle button group" style="width: 100%;">
            <input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection1" typeColl='TELECOLL' autocomplete="off" checked>
            <label class="btn btn-outline-success" for="btnradio_typeCollection1">TELECOLL</label>

            <input type="radio" class="btn-check mandatory" name="btnradio_typeCollection" id="btnradio_typeCollection2" typeColl='FIELDCOLL' autocomplete="off" >
            <label class="btn btn-outline-primary" for="btnradio_typeCollection2">FIELDCOLL</label>
        </div>
        <input type='hidden' name='opt-type_collection' id="opt-type_collection" value='TELECOLL'/>
    
    </div>

	<!-- <div>
		<label for="form-field-select-2">Use Dashboard</label>
		<div class="btn-group" role="group" id="radiousedashboard" aria-label="Basic radio toggle button group" style="width: 100%;">
			<input type="radio" class="btn-check mandatory" name="btnradio_useDashboard" id="btnradio_useDashboard1" typeuseDashboard='YES' autocomplete="off" checked>
			<label class="btn btn-outline-success" for="btnradio_useDashboard1">YES</label>

			<input type="radio" class="btn-check mandatory" name="btnradio_useDashboard" id="btnradio_useDashboard2" typeuseDashboard='NO' autocomplete="off">
			<label class="btn btn-outline-primary" for="btnradio_useDashboard2">NO</label>
		</div>
		<input type='hidden' name='opt-use-dashboard' id="opt-use-dashboard" value='YES' />
	</div>
	<br />
	<div>
		<label for="form-field-select-2">Use Telephony</label>
		<div class="btn-group" role="group" id="radiousetelephony" aria-label="Basic radio toggle button group" style="width: 100%;">
			<input type="radio" class="btn-check mandatory" name="btnradio_useTelephony" id="btnradio_useTelephony1" typeuseTelephony='YES' autocomplete="off" checked>
			<label class="btn btn-outline-success" for="btnradio_useTelephony1">YES</label>

			<input type="radio" class="btn-check mandatory" name="btnradio_useTelephony" id="btnradio_useTelephony2" typeuseTelephony='NO' autocomplete="off">
			<label class="btn btn-outline-primary" for="btnradio_useTelephony2">NO</label>
		</div>
		<input type='hidden' name='opt-use-telephony' id="opt-use-telephony" value='YES' />
	</div> -->
	<br />

	



	<style>
		ul.checktree-root,
		ul#tree ul {
			list-style: none;
		}

		ul.checktree-root label {
			font-weight: normal;
			position: relative;
		}

		ul.checktree-root label input {
			position: relative;
			top: 2px;
			left: -5px;
		}

		.multiple-checkbox {
			padding-left: 20px;
		}

		.multiple-checkbox li {
			margin-top: 4px;
		}

		.multiple-checkbox .custom-control-label {
			font-weight: 600;
			color: #666;
		}

		.custom-control-label::before {
			border-radius: 3px;
		}

		.custom-control-input:checked~.custom-control-label::after {
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23fff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e")
		}

		.custom-control-input:indeterminate~.custom-control-label::after {
			background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3e%3cpath stroke='%23fff' d='M0 2h4'/%3e%3c/svg%3e") !important;
			border-radius: 3px;
			color: #fff;
			border-color: #007bff;
			background-color: #007bff;
		}
	</style>

	<div class="container my-3">
		<div class="row">
			<div class="col-md-12">
				<div class="card common-table-card mb-4">
					<div class="card-header text-muted"><strong>Choose authority</strong></div>
					<div class="card-body">
						<input type="checkbox" class="custom-control-input" name="all" id="all">
						<label class="custom-control-label" for="all">
							<h5><strong>Check Or Uncheck All</strong></h5>
						</label>

						<!-- <input type="checkbox" class="custom-control-input" name="uall" id="uall">
            <label class="custom-control-label" for="uall">All Uncheck</label> -->
						<ul class="multiple-checkbox" id="tree">
							<?php 

							$builder = $this->db->table('cc_menu');
                            $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                    ->where('parent_id', '')
                                    ->whereIn('menu_type', ['url', 'api'])
                                    ->orderBy('order_num', 'asc');
                            
                            $main_menu = $builder->get()->getResultArray();

							foreach ($main_menu as $aRow) {
							?>
								<li>
									<input type="checkbox" class="custom-control-input" value="<?= $aRow['menu_id'] ?>" id="<?= $aRow['menu_id'] ?>" name="menu-item[]">
									<label class="custom-control-label"><strong> <?= $aRow['menu_desc'] ?> </strong></label>
									<!--List Step-2 -->
									<?
									$builder = $this->db->table('cc_menu');

                                    $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                            ->where('parent_id', $aRow['menu_id'])
                                            ->whereIn('menu_type', ['url', 'api'])
                                            ->orderBy('order_num', 'asc');
                                    
                                    $sub_menu = $builder->get();
									if ($sub_menu->getNumRows() > 0) {
										echo '<ul> ';
										foreach ($sub_menu->getResultArray() as $bRow) {
									?>
								<li>
									<input type="checkbox" class="custom-control-input" value="<?= $bRow['menu_id'] ?>" id="<?= $bRow['menu_id'] ?>" name="menu-item[]">
									<label class="custom-control-label"><?= $bRow['menu_desc'] ?></label>

									<?
										$builder = $this->db->table('cc_menu');

                                        $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                                ->where('parent_id', $bRow['menu_id'])
                                                ->whereIn('menu_type', ['url', 'api'])
                                                ->orderBy('order_num', 'asc');
                                        
                                        $sub_sub_menu = $builder->get();

											if ($sub_sub_menu->getNumRows() > 0) {
												echo '<ul> '; //start ul subsubmenu
												foreach ($sub_sub_menu->getResultArray() as $cRow) {
									?>
								<li>
									<input type="checkbox" class="custom-control-input" value="<?= $cRow['menu_id'] ?>" id="<?= $cRow['menu_id'] ?>" name="menu-item[]">
									<label class="custom-control-label"><?= $cRow['menu_desc'] ?></label>

									<?
													$builder = $this->db->table('cc_menu');

                                                    $builder->select('menu_id, menu_desc, parent_id, order_num, url, icon')
                                                            ->where('parent_id', $cRow['menu_id'])
                                                            ->whereIn('menu_type', ['url', 'api'])
                                                            ->orderBy('order_num', 'asc');
                                                    
                                                    $button_sub_sub_menu = $builder->get();

													if ($button_sub_sub_menu->getNumRows() > 0) {
														echo '<ul> '; //start ul cek button
														foreach ($button_sub_sub_menu->getResultArray() as $dRow) {
									?>
									<li>
											<input type="checkbox" class="custom-control-input" value="<?= $dRow['menu_id'] ?>" id="<?= $dRow['menu_id'] ?>" name="menu-item[]">
											<label class="custom-control-label"><?= $dRow['menu_desc'] ?></label>
									</li>
											
									<?
														}
														echo '</ul> ';
													}
									?>
								</li>
						<?
												}
												echo '</ul> ';
											}
						?>
						</li>
				<?
										}
										echo '</ul> ';
									}
				?>
				</li>
			<?
							}
			?>
			<!--List Step-1 -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
	$("[name=btnradio_useDashboard]").change(function(e) {
		let tipe = $(this).attr('typeuseDashboard');
		console.log('tipe', tipe);
		$("#opt-use-dashboard").val(tipe);
	});

	$("[name=btnradio_useTelephony]").change(function(e) {
		let tipe = $(this).attr('typeuseTelephony');
		console.log('tipe', tipe);
		$("#opt-use-telephony").val(tipe);
	});
	$(document).on('change', 'input[type="checkbox"]', function(e) {
		var checked = $(this).prop("checked");
		var container = $(this).parent();
		var siblings = container.siblings();

		container.find('input[type="checkbox"]').prop({
			indeterminate: false,
			checked: checked
		});

		function checkSiblings(el) {

			var parent = el.parent().parent();
			var all = true;

			el.siblings().each(function() {
				return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
			});

			if (all && checked) {

				parent.children('input[type="checkbox"]').prop({
					indeterminate: false,
					checked: checked
				});

				checkSiblings(parent);

			} else if (all && !checked) {

				parent.children('input[type="checkbox"]').prop("checked", checked);
				parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
				checkSiblings(parent);

			} else {

				el.parents("li").children('input[type="checkbox"]').prop({
					indeterminate: true,
					checked: false
				});

			}

		}

		checkSiblings(container);
	});
	var $assets = "assets"; //this will be used in fuelux.tree-sampledata.js
</script>
<script src="<?php //=base_url();
				?>assets/js/jqChecktree/jquery-checktree.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		$('#tree').checktree();
	});

	$("#opt-level_group").change(function() {
		// console.log("level changed");
		$('input:checkbox').removeAttr('checked');
		var arrDefaultAuth;
		switch ($(this).val()) {
			case 'ADMIN':
				arrDefaultAuth = [];
				break;
			case 'SUPERVISOR':
				arrDefaultAuth = ['6', '61', '62', '611', '612', '63', '64', '7', '71', '72', '73', '74', '75', '76', '5', '51', '52', '8', '81', '82', '83'];
				break;
			case 'TEAM_LEADER':
				arrDefaultAuth = ['6', '61', '62', '611', '612', '63', '64', '7', '71', '72', '73', '74', '75', '76', '5', '51', '52', '8', '81', '82', '83'];
				break;
			case 'AGENT':
				arrDefaultAuth = ['6', '61', '62', '611', '612', '63', '64', '7', '71', '72', '73', '74', '75', '76', '5', '51', '52'];
				break;
			case 'ARCO':
				arrDefaultAuth = ['6', '61', '62', '611', '612', '63', '64', '7', '71', '72', '73', '74', '75', '76', '5', '51', '52', '8', '81', '83'];
				break;

		}

		for (var i = 0; i < arrDefaultAuth.length; i++) {
			$("#" + arrDefaultAuth[i]).prop("checked", true);
			// alert("#" + arrAuthority[i]);
		}

	});

    $("[name=btnradio_typeCollection]").change(function(e) {
        let tipe = $(this).attr('typeColl');
        console.log('tipe',tipe);
        $("#opt-type_collection").val(tipe);
    });
</script>