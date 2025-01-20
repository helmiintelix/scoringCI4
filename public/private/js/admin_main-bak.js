<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>eCentriX CRM</title>

		<meta name="description" content="This is page-header (.page-header &gt; h1)" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->

		<link href="assets/ace/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/ace/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!-- page specific plugin styles -->
		
		<link rel="stylesheet" href="assets/ace/css/jquery-ui-1.10.3.full.min.css" />
		<link rel="stylesheet" href="assets/ace/css/datepicker.css" />
		<link rel="stylesheet" href="assets/ace/css/ui.jqgrid.css" />
		<link rel="stylesheet" href="assets/ace/css/prettify.css" />
		<link rel="stylesheet" href="assets/ace/css/jquery.gritter.css" />
		<link rel="stylesheet" href="assets/ace/css/select2.css" />
		<link rel="stylesheet" href="assets/ace/css/bootstrap-editable.css" />
		<link rel="stylesheet" href="assets/ace/css/chosen.css" />

		<!-- fonts -->

		<link rel="stylesheet" href="assets/ace/css/ace-fonts.css" />

		<!-- ace styles -->

		<link rel="stylesheet" href="assets/ace/css/ace.min.css" />
		<link rel="stylesheet" href="assets/ace/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="assets/ace/css/ace-skins.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
		
		<link rel="stylesheet" href="assets/css/ace-custom.css" />

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

		<script src="assets/ace/js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="navbar navbar-default" id="navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							<i class="icon-globe"></i>
							Loan Originating System
						</small>
					</a><!-- /.brand -->
				</div><!-- /.navbar-header -->

				<div class="navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<span class="user-info">
									<small>Welcome,</small>
									<?=$this->session->userdata('USER_NAME');?>
								</span>

								<i class="icon-caret-down"></i>
							</a>

							<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#" id="change_security_pass_button">
										<i class="icon-lock"></i>
										Change Password
									</a>
								</li>
								<!--
								<li>
									<a href="#">
										<i class="icon-user"></i>
										Profile
									</a>
								</li>
								-->
								<li class="divider"></li>

								<li>
									<a href="#" id="logout_button">
										<i class="icon-off"></i>
										Logout
									</a>
								</li>
							</ul>
						</li>
					</ul><!-- /.ace-nav -->
				</div><!-- /.navbar-header -->
			</div><!-- /.container -->
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<div class="sidebar-shortcuts" id="sidebar-shortcuts">
						<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
							<button class="btn btn-success">
								<i class="icon-refresh"></i>
							</button>

							<button class="btn btn-info">
								<i class="icon-ban-circle "></i>
							</button>

							<button class="btn btn-warning">
								<i class="icon-book"></i>
							</button>

							<button class="btn btn-danger">
								<i class="icon-off"></i>
							</button>
						</div>

						<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>

							<span class="btn btn-info"></span>

							<span class="btn btn-warning"></span>

							<span class="btn btn-danger"></span>
						</div>
					</div><!-- #sidebar-shortcuts -->
					
					<ul class="nav nav-list" id="menuMain">
						<?
							if(in_array("ocr-verification", $this->session->userdata("AUTHORITY"))) 
							{
						?>
						<li>
							<a href="#" class="dropdown-toggle">
								<i class="icon-eye-open"></i>
								<span class="menu-text"> OCR Verification </span>

								<b class="arrow icon-angle-down"></b>
							</a>

							<ul class="submenu">
								<?
									if(in_array("field-verification", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li class="active">
									<a href="#" onclick="loadMenu('Field Verification','ocr_verification/raw_data_list');">
										<i class="icon-double-angle-right"></i>
										Field Verification
									</a>
								</li>
								<?
									}
								?>
							</ul>
						</li>
						<?
							}
						?>
						<?
							if(in_array("checking", $this->session->userdata("AUTHORITY"))) 
							{
						?>
						<li>
							<a href="#" class="dropdown-toggle">
								<i class="icon-check"></i>
								<span class="menu-text"> Checking </span>

								<b class="arrow icon-angle-down"></b>
							</a>

							<ul class="submenu">
								<?
									if(in_array("fraud-checking", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('Fraud Checking','checking/fraud_checking_list');">
										<i class="icon-double-angle-right"></i>
										Fraud Checking
									</a>
								</li>
								<?
									}
								?>
								<?
									if(in_array("bi-checking", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('BI Checking','checking/bi_checking_list');">
										<i class="icon-double-angle-right"></i>
										BI Checking
									</a>
								</li>
								<?
									}
								?>
								<?
									if(in_array("dbr-checking", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('DBR Checking','checking/dbr_checking_list');">
										<i class="icon-double-angle-right"></i>
										DBR Checking
									</a>
								</li>
								<?
									}
								?>
							</ul>
						</li>
						<?
							}
						?>
						<?
							if(in_array("settings", $this->session->userdata("AUTHORITY"))) 
							{
						?>
						<li>
							<a href="#" class="dropdown-toggle">
								<i class="icon-cog"></i>
								<span class="menu-text"> Settings </span>

								<b class="arrow icon-angle-down"></b>
							</a>

							<ul class="submenu">
								<?
									if(in_array("sms-template", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('SMS Template','settings/sms_info_for_aav');">
										<i class="icon-double-angle-right"></i>
										SMS Template
									</a>
								</li>
								<?
									}
								?>
								<?
									if(in_array("general", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('General Settings','settings/general');">
										<i class="icon-double-angle-right"></i>
										General
									</a>
								</li>
								<?
									}
								?>
							</ul>
						</li>
						<?
							}
						?>
						<?
							if(in_array("user-and-group", $this->session->userdata("AUTHORITY"))) 
							{
						?>
						<li>
							<a href="#" class="dropdown-toggle">
								<i class="icon-user"></i>
								<span class="menu-text"> User and Group </span>

								<b class="arrow icon-angle-down"></b>
							</a>

							<ul class="submenu">
								<?
									if(in_array("user-management", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('User Management','user_and_group/user_management');">
										<i class="icon-double-angle-right"></i>
										User Management
									</a>
								</li>
								<?
									}
								?>
								<?
									if(in_array("user-access-authority", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('System Access Authority','user_and_group/system_access_authority');">
										<i class="icon-double-angle-right"></i>
										User Access Authority
									</a>
								</li>
								<?
									}
								?>
							</ul>
						</li>
						<?
							}
						?>
						<?
							if(in_array("reports", $this->session->userdata("AUTHORITY"))) 
							{
						?>
						<li>
							<a href="#" class="dropdown-toggle">
								<i class="icon-book"></i>
								<span class="menu-text"> Reports </span>

								<b class="arrow icon-angle-down"></b>
							</a>
							
							<ul class="submenu">
								<?
									if(in_array("checking-report", $this->session->userdata("AUTHORITY"))) 
									{
								?>
								<li>
									<a href="#" onclick="loadMenu('Checking Report','reports/report_checking');">
										<i class="icon-double-angle-right"></i>
										Checking Report
									</a>
								</li>
							<?
								}
							?>
							</ul>
							
						</li>
						<?
							}
						?>
					</ul><!-- /.nav-list -->

					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>

					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<!--<i class="icon-home home-icon"></i>-->
								<a href="#"><span id="page-title">Home</span></a>
							</li>
						</ul><!-- .breadcrumb -->

					</div>

					<div class="page-content" style="display: none;" id="admin-wrapper">
						<!-- Load content here -->
						
					</div><!-- /.page-content -->
				</div><!-- /.main-content -->

				<!--
				<div class="ace-settings-container" id="ace-settings-container">
					<div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
						<i class="icon-cog bigger-150"></i>
					</div>

					<div class="ace-settings-box" id="ace-settings-box">
						<div>
							<div class="pull-left">
								<select id="skin-colorpicker" class="hide">
									<option data-skin="default" value="#438EB9">#438EB9</option>
									<option data-skin="skin-1" value="#222A2D">#222A2D</option>
									<option data-skin="skin-2" value="#C6487E">#C6487E</option>
									<option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
								</select>
							</div>
							<span>&nbsp; Choose Skin</span>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
							<label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
							<label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
							<label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
							<label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
						</div>

						<div>
							<input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
							<label class="lbl" for="ace-settings-add-container">
								Inside
								<b>.container</b>
							</label>
						</div>
					</div>
				</div>
				-->
				<!-- /#ace-settings-container -->
			</div><!-- /.main-container-inner -->
			<!--
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
			-->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-2.1.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="assets/ace/js/jquery-ui-1.10.3.full.min.js"></script>
		<script src="assets/ace/js/bootstrap.min.js"></script>
		<script src="assets/ace/js/typeahead-bs2.min.js"></script>

		<!-- page specific plugin scripts -->
		<!--
		<script src="assets/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/jquery.dataTables.bootstrap.js"></script>
		-->
		<script src="assets/ace/js/bootbox.min.js"></script>
		<script src="assets/ace/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="assets/ace/js/jqGrid/i18n/grid.locale-en.js"></script>
		<script src="assets/ace/js/prettify.js"></script>
		<script src="assets/ace/js/jquery.maskedinput.min.js"></script>
		<script src="assets/ace/js/jquery.gritter.min.js"></script>
		<script src="assets/ace/js/jquery.inputlimiter.1.3.1.min.js"></script>
		<script src="assets/ace/js/jquery.slimscroll.min.js"></script>
		<script src="assets/ace/js/fuelux/fuelux.tree.min.js"></script>
		<!--
		<script src="assets/js/fuelux-3.6.3/js/tree.js"></script>
		-->
		<script src="assets/ace/js/fuelux/fuelux.spinner.min.js"></script>
		<script src="assets/ace/js/select2.min.js"></script>
		<script src="assets/ace/js/x-editable/bootstrap-editable.min.js"></script>
		<script src="assets/ace/js/x-editable/ace-editable.min.js"></script>
		
		<script src="assets/js/jquery.form.min.js"></script>
		<script src="assets/js/jquery/jquery.download.js"></script>
		<script src="assets/js/jquery/jquery-idle.min.js"></script>
		
		<!--<script src="assets/js/jquery/autoNumeric.js"></script>-->
		<!--<script src="assets/js/jquery/jquery.maskMoney.js"></script>-->

		<!-- ace scripts -->

		<script src="assets/ace/js/ace-elements.min.js"></script>
		<script src="assets/ace/js/ace.min.js"></script>
		<script src="assets/ace/js/chosen.jquery.min.js"></script>
		
		<!-- additional scripts -->
		
		<script src="<?=base_url();?>assets/chat/jquery.xmpp.js"></script>
		<!--<script src="<?=base_url();?>modules/chat/js/chat.js"></script>-->
				
		<!-- ecentrix scripts -->
		
		<script src="<?=base_url();?>private/js/main.js"></script>
		<script src="<?=base_url();?>private/js/admin_main.js"></script>

		<!-- inline scripts related to this page -->

		<script type="text/javascript">
			jQuery(function($) {
			
				window.prettyPrint && prettyPrint();
				$('#id-check-horizontal').removeAttr('checked').on('click', function(){
					$('#dt-list-1').toggleClass('dl-horizontal').prev().html(this.checked ? '&lt;dl class="dl-horizontal"&gt;' : '&lt;dl&gt;');
				});
			
			})
			
			GLOBAL_MAIN_VARS = new Array ();
			GLOBAL_MAIN_VARS["BASE_URL"] = "<?=base_url();?>";
			GLOBAL_MAIN_VARS["SITE_URL"] = "<?=site_url();?>";
			GLOBAL_MAIN_VARS["SPINNER"] = "<img src='assets/img/select2-spinner.gif'/>";
			GLOBAL_MAIN_VARS["HTTP_BIND"] = "<?=$this->config->item('chatting_bind');?>";		
			GLOBAL_MAIN_VARS["GROUP_MENU"] = "<?=$group;?>";
			
			var GLOBAL_SESSION_VARS = new Array ();
			GLOBAL_SESSION_VARS["USER_ID"] = "<?=$this->session->userdata('USER_ID');?>";
			GLOBAL_SESSION_VARS["GROUP_ID"] = "<?=$this->session->userdata('GROUP_ID');?>";
			GLOBAL_SESSION_VARS["LEVEL_GROUP"] = "<?=$this->session->userdata('LEVEL_GROUP');?>";
			
			GLOBAL_MAIN_VARS["PASSWORD_STATUS"]						= "<?=$password_status;?>";
			GLOBAL_MAIN_VARS["PASSWORD_AGE"]							= "<?=$password_age;?>";
			
			$('#frmDialMenu').submit(function () {
			 $("html, body").animate({ scrollTop: 310 }, 500);
			 return false;
			});
			
		</script>
	</body>
</html>
