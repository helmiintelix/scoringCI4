<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;


$routes->setAutoRoute(false);
$security = \Config\Services::security();

/**
 * @var RouteCollection $routes
 */

$uri = current_url(true);
$router = service('router');
$module = $router->controllerName();

if(session()->get('logged_in')!==null){
    if(!session()->get('logged_in')){
        $routes->get('/', 'Main::index');
       
    }else{
        $routes->get('/', 'Login::index');  
       
    }
}else{
    $routes->get('/', 'Login::index');
}
 
$routes->post('/test', 'Test::index',['filter' => 'authfilter']);

#non modules
$routes->get('/main', 'Main::index',['filter' => 'authfilter']);
$routes->get('/Main', 'Main::index',['filter' => 'authfilter']);
$routes->get('/Main/setMenu', 'Main::setMenu',['filter' => 'authfilter']);
$routes->get('/login', 'Login::index');
$routes->post('login/post_login', 'Login::post_login');
$routes->get('login/logout', 'Login::logout');
$routes->get('PushNotif/getUserAccountNo', 'PushNotif::getUserAccountNo');
$routes->get('PushNotif/getInboundId', 'PushNotif::getInboundId');

$routes->get('/', '\App\Modules\\' . $module . '\Controllers\\' . $module . '::index');

#ECENTRIX
$routes->get('/Ecentrix8/getCallCenterConfigurationSupervisor','Ecentrix8::getCallCenterConfigurationSupervisor');
$routes->get('/Ecentrix8/getCallCenterConfiguration','Ecentrix8::getCallCenterConfiguration');
$routes->post('/Ecentrix8/updateAccountCodeSessionLog','Ecentrix8::updateAccountCodeSessionLog');


#ClassificationDetail
$routes->add('classification_detail/get_parameter_list', '\App\Modules\ClassificationDetail\Controllers\Classification_detail::get_parameter_list',['filter' => 'authfilter']);
#team_management
$routes->add('team_management/team_work', '\App\Modules\TeamManagement\Controllers\TeamWork::index',['filter' => 'authfilter']);
#UserAndGroup
$routes->add('user_and_group/user_management', '\App\Modules\UserAndGroup\Controllers\User_and_group::user_management',['filter' => 'authfilter']);
#UserManagementApproval
$routes->add('user_and_group/user_management_temp', '\App\Modules\UserManagementApproval\Controllers\User_management_approval::index',['filter' => 'authfilter']);
#InputVisitRadius
$routes->add('input_visit_radius/visit_radius_all', '\App\Modules\InputVisitRadius\Controllers\Input_visit_radius::visit_radius_all',['filter' => 'authfilter']);
#SetupAreaBranch
$routes->add('setup_area_branch/branch', '\App\Modules\SetupAreaBranch\Controllers\Setup_area_branch::branch',['filter' => 'authfilter']);
#SetupAreaBranchtemp
$routes->add('setup_area_branch_temp/area_branch_temp', '\App\Modules\SetupAreaBranch\Controllers\setup_area_branch_temp::area_branch_temp',['filter' => 'authfilter']);
#VisitRadius
$routes->add('visit_radius/visit_radius', '\App\Modules\VisitRadius\Controllers\Visit_radius_maker::index',['filter' => 'authfilter']);
#VisitRadiusTemp
$routes->add('visit_radius/visit_radius_temp', '\App\Modules\VisitRadius\Controllers\Visit_radius_temp::index',['filter' => 'authfilter']);
#SetUpBranch
$routes->add('settings/branch', '\App\Modules\SetUpBranch\Controllers\Set_up_branch_maker::index',['filter' => 'authfilter']);
#SetUpBranchTemp
$routes->add('settings/branch_temp', '\App\Modules\SetUpBranch\Controllers\Set_up_branch_temp::index',['filter' => 'authfilter']);
#HolidayMaker
$routes->add('holiday/holiday_maker', '\App\Modules\Holiday\Controllers\Holiday_maker::index',['filter' => 'authfilter']);
#HolidayMaker
$routes->add('holiday/holiday_temp', '\App\Modules\Holiday\Controllers\Holiday_temp::index',['filter' => 'authfilter']);
#MasterAuctionHouse
$routes->add('settings/balai_lelang', '\App\Modules\SetupAuctionHouse\Controllers\Master_auction_house::index',['filter' => 'authfilter']);
#EventAuctionHouse
$routes->add('settings/event_balai_lelang', '\App\Modules\SetupAuctionHouse\Controllers\Event_auction_house::index',['filter' => 'authfilter']);
#SetupBidderManagement
$routes->add('settings/bidder', '\App\Modules\SetupAuctionHouse\Controllers\Setup_bidder_management::index',['filter' => 'authfilter']);
#SetupVoiceBlastMaker
$routes->add('voiceblast/campaign', '\App\Modules\SetupVoiceBlast\Controllers\Setup_voice_blast_maker::index',['filter' => 'authfilter']);
#SetupVoiceBlastTemp
$routes->add('voiceblast/campaign_tmp', '\App\Modules\SetupVoiceBlast\Controllers\Setup_voice_blast_temp::index',['filter' => 'authfilter']);
#SuratPeringatanSPTemplateMaker
$routes->add('surat_peringatan_sp_template/letter_template', '\App\Modules\SuratPeringatanSPTemplate\Controllers\Surat_peringatan_sp_template_maker::index',['filter' => 'authfilter']);
#SuratPeringatanSPTemplateApproval
$routes->add('settings/letter_template_temp', '\App\Modules\SuratPeringatanSPTemplate\Controllers\Surat_peringatan_sp_template_temp::index',['filter' => 'authfilter']);
#SetupListOfValue
$routes->add('setup_list_of_value/lov', '\App\Modules\SetupListOfValue\Controllers\setup_list_of_value::lov',['filter' => 'authfilter']);
#ChecklistAsset
$routes->add('checklist_asset/add_field_checklist', '\App\Modules\ChecklistAsset\Controllers\checklist_asset::add_field_checklist',['filter' => 'authfilter']);
#SetupDiskonApproval
$routes->add('setup_diskon_approval/setup_diskon_approval_list', '\App\Modules\SetupDiskonApproval\Controllers\setup_diskon_approval::setup_diskon_approval_list',['filter' => 'authfilter']);
#SetupWaNumber
$routes->add('setup_wa_number/setup_wa_number', '\App\Modules\SetupWaNumber\Controllers\setup_wa_number::setup_wa_number',['filter' => 'authfilter']);
#SetupWaTemplate
$routes->add('setup_wa_template/view_template_list', '\App\Modules\SetupWaTemplate\Controllers\setup_wa_template::view_template_list',['filter' => 'authfilter']);
#SetupWaQuickReply
$routes->add('setup_wa_quick_reply/view_quick_reply_list', '\App\Modules\SetupWaQuickReply\Controllers\setup_wa_quick_reply::view_quick_reply_list',['filter' => 'authfilter']);
#SetupWaFlow
$routes->add('setup_wa_flow/view_flow_list', '\App\Modules\SetupWaFlow\Controllers\setup_wa_flow::view_flow_list',['filter' => 'authfilter']);
#SetupWaGeneral
$routes->add('setup_wa_general/view_general_list', '\App\Modules\SetupWaGeneral\Controllers\setup_wa_general::view_general_list',['filter' => 'authfilter']);
#SetupWaFilterWord
$routes->add('setup_wa_filter_word/view_filter_word_list', '\App\Modules\SetupWaFilterWord\Controllers\setup_wa_filter_word::view_filter_word_list',['filter' => 'authfilter']);
#UploadWaBlast
$routes->add('upload_wa_blast/view_upload_wa_blast_list', '\App\Modules\UploadWaBlast\Controllers\Upload_wa_blast::view_upload_wa_blast_list',['filter' => 'authfilter']);
#ApprovalUploadWaBlast
$routes->add('approval_upload_wa_blast/view_approval_upload_wa_blast_list', '\App\Modules\ApprovalUploadWaBlast\Controllers\Approval_upload_wa_blast::view_approval_upload_wa_blast_list',['filter' => 'authfilter']);
#ApprovalAgentWaBlast
$routes->add('approval_agent_wa_blast/view_approval_agent_wa_blast_list', '\App\Modules\ApprovalAgentWaBlast\Controllers\Approval_agent_wa_blast::view_approval_agent_wa_blast_list',['filter' => 'authfilter']);
#ReportMasterWa
#semua report wa ada di tabel wa_master_report
$routes->add('reports/wa/view/(:any)', '\App\Modules\ReportWa\Controllers\Report_wa::view/$1',['filter' => 'authfilter']);

#SuratPeringatanSpTemplate
$routes->add('surat_peringatan_sp_template/letter_template', '\App\Modules\SuratPeringatanSpTemplate\Controllers\surat_peringatan_sp_template::letter_template',['filter' => 'authfilter']);
#ParameterPengajuanDiskon
$routes->add('parameter_pengajuan_diskon/discount_parameter', '\App\Modules\ParameterPengajuanDiskon\Controllers\parameter_pengajuan_diskon::discount_parameter',['filter' => 'authfilter']);
#SetupBroadcastMessage
$routes->add('settings/broadcast_message_setup', '\App\Modules\SetupBroadcastMessage\Controllers\Setup_broadcast_message::index',['filter' => 'authfilter']);
#AgentTeleScript
$routes->add('agent_script/index', '\App\Modules\AgentTeleScript\Controllers\Agent_tele_script::index',['filter' => 'authfilter']);
#SetupHistParameterMaker
$routes->add('settings/setup_alert_angsuran', '\App\Modules\SetupHistParameter\Controllers\Setup_hist_parameter_maker::index',['filter' => 'authfilter']);
#SetupHistParameterApproval
$routes->add('settings/setup_alert_angsuran_approval', '\App\Modules\SetupHistParameter\Controllers\Setup_hist_parameter_temp::index',['filter' => 'authfilter']);
#ParameterPengajuanDiskon
$routes->add('parameter_pengajuan_diskon/discount_parameter', '\App\Modules\ParameterPengajuanDiskon\Controllers\Parameter_pengajuan_diskon::index',['filter' => 'authfilter']);
#DetailAccount
$routes->add('detail_account/detail_account', '\App\Modules\DetailAccount\Controllers\Detail_account::index',['filter' => 'authfilter']);
#ParameterPengajuanRestructure
$routes->add('detail_account/approval/restructure_parameter', '\App\Modules\ParameterPengajuanRestructure\Controllers\Parameter_pengajuan_restructure::index',['filter' => 'authfilter']);
#SetupDeviationRererenceMaker
$routes->add('settings/deviation_reference', '\App\Modules\SetupDeviationReference\Controllers\Setup_deviation_reference_maker::index',['filter' => 'authfilter']);
#SetupDeviationRererenceApproval
$routes->add('settings/deviation_reference_temp', '\App\Modules\SetupDeviationReference\Controllers\Setup_deviation_reference_temp::index',['filter' => 'authfilter']);
#SetupDeviationApprovalMaker
$routes->add('settings/deviation_approval', '\App\Modules\SetupDeviationApproval\Controllers\Setup_deviation_approval_maker::index',['filter' => 'authfilter']);
#SetupDeviationApprovalApproval
$routes->add('settings/deviation_approval_temp', '\App\Modules\SetupDeviationApproval\Controllers\Setup_deviation_approval_temp::index',['filter' => 'authfilter']);
#BucketMaker
$routes->add('bucket/bucket', '\App\Modules\Bucket\Controllers\Bucket_maker::index',['filter' => 'authfilter']);
#BucketTemp
$routes->add('bucket/bucket_temp', '\App\Modules\Bucket\Controllers\Bucket_temp::index',['filter' => 'authfilter']);
#EmailSmsTemplateMaker
$routes->add('settings/email_sms_template', '\App\Modules\EmailSmsTemplate\Controllers\Email_sms_template_maker::index',['filter' => 'authfilter']);
#EmailSmsTemplateTemp
$routes->add('settings/email_sms_template_temp', '\App\Modules\EmailSmsTemplate\Controllers\Email_sms_template_temp::index',['filter' => 'authfilter']);
#SetUpRestructureApproval
$routes->add('workflow_pengajuan/setup_restructure_approval_list', '\App\Modules\SetUpRestructureApproval\Controllers\Setup_restructure_approval::index',['filter' => 'authfilter']);
#SetupAreaTagihMaker
$routes->add('settings/area_tagih', '\App\Modules\SetupAreaTagih\Controllers\Setup_area_tagih_maker::index',['filter' => 'authfilter']);
#SetupAreaTagihApproval
$routes->add('settings/area_tagih_temp', '\App\Modules\SetupAreaTagih\Controllers\Setup_area_tagih_temp::index',['filter' => 'authfilter']);
#ZipcodeAreaMappingMaker
$routes->add('zipcodes/zipcode_area_mapping', '\App\Modules\ZipcodeAreaMapping\Controllers\Zipcode_area_mapping_maker::index',['filter' => 'authfilter']);
#ZipcodeAreaMappingApproval
$routes->add('zipcodes/zipcode_area_mapping_temp', '\App\Modules\ZipcodeAreaMapping\Controllers\Zipcode_area_mapping_temp::index',['filter' => 'authfilter']);
#UnassignedZipcode
$routes->add('zipcodes/unassigned_zipcode', '\App\Modules\UnassignedZipcode\Controllers\Unassigned_zipcode::index',['filter' => 'authfilter']);
#SetupFieldcollAreaMappingMaker
$routes->add('settings/fieldcoll_area_mapping', '\App\Modules\SetupFieldcollAreaMapping\Controllers\Setup_fieldcoll_area_mapping_maker::index',['filter' => 'authfilter']);
#SetupFieldcollAreaMappingApproval
$routes->add('settings/fieldcoll_area_mapping_temp', '\App\Modules\SetupFieldcollAreaMapping\Controllers\Setup_fieldcoll_area_mapping_temp::index',['filter' => 'authfilter']);
#AgencyManagementMaker
$routes->add('settings_am/settings_am_am', '\App\Modules\AgencyManagement\Controllers\Agency_management_maker::index',['filter' => 'authfilter']);
#AgencyManagementApproval
$routes->add('settings_am/settings_am_temp', '\App\Modules\AgencyManagement\Controllers\Agency_management_temp::index',['filter' => 'authfilter']);
#AgencyActivityUpload
$routes->add('agency/upload_activity', '\App\Modules\AgencyActivityUpload\Controllers\Agency_activity_upload::index',['filter' => 'authfilter']);
#ClassificationManagement
$routes->add('classification/classification_list', '\App\Modules\ClassificationManagement\Controllers\Classification_management::index',['filter' => 'authfilter']);
#FieldcollAndAgencyClassAssignment
$routes->add('assignment/class_assignment', '\App\Modules\FieldcollAndAgencyClassAssignment\Controllers\Fieldcoll_and_agency_class_assignment::index',['filter' => 'authfilter']);
#FieldcollAndAgencyReassignment
$routes->add('assignment/reassignment', '\App\Modules\FieldcollAndAgencyReassignment\Controllers\Fieldcoll_and_agency_reassignment::index',['filter' => 'authfilter']);
#FieldcollAndAgencyApprovalReassignment
$routes->add('assignment/approval_reassignment', '\App\Modules\FieldcollAndAgencyApprovalReassignment\Controllers\Fieldcoll_and_agency_approval_reassignment::index',['filter' => 'authfilter']);
#Reschedule
$routes->add('workflow_pengajuan/workflow_pengajuan_reschedule', '\App\Modules\WorkflowPengajuan\Controllers\Workflow_pengajuan_reschedule::index',['filter' => 'authfilter']);
#AssignmentRestructure
$routes->add('workflow_pengajuan/workflow_pengajuan_restructure', '\App\Modules\WorkflowPengajuan\Controllers\Assignment_restructure::index',['filter' => 'authfilter']);
#DownloadAccountHandling
$routes->add('account_handling/download_account_handling', '\App\Modules\DownloadAccountHandling\Controllers\Download_account_handling::index',['filter' => 'authfilter']);
#MyAccount
$routes->add('account_handling/assigned_account', '\App\Modules\MyAccount\Controllers\My_account::index',['filter' => 'authfilter']);
#SetupAccountTaggingMaker
$routes->add('account_handling/setup_account_tagging_list', '\App\Modules\SetupAccountTagging\Controllers\Setup_account_tagging_maker::index',['filter' => 'authfilter']);
#SetupAccountTaggingApproval
$routes->add('account_handling/setup_account_tagging_list_temp', '\App\Modules\SetupAccountTagging\Controllers\Setup_account_tagging_temp::index',['filter' => 'authfilter']);
#PhoneTaggingList
$routes->add('account_handling/setup_phone_tagging_list', '\App\Modules\PhoneTagging\Controllers\Phone_tagging_list::index',['filter' => 'authfilter']);
#PhoneTaggingRef
$routes->add('account_handling/setup_phone_tagging_ref', '\App\Modules\PhoneTagging\Controllers\Phone_tagging_ref::index',['filter' => 'authfilter']);
#UploadPengirimanSurat
$routes->add('pengiriman_surat/upload_pengiriman_surat', '\App\Modules\UploadPengirimanSurat\Controllers\Upload_pengiriman_surat::index',['filter' => 'authfilter']);
#MonitoringDebitur
$routes->add('monitoring_old/monitoring/debitur', '\App\Modules\MonitoringDebitur\Controllers\Monitoring_debitur::index',['filter' => 'authfilter']);
#MonitoringStatusFieldcoll
$routes->add('visit_radius/monitor_field_coll_view', '\App\Modules\MonitoringStatusFieldcoll\Controllers\Monitoring_status_fieldcoll::index',['filter' => 'authfilter']);
#MonitoringFieldcoll
$routes->add('monitoring/petugas', '\App\Modules\MonitoringFieldcoll\Controllers\Monitoring_fieldcoll::index',['filter' => 'authfilter']);
#CaseBaseReportEod
$routes->add('new_report/case_base', '\App\Modules\CaseBaseReportEod\Controllers\Case_base_report_eod::index',['filter' => 'authfilter']);
#EscalationMonitoringDetail
$routes->add('new_matrix_report/report_template', '\App\Modules\EscalationMonitoringDetail\Controllers\Escalation_monitoring_detail::index',['filter' => 'authfilter']);
#AgentMonitoring
$routes->add('agent_monitoring/agent_monitoring', '\App\Modules\AgentMonitoring\Controllers\Agent_monitoring::index',['filter' => 'authfilter']);
#RecordingVoice
$routes->add('recording/recording_list', '\App\Modules\RecordingVoice\Controllers\Recording_voice::index',['filter' => 'authfilter']);
#CaseEscalationToTeamLeaderDanFormApproval
$routes->add('coordinator_main/approval', '\App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Controllers\Case_escalation_to_team_leader_dan_form_approval::index',['filter' => 'authfilter']);
#FcRecordingVoice
$routes->add('recordingfc/recording_list', '\App\Modules\FcRecordingVoice\Controllers\Fc_recording_voice::index',['filter' => 'authfilter']);
#SuratPeringatan
$routes->add('account_handling/sp_due_list', '\App\Modules\SuratPeringatan\Controllers\Surat_peringatan::index',['filter' => 'authfilter']);
#BucketMonitoringAsOfToday
$routes->add('dashboard/bucket_monitoring_as_of_today', '\App\Modules\BucketMonitoringAsOfToday\Controllers\Bucket_monitoring_as_of_today::index',['filter' => 'authfilter']);
#WaDashboard
$routes->add('main_wablast/', '\App\Modules\WaDashboard\Controllers\Wa_dashboard::index',['filter' => 'authfilter']);
#ReportAudittrail
$routes->add('reportCols/load_auditrail', '\App\Modules\ReportAudittrail\Controllers\Report_audittrail::index',['filter' => 'authfilter']);
#ListPtp
$routes->add('reportCols/load_list_ptp', '\App\Modules\ListPtp\Controllers\List_ptp::index',['filter' => 'authfilter']);
#VoiceBlastReport
$routes->add('reportCols/load_voiceblast', '\App\Modules\VoiceBlastReport\Controllers\Voice_blast_report::index',['filter' => 'authfilter']);
#SummaryPTP(eod)
$routes->add('reportCols/load_sum_ptp_eod', '\App\Modules\SummaryPtpEod\Controllers\Summary_ptp_eod::index',['filter' => 'authfilter']);
#ListActivity
$routes->add('reportCols/load_list_activity', '\App\Modules\ListActivity\Controllers\List_activity::index',['filter' => 'authfilter']);
#LoadUserLoginlast
$routes->add('reportCols/load_user_last_login', '\App\Modules\ReportUserLastLogin\Controllers\Report_user_last_login::index',['filter' => 'authfilter']);
#CallingBaseReport
$routes->add('reportCols/load_calling_base', '\App\Modules\CallingBaseReport\Controllers\Calling_base_report::index',['filter' => 'authfilter']);
#PengirimanSurat
$routes->add('reportCols/load_pengiriman_surat', '\App\Modules\PengirimanSurat\Controllers\Pengiriman_surat::index',['filter' => 'authfilter']);
#ReportInputVisitLuarRadius
$routes->add('visit_radius/report_visit_radius', '\App\Modules\ReportInputVisitLuarRadius\Controllers\Report_input_visit_luar_radius::index',['filter' => 'authfilter']);
#ReportVisitLuarRadiusGeofencing
$routes->add('reports/report_visit_radius', '\App\Modules\ReportVisitLuarRadiusGeofencing\Controllers\Report_visit_luar_radius_geofencing::index',['filter' => 'authfilter']);
#ReportApiCheckNumber
$routes->add('reports/report_telesign', '\App\Modules\ReportApiCheckNumber\Controllers\Report_api_check_number::index',['filter' => 'authfilter']);
#ReportGenerateEmailWaSms
$routes->add('report_email_sms/report_generate_email_sms', '\App\Modules\ReportGenerateEmailWaSms\Controllers\Report_generate_email_wa_sms::index',['filter' => 'authfilter']);
#ReportAutoDial
$routes->add('reports/report_auto_dial', '\App\Modules\ReportAutoDial\Controllers\Report_auto_dial::index',['filter' => 'authfilter']);
#ReportPengajuanDiskon
$routes->add('reports/report_pengajuan_diskon', '\App\Modules\ReportPengajuanDiskon\Controllers\Report_pengajuan_diskon::index',['filter' => 'authfilter']);
#ReportPengajuanRestructure
$routes->add('reports/report_restructure', '\App\Modules\ReportPengajuanRestructure\Controllers\Report_pengajuan_restructure::index',['filter' => 'authfilter']);
#ReportPembayaranDetail
$routes->add('reports/pembayaran_detail', '\App\Modules\ReportPembayaranDetail\Controllers\Report_pmbayaran_detail::index',['filter' => 'authfilter']);
#ReportAccountTagging
$routes->add('reports/account_tagging', '\App\Modules\ReportAccountTagging\Controllers\Report_account_tagging::index',['filter' => 'authfilter']);
#LaporanAktivitasDeskcollEod
$routes->add('report_activity_dc/report_generate_activity_dc', '\App\Modules\LaporanAktivitasDeskcollEod\Controllers\Laporan_aktivitas_deskcoll_eod::index',['filter' => 'authfilter']);
#LaporanVisitFieldCollection
$routes->add('reportCols/load_visit_field_collection', '\App\Modules\LaporanVisitFieldCollection\Controllers\Laporan_visit_field_collection::index',['filter' => 'authfilter']);
#BodLog
$routes->add('reports/report_bod_log', '\App\Modules\BodLog\Controllers\Bod_log::index',['filter' => 'authfilter']);
#LaporanAuditPerdebitur
$routes->add('report_collection/reporting/report_audit_perdebitur', '\App\Modules\LaporanAuditPerdebitur\Controllers\Laporan_audit_perdebitur::index',['filter' => 'authfilter']);
#InventoryCollection
$routes->add('reportCols/load_inventory_collection', '\App\Modules\InventoryCollection\Controllers\Inventory_collection::index',['filter' => 'authfilter']);
#InventoryWo
$routes->add('reportCols/load_inventory_wo', '\App\Modules\InventoryWo\Controllers\Inventory_wo::index',['filter' => 'authfilter']);
#Inventory90
$routes->add('reportCols/load_inventory_90', '\App\Modules\Inventory90\Controllers\Inventory_90::index',['filter' => 'authfilter']);
#ClassWallBoard
$routes->add('dashboardInteractive/class_wallboard', '\App\Modules\DashboardInteractive\Controllers\DashboardInteractive::class_wallboard',['filter' => 'authfilter']);
#SummaryWallBoard
$routes->add('dashboardInteractive/summary_wallboard', '\App\Modules\DashboardInteractive\Controllers\DashboardInteractive::summary_wallboard',['filter' => 'authfilter']);
#SummaryWallBoard
$routes->add('dashboardInteractive/telephony_wallboard', '\App\Modules\DashboardInteractive\Controllers\DashboardInteractive::telephony_wallboard',['filter' => 'authfilter']);
#Summaryagentlogin
$routes->add('dashboardInteractive/dashboard_sum_agent_login', '\App\Modules\DashboardInteractive\Controllers\DashboardInteractive::dashboard_sum_agent_login',['filter' => 'authfilter']);
#Summaryagentlogin
$routes->add('settings/general', '\App\Modules\SetupPassword\Controllers\SetupPassword::general',['filter' => 'authfilter']);
$routes->add('settings/update_system_setting', '\App\Modules\SetupPassword\Controllers\SetupPassword::update_system_setting',['filter' => 'authfilter']);
$routes->add('settings/update_mobcoll_setting', '\App\Modules\SetupPassword\Controllers\SetupPassword::update_system_setting',['filter' => 'authfilter']);
$routes->add('settings/general_tmp', '\App\Modules\SetupPassword\Controllers\SetupPassword::general_tmp',['filter' => 'authfilter']);
$routes->add('settings/get_password_setting_temp', '\App\Modules\SetupPassword\Controllers\SetupPassword::get_password_setting_temp',['filter' => 'authfilter']);
$routes->add('settings/approve_system_setting', '\App\Modules\SetupPassword\Controllers\SetupPassword::approve_system_setting',['filter' => 'authfilter']);
$routes->add('settings/reject_system_setting', '\App\Modules\SetupPassword\Controllers\SetupPassword::reject_system_setting',['filter' => 'authfilter']);

$routes->add('dashboard/class_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::class_monitoring_as_of_today',['filter' => 'authfilter']);
$routes->add('dashboard/get_class_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::get_class_monitoring_as_of_today',['filter' => 'authfilter']);
$routes->add('dashboard/agent_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::agent_monitoring_as_of_today',['filter' => 'authfilter']);
$routes->add('dashboard/get_agent_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::get_agent_monitoring_as_of_today',['filter' => 'authfilter']);
$routes->add('dashboard/team_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::team_monitoring_as_of_today',['filter' => 'authfilter']);
$routes->add('dashboard/get_team_monitoring_as_of_today', '\App\Modules\Monitoring\Controllers\Monitoring::get_team_monitoring_as_of_today',['filter' => 'authfilter']);
#Dialing mode call status
$routes->add('dialingSetup/dialingModeCallStatus', '\App\Modules\DialingSetup\Controllers\DialingSetup::index',['filter' => 'authfilter']);
#setup aux
$routes->add('settings/setupAux', '\App\Modules\SetupAux\Controllers\SetupAux::index',['filter' => 'authfilter']);
#Laporan Input Visit FC
$routes->add('report_collection/reporting/report_visit_field', '\App\Modules\LaporanVisitFc\Controllers\LaporanVisitFc::index',['filter' => 'authfilter']);
#Setup Lov
$routes->add('settings/lov/', '\App\Modules\SetupLov\Controllers\SetupLov::index',['filter' => 'authfilter']);


#untuk module
if($uri->getTotalSegments() > 2){
    $function = $uri->getSegment(3);

    #Assignment
    $routes->add('assignment/assignment/'.$function, '\App\Modules\Assignment\Controllers\Assignment::'.$function,['filter' => 'authfilter']);

    #Team_management
    $routes->add('team_management/team_work/'.$function, '\App\Modules\TeamManagement\Controllers\TeamWork::'.$function,['filter' => 'authfilter']);
    
    #UserAndGroup
    $routes->add('user_and_group/user_and_group/'.$function, '\App\Modules\UserAndGroup\Controllers\User_and_group::'.$function,['filter' => 'authfilter']);
    #UserAndGroup
    $routes->add('user_and_group/'.$function, '\App\Modules\UserAndGroup\Controllers\User_and_group::'.$function,['filter' => 'authfilter']);
    
    #UserManagementApproval
    $routes->add('user_and_group/user_management_temp/'.$function, '\App\Modules\UserManagementApproval\Controllers\User_management_approval::'.$function,['filter' => 'authfilter']);

    #InputVisitRadius
    $routes->add('input_visit_radius/input_visit_radius/'.$function, '\App\Modules\InputVisitRadius\Controllers\Input_visit_radius::'.$function,['filter' => 'authfilter']);
    
    #SetupAreaBranch
    $routes->add('setup_area_branch/setup_area_branch/'.$function, '\App\Modules\SetupAreaBranch\Controllers\Setup_area_branch::'.$function,['filter' => 'authfilter']);
    
    #SetupAreaBranchTemp
    $routes->add('setup_area_branch_temp/setup_area_branch_temp/'.$function, '\App\Modules\SetupAreaBranch\Controllers\setup_area_branch_temp::'.$function,['filter' => 'authfilter']);
    #visit_radius_maker
    $routes->add('visit_radius/visit_radius/'.$function, '\App\Modules\VisitRadius\Controllers\Visit_radius_maker::'.$function,['filter' => 'authfilter']);
    
    #visit_radius_temp
    $routes->add('visit_radius/visit_radius_temp/'.$function, '\App\Modules\VisitRadius\Controllers\Visit_radius_temp::'.$function,['filter' => 'authfilter']);
    
    #set_up_branch_maker
    $routes->add('settings/branch/'.$function, '\App\Modules\SetUpBranch\Controllers\Set_up_branch_maker::'.$function,['filter' => 'authfilter']);
    
    #set_up_branch_temp
    $routes->add('settings/branch_temp/'.$function, '\App\Modules\SetUpBranch\Controllers\Set_up_branch_temp::'.$function,['filter' => 'authfilter']);
    
    #holiday_maker
    $routes->add('holiday/holiday_maker/'.$function, '\App\Modules\Holiday\Controllers\Holiday_maker::'.$function,['filter' => 'authfilter']);
    
    #holiday_temp
    $routes->add('holiday/holiday_temp/'.$function, '\App\Modules\Holiday\Controllers\Holiday_temp::'.$function,['filter' => 'authfilter']);
    
    #master_auction_house
    $routes->add('settings/balai_lelang/'.$function, '\App\Modules\SetupAuctionHouse\Controllers\Master_auction_house::'.$function,['filter' => 'authfilter']);
    
    #event_auction_house
    $routes->add('settings/event_balai_lelang/'.$function, '\App\Modules\SetupAuctionHouse\Controllers\Event_auction_house::'.$function,['filter' => 'authfilter']);
    
    #setup_bidder_management
    $routes->add('settings/bidder/'.$function, '\App\Modules\SetupAuctionHouse\Controllers\Setup_bidder_management::'.$function,['filter' => 'authfilter']);
    
    #setup_voice_blast
    $routes->add('voiceblast/campaign/'.$function, '\App\Modules\SetupVoiceBlast\Controllers\Setup_voice_blast_maker::'.$function,['filter' => 'authfilter']);
    
    #setup_voice_blast_temp
    $routes->add('voiceblast/campaign_tmp/'.$function, '\App\Modules\SetupVoiceBlast\Controllers\Setup_voice_blast_temp::'.$function,['filter' => 'authfilter']);
    
    #SuratPeringatanSPTemplateMaker
    $routes->add('surat_peringatan_sp_template/letter_template/'.$function, '\App\Modules\SuratPeringatanSPTemplate\Controllers\Surat_peringatan_sp_template_maker::'.$function,['filter' => 'authfilter']);
    
    #SuratPeringatanSPTemplateApproval
    $routes->add('settings/letter_template_temp/'.$function, '\App\Modules\SuratPeringatanSPTemplate\Controllers\Surat_peringatan_sp_template_temp::'.$function,['filter' => 'authfilter']);
    
    #SetupListOfValue
    $routes->add('setup_list_of_value/setup_list_of_value/'.$function, '\App\Modules\SetupListOfValue\Controllers\setup_list_of_value::'.$function,['filter' => 'authfilter']);

    #ChecklistAsset
    $routes->add('checklist_asset/checklist_asset/'.$function, '\App\Modules\ChecklistAsset\Controllers\checklist_asset::'.$function,['filter' => 'authfilter']);

    #SetupDiskonApproval
    $routes->add('setup_diskon_approval/setup_diskon_approval/'.$function, '\App\Modules\SetupDiskonApproval\Controllers\setup_diskon_approval::'.$function,['filter' => 'authfilter']);

    #SetupWaNumber
    $routes->add('setup_wa_number/setup_wa_number/'.$function, '\App\Modules\SetupWaNumber\Controllers\setup_wa_number::'.$function,['filter' => 'authfilter']);

    #SetupWaTemplate
    $routes->add('setup_wa_template/setup_wa_template/'.$function, '\App\Modules\SetupWaTemplate\Controllers\setup_wa_template::'.$function,['filter' => 'authfilter']);

    #SetupWaQuickReply
    $routes->add('setup_wa_quick_reply/setup_wa_quick_reply/'.$function, '\App\Modules\SetupWaQuickReply\Controllers\setup_wa_quick_reply::'.$function,['filter' => 'authfilter']);

    #SetupWaFlow
    $routes->add('setup_wa_flow/setup_wa_flow/'.$function, '\App\Modules\SetupWaFlow\Controllers\setup_wa_flow::'.$function,['filter' => 'authfilter']);

    #SetupWaGeneral
    $routes->add('setup_wa_general/setup_wa_general/'.$function, '\App\Modules\SetupWaGeneral\Controllers\setup_wa_general::'.$function,['filter' => 'authfilter']);
    #SetupWaFilterWord
    $routes->add('setup_wa_filter_word/setup_wa_filter_word/'.$function, '\App\Modules\SetupWaFilterWord\Controllers\setup_wa_filter_word::'.$function,['filter' => 'authfilter']);

    #UploadWaBlast
    $routes->add('upload_wa_blast/upload_wa_blast/'.$function, '\App\Modules\UploadWaBlast\Controllers\Upload_wa_blast::'.$function,['filter' => 'authfilter']);

    #ApprovalUploadWaBlast
    $routes->add('approval_upload_wa_blast/approval_upload_wa_blast/'.$function, '\App\Modules\ApprovalUploadWaBlast\Controllers\Approval_upload_wa_blast::'.$function,['filter' => 'authfilter']);

    #ApprovalAgentWaBlast
    $routes->add('approval_agent_wa_blast/approval_agent_wa_blast/'.$function, '\App\Modules\ApprovalAgentWaBlast\Controllers\Approval_agent_wa_blast::'.$function,['filter' => 'authfilter']);
    #ReportMasterWa
    #semua report wa ada di tabel wa_master_report
    $routes->add('reports/wa/'.$function, '\App\Modules\ReportWa\Controllers\Report_wa::'.$function,['filter' => 'authfilter']);

    #SuratPeringatanSpTemplate
    $routes->add('surat_peringatan_sp_template/surat_peringatan_sp_template/'.$function, '\App\Modules\SuratPeringatanSpTemplate\Controllers\surat_peringatan_sp_template::'.$function,['filter' => 'authfilter']);

    #ParameterPengajuanDiskon
    $routes->add('parameter_pengajuan_diskon/parameter_pengajuan_diskon/'.$function, '\App\Modules\ParameterPengajuanDiskon\Controllers\parameter_pengajuan_diskon::'.$function,['filter' => 'authfilter']);

    #setup_broadcast_message
    $routes->add('settings/broadcast_message_setup/'.$function, '\App\Modules\SetupBroadcastMessage\Controllers\Setup_broadcast_message::'.$function,['filter' => 'authfilter']);
    
    #AgentTeleScript
    $routes->add('agent_script/index/'.$function, '\App\Modules\AgentTeleScript\Controllers\Agent_tele_script::'.$function,['filter' => 'authfilter']);

    #SetupHistParameterMaker
    $routes->add('settings/setup_alert_angsuran/'.$function, '\App\Modules\SetupHistParameter\Controllers\Setup_hist_parameter_maker::'.$function,['filter' => 'authfilter']);
    
    #SetupHistParameterApproval
    $routes->add('settings/setup_alert_angsuran_approval/'.$function, '\App\Modules\SetupHistParameter\Controllers\Setup_hist_parameter_temp::'.$function,['filter' => 'authfilter']);
    
    #ParameterPengajuanDiskon
    $routes->add('parameter_pengajuan_diskon/discount_parameter/'.$function, '\App\Modules\ParameterPengajuanDiskon\Controllers\Parameter_pengajuan_diskon::'.$function,['filter' => 'authfilter']);

    #DetailAccount
    $routes->add('detail_account/detail_account/'.$function, '\App\Modules\DetailAccount\Controllers\Detail_account::'.$function,['filter' => 'authfilter']);

    #parameter_pengajuan_restructure
    $routes->add('detail_account/approval/'.$function, '\App\Modules\ParameterPengajuanRestructure\Controllers\Parameter_pengajuan_restructure::'.$function,['filter' => 'authfilter']);
    
    #SetupDeviationRererenceMaker
    $routes->add('settings/deviation_reference/'.$function, '\App\Modules\SetupDeviationReference\Controllers\Setup_deviation_reference_maker::'.$function,['filter' => 'authfilter']);
    
    #SetupDeviationRererenceApproval
    $routes->add('settings/deviation_reference_temp/'.$function, '\App\Modules\SetupDeviationReference\Controllers\Setup_deviation_reference_temp::'.$function,['filter' => 'authfilter']);
    
    #SetupDeviationApprovalMaker
    $routes->add('settings/deviation_approval/'.$function, '\App\Modules\SetupDeviationApproval\Controllers\Setup_deviation_approval_maker::'.$function,['filter' => 'authfilter']);
    
    #SetupDeviationApprovalApproval
    $routes->add('settings/deviation_approval_temp/'.$function, '\App\Modules\SetupDeviationApproval\Controllers\Setup_deviation_approval_temp::'.$function,['filter' => 'authfilter']);

    #bucket_maker
    $routes->add('bucket/bucket/'.$function, '\App\Modules\Bucket\Controllers\Bucket_maker::'.$function,['filter' => 'authfilter']);
    
    #bucket_temp
    $routes->add('bucket/bucket_temp/'.$function, '\App\Modules\Bucket\Controllers\Bucket_temp::'.$function,['filter' => 'authfilter']);
    
    #email_sms_template_maker
    $routes->add('settings/email_sms_template/'.$function, '\App\Modules\EmailSmsTemplate\Controllers\Email_sms_template_maker::'.$function,['filter' => 'authfilter']);
    
    #email_sms_template_temp
    $routes->add('settings/email_sms_template_temp/'.$function, '\App\Modules\EmailSmsTemplate\Controllers\Email_sms_template_temp::'.$function,['filter' => 'authfilter']);
    
    #Setup_restructure_approval
    $routes->add('workflow_pengajuan/setup_restructure_approval_list/'.$function, '\App\Modules\SetUpRestructureApproval\Controllers\Setup_restructure_approval::'.$function,['filter' => 'authfilter']);

    #SetupAreaTagihMaker
    $routes->add('settings/area_tagih/'.$function, '\App\Modules\SetupAreaTagih\Controllers\Setup_area_tagih_maker::'.$function,['filter' => 'authfilter']);
    
    #SetupAreaTagihApproval
    $routes->add('settings/area_tagih_temp/'.$function, '\App\Modules\SetupAreaTagih\Controllers\Setup_area_tagih_temp::'.$function,['filter' => 'authfilter']);

    #ZipcodeAreaMappingMaker
    $routes->add('zipcodes/zipcode_area_mapping/'.$function, '\App\Modules\ZipcodeAreaMapping\Controllers\Zipcode_area_mapping_maker::'.$function,['filter' => 'authfilter']);
    
    #ZipcodeAreaMappingApproval
    $routes->add('zipcodes/zipcode_area_mapping_temp/'.$function, '\App\Modules\ZipcodeAreaMapping\Controllers\Zipcode_area_mapping_temp::'.$function,['filter' => 'authfilter']);
    
    #UnassignedZipcode
    $routes->add('zipcodes/unassigned_zipcode/'.$function, '\App\Modules\UnassignedZipcode\Controllers\Unassigned_zipcode::'.$function,['filter' => 'authfilter']);
    
    #SetupFieldcollAreaMappingMaker
    $routes->add('settings/fieldcoll_area_mapping/'.$function, '\App\Modules\SetupFieldcollAreaMapping\Controllers\Setup_fieldcoll_area_mapping_maker::'.$function,['filter' => 'authfilter']);

    #SetupFieldcollAreaMappingApproval
    $routes->add('settings/fieldcoll_area_mapping_temp/'.$function, '\App\Modules\SetupFieldcollAreaMapping\Controllers\Setup_fieldcoll_area_mapping_temp::'.$function,['filter' => 'authfilter']);

    #AgencyManagementMaker
    $routes->add('settings_am/settings_am_am/'.$function, '\App\Modules\AgencyManagement\Controllers\Agency_management_maker::'.$function,['filter' => 'authfilter']);
    
    #AgencyManagementApproval
    $routes->add('settings_am/settings_am_temp/'.$function, '\App\Modules\AgencyManagement\Controllers\Agency_management_temp::'.$function,['filter' => 'authfilter']);

    #AgencyActivityUpload
    $routes->add('agency/upload_activity/'.$function, '\App\Modules\AgencyActivityUpload\Controllers\Agency_activity_upload::'.$function,['filter' => 'authfilter']);

    #DownloadAccountHandling
    $routes->add('account_handling/download_account_handling/'.$function, '\App\Modules\DownloadAccountHandling\Controllers\Download_account_handling::'.$function,['filter' => 'authfilter']);

    #MyAccount
    $routes->add('account_handling/assigned_account/'.$function, '\App\Modules\MyAccount\Controllers\My_account::'.$function,['filter' => 'authfilter']);

    #ClassificationManagement
    $routes->add('classification/classification_list/'.$function, '\App\Modules\ClassificationManagement\Controllers\Classification_management::'.$function);

    #dialingModeCallStatus
    $routes->add('dialingSetup/dialingModeCallStatus/'.$function, '\App\Modules\DialingSetup\Controllers\DialingSetup::'.$function);

    #FieldcollAndAgencyClassAssignment
    $routes->add('assignment/class_assignment/'.$function, '\App\Modules\FieldcollAndAgencyClassAssignment\Controllers\Fieldcoll_and_agency_class_assignment::'.$function,['filter' => 'authfilter']);

    #FieldcollAndAgencyReassignment
    $routes->add('assignment/reassignment/'.$function, '\App\Modules\FieldcollAndAgencyReassignment\Controllers\Fieldcoll_and_agency_reassignment::'.$function,['filter' => 'authfilter']);

    #FieldcollAndAgencyApprovalReassignment
    $routes->add('assignment/approval_reassignment/'.$function, '\App\Modules\FieldcollAndAgencyApprovalReassignment\Controllers\Fieldcoll_and_agency_approval_reassignment::'.$function,['filter' => 'authfilter']);

    #Reschedule
    $routes->add('workflow_pengajuan/workflow_pengajuan_reschedule/'.$function, '\App\Modules\WorkflowPengajuan\Controllers\Workflow_pengajuan_reschedule::'.$function,['filter' => 'authfilter']);

    #AssignmentRestructure
    $routes->add('workflow_pengajuan/workflow_pengajuan_restructure/'.$function, '\App\Modules\WorkflowPengajuan\Controllers\Assignment_restructure::'.$function,['filter' => 'authfilter']);

    #SetupAccountTaggingMaker
    $routes->add('account_handling/setup_account_tagging_list/'.$function, '\App\Modules\SetupAccountTagging\Controllers\Setup_account_tagging_maker::'.$function,['filter' => 'authfilter']);
    
    #SetupAccountTaggingApproval
    $routes->add('account_handling/setup_account_tagging_list_temp/'.$function, '\App\Modules\SetupAccountTagging\Controllers\Setup_account_tagging_temp::'.$function,['filter' => 'authfilter']);

    #PhoneTaggingList
    $routes->add('account_handling/setup_phone_tagging_list/'.$function, '\App\Modules\PhoneTagging\Controllers\Phone_tagging_list::'.$function,['filter' => 'authfilter']);
    
    #PhoneTaggingRef
    $routes->add('account_handling/setup_phone_tagging_ref/'.$function, '\App\Modules\PhoneTagging\Controllers\Phone_tagging_ref::'.$function,['filter' => 'authfilter']);

    #UploadPengirimanSurat
    $routes->add('pengiriman_surat/upload_pengiriman_surat/'.$function, '\App\Modules\UploadPengirimanSurat\Controllers\Upload_pengiriman_surat::'.$function,['filter' => 'authfilter']);
    
    #MonitoringDebitur
    $routes->add('monitoring_old/monitoring/'.$function, '\App\Modules\MonitoringDebitur\Controllers\Monitoring_debitur::'.$function,['filter' => 'authfilter']);

    #MonitoringStatusFieldcoll
    $routes->add('visit_radius/monitor_field_coll_view/'.$function, '\App\Modules\MonitoringStatusFieldcoll\Controllers\Monitoring_status_fieldcoll::'.$function,['filter' => 'authfilter']);

    #MonitoringFieldcoll
    $routes->add('monitoring/petugas/'.$function, '\App\Modules\MonitoringFieldcoll\Controllers\Monitoring_fieldcoll::'.$function,['filter' => 'authfilter']);

    #CaseBaseReportEod
    $routes->add('new_report/case_base/'.$function, '\App\Modules\CaseBaseReportEod\Controllers\Case_base_report_eod::'.$function,['filter' => 'authfilter']);

    #EscalationMonitoringDetail
    $routes->add('new_matrix_report/report_template/'.$function, '\App\Modules\EscalationMonitoringDetail\Controllers\Escalation_monitoring_detail::'.$function,['filter' => 'authfilter']);

    #AgentMonitoring
    $routes->add('agent_monitoring/agent_monitoring/'.$function, '\App\Modules\AgentMonitoring\Controllers\Agent_monitoring::'.$function,['filter' => 'authfilter']);

    #RecordingVoice
    $routes->add('recording/recording_list/'.$function, '\App\Modules\RecordingVoice\Controllers\Recording_voice::'.$function,['filter' => 'authfilter']);

    #CaseEscalationToTeamLeaderDanFormApproval
    $routes->add('coordinator_main/approval/'.$function, '\App\Modules\CaseEscalationToTeamLeaderDanFormApproval\Controllers\Case_escalation_to_team_leader_dan_form_approval::'.$function,['filter' => 'authfilter']);

    #FcRecordingVoice
    $routes->add('recordingfc/recording_list/'.$function, '\App\Modules\FcRecordingVoice\Controllers\Fc_recording_voice::'.$function,['filter' => 'authfilter']);

    #BucketMonitoringAsOfToday
    $routes->add('dashboard/bucket_monitoring_as_of_today/'.$function, '\App\Modules\BucketMonitoringAsOfToday\Controllers\Bucket_monitoring_as_of_today::'.$function,['filter' => 'authfilter']);

    #SuratPeringatan
    $routes->add('account_handling/sp_due_list/'.$function, '\App\Modules\SuratPeringatan\Controllers\Surat_peringatan::'.$function,['filter' => 'authfilter']);

    #ReportAudittrail
    $routes->add('reportCols/load_auditrail/'.$function, '\App\Modules\ReportAudittrail\Controllers\Report_audittrail::'.$function,['filter' => 'authfilter']);
    
    #ListPtp
    $routes->add('reportCols/load_list_ptp/'.$function, '\App\Modules\ListPtp\Controllers\List_ptp::'.$function,['filter' => 'authfilter']);

    #VoiceBlastReport
    $routes->add('reportCols/load_voiceblast/'.$function, '\App\Modules\VoiceBlastReport\Controllers\Voice_blast_report::'.$function,['filter' => 'authfilter']);

    #SummaryPTP
    $routes->add('reportCols/load_sum_ptp_eod/'.$function, '\App\Modules\SummaryPtpEod\Controllers\Summary_ptp_eod::'.$function,['filter' => 'authfilter']);

    #ListActivity
    $routes->add('reportCols/load_list_activity/'.$function, '\App\Modules\ListActivity\Controllers\List_activity::'.$function,['filter' => 'authfilter']);

    #LoadUserLoginlast
    $routes->add('reportCols/load_user_last_login/'.$function, '\App\Modules\ReportUserLastLogin\Controllers\Report_user_last_login::'.$function,['filter' => 'authfilter']);

    #CallingBaseReport
    $routes->add('reportCols/load_calling_base/'.$function, '\App\Modules\CallingBaseReport\Controllers\Calling_base_report::'.$function,['filter' => 'authfilter']);
    
    #PengirimanSurat
    $routes->add('reportCols/load_pengiriman_surat/'.$function, '\App\Modules\PengirimanSurat\Controllers\Pengiriman_surat::'.$function,['filter' => 'authfilter']);

    #ReportInputVisitLuarRadius
    $routes->add('visit_radius/report_visit_radius/'.$function, '\App\Modules\ReportInputVisitLuarRadius\Controllers\Report_input_visit_luar_radius::'.$function,['filter' => 'authfilter']);

    #ReportVisitLuarRadiusGeofencing
    $routes->add('reports/report_visit_radius/'.$function, '\App\Modules\ReportVisitLuarRadiusGeofencing\Controllers\Report_visit_luar_radius_geofencing::'.$function,['filter' => 'authfilter']);

    #ReportApiCheckNumber
    $routes->add('reports/report_telesign/'.$function, '\App\Modules\ReportApiCheckNumber\Controllers\Report_api_check_number::'.$function,['filter' => 'authfilter']);

    #ReportGenerateEmailWaSms
    $routes->add('report_email_sms/report_generate_email_sms/'.$function, '\App\Modules\ReportGenerateEmailWaSms\Controllers\Report_generate_email_wa_sms::'.$function,['filter' => 'authfilter']);

    #ReportAutoDial
    $routes->add('reports/report_auto_dial/'.$function, '\App\Modules\ReportAutoDial\Controllers\Report_auto_dial::'.$function,['filter' => 'authfilter']);

    #ReportPengajuanDiskon
    $routes->add('reports/report_pengajuan_diskon/'.$function, '\App\Modules\ReportPengajuanDiskon\Controllers\Report_pengajuan_diskon::'.$function,['filter' => 'authfilter']);

    #ReportPengajuanRestructure
    $routes->add('reports/report_restructure/'.$function, '\App\Modules\ReportPengajuanRestructure\Controllers\Report_pengajuan_restructure::'.$function,['filter' => 'authfilter']);

    #ReportPembayaranDetail
    $routes->add('reports/pembayaran_detail/'.$function, '\App\Modules\ReportPembayaranDetail\Controllers\Report_pmbayaran_detail::'.$function,['filter' => 'authfilter']);

    #ReportAccountTagging
    $routes->add('reports/account_tagging/'.$function, '\App\Modules\ReportAccountTagging\Controllers\Report_account_tagging::'.$function,['filter' => 'authfilter']);

    #LaporanAktivitasDeskcollEod
    $routes->add('report_activity_dc/report_generate_activity_dc/'.$function, '\App\Modules\LaporanAktivitasDeskcollEod\Controllers\Laporan_aktivitas_deskcoll_eod::'.$function,['filter' => 'authfilter']);

    #LaporanVisitFieldCollection
    $routes->add('reportCols/load_visit_field_collection/'.$function, '\App\Modules\LaporanVisitFieldCollection\Controllers\Laporan_visit_field_collection::'.$function,['filter' => 'authfilter']);

    #BodLog
    $routes->add('reports/report_bod_log/'.$function, '\App\Modules\BodLog\Controllers\Bod_log::'.$function,['filter' => 'authfilter']);

    #LaporanAuditPerdebitur
    $routes->add('report_collection/reporting/'.$function, '\App\Modules\LaporanAuditPerdebitur\Controllers\Laporan_audit_perdebitur::'.$function,['filter' => 'authfilter']);
    
    #Laporan Visit FC
    $routes->add('report_collection/report_visit_fc/'.$function, '\App\Modules\LaporanVisitFc\Controllers\LaporanVisitFc::'.$function,['filter' => 'authfilter']);

    #InventoryCollection
    $routes->add('reportCols/load_inventory_collection/'.$function, '\App\Modules\InventoryCollection\Controllers\Inventory_collection::'.$function,['filter' => 'authfilter']);
    
    #InventoryWo
    $routes->add('reportCols/load_inventory_wo/'.$function, '\App\Modules\InventoryWo\Controllers\Inventory_wo::'.$function,['filter' => 'authfilter']);
    
    #Inventory90
    $routes->add('reportCols/load_inventory_90/'.$function, '\App\Modules\Inventory90\Controllers\Inventory_90::'.$function,['filter' => 'authfilter']);

    #setup aux
    $routes->add('settings/setupAux/'.$function, '\App\Modules\SetupAux\Controllers\SetupAux::'.$function,['filter' => 'authfilter']);

    #Setup Lov
    $routes->add('settings/lov/'.$function, '\App\Modules\SetupLov\Controllers\SetupLov::'.$function,['filter' => 'authfilter']);

}


// if (! $request->is('post')) {
//     return $request->setStatusCode(405)->setBody('Method Not Allowed');
// }