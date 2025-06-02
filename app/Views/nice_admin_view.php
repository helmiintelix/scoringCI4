<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <title>CMS</title>
  <link rel="icon" href="<?= base_url(); ?>assets/Logo_ecentrix.svg">
  <!-- <meta content="" name="description">
  <meta content="" name="keywords"> -->


  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-5.3.2-dist/css/bootstrap.min.css" />
  <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-5.0.2/boxicons.min.css" /> -->
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/simple-datatables/style.css" rel="stylesheet">
  
  <link href="<?= base_url(); ?>assets/datetimepicker/daterangepicker.css" rel="stylesheet" type="text/css" media="all">
  
  

  <!-- css query builder terbaru -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/query-builder/dist/css/query-builder.default.css" />


  <link rel="stylesheet" href="<?= base_url(); ?>assets/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-select/dist/css/bootstrap-select.min.css">
  <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-tooltip/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/runningText/runningText.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/bs5treeview-main/src/css/bstreeview.css">


  <link href="<?= base_url(); ?>assets/agGrid/styles_enterprise/ag-grid.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/agGrid/styles_enterprise/ag-theme-alpine.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/agGrid/styles_enterprise/ag-theme-material.min.css" rel="stylesheet">

  <script src="<?= base_url(); ?>assets/bootstrap-5.0.2/js/jquery-3.6.4.min.js"></script>


  <link rel="stylesheet" href="<?= base_url(); ?>assets/js/lou-multi-select/css/multi-select.css">
  <link href="<?= base_url(); ?>assets/nice_admin/css/style.css" rel="stylesheet">

  <link href="<?= base_url(); ?>assets/chosen_v1.8.7/chosen.min.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/select2-4.0.13/dist/css/select2.min.css" rel="stylesheet">
  
  <link href="<?= base_url(); ?>assets/waModule/waModule.css" rel="stylesheet">
  <style>
    .row {
      margin-bottom: 5px;
    }
  </style>
  <style>
  
    #ecentrix {
      position: fixed;
      bottom: 0;
      left: 0;
      margin: 0;
      padding: 0;
      width: 100%;
      height: 56px;
      border: none;
      overflow: hidden !important;
      /* z-index: 10000; */
    }
    
  @font-face {
    font-family: 'runningText';
    src: url('./assets/runningText/TheLedDisplaySt.ttf') format('truetype');
  }

  /* .hover-effect:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.7);
    } */
    /* .shadow-effect{
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.7);
    } */

    @font-face {
        font-family: 'Roboto';
        src: url('./assets/font/Roboto/Roboto-Regular.ttf') format('truetype'); /* Ganti dengan path sesuai */
        font-weight: 400; /* Regular */
        font-style: normal;
    }

    @font-face {
        font-family: 'Roboto';
        src: url('./assets/font/Roboto/Roboto-Bold.ttf') format('truetype'); /* Ganti dengan path sesuai */
        font-weight: 700; /* Bold */
        font-style: normal;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center text-white">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="logo d-flex align-items-center text-white">
        <img src="<?= base_url(); ?>assets/Logo_ecentrix.svg" alt="">
        <span class="d-none d-lg-block text-white">CMS</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn text-white"></i>
    </div><!-- End Logo -->

    <!-- tambahkan class notificationDynamicisland untuk notif kelap kelip ijo-->
    <div class="m-3 d-flex align-items-center justify-content-between " style="width: 100%;" data-animation="stop">
      <div id="scroll-container">
        <div id="scroll-text"><?=@$broadcastMsg?></div>
      </div>
    </div>



    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
      
          <span class="text-secondary" style="margin-right: 10px;font-size: 20px;cursor: context-menu;" >|</span>
          <!-- <a style="margin-right: 20px;" class="nav-link nav-icon text-white" href="#" data-bs-toggle="offcanvas" data-bs-target="#" aria-controls="">
            <i class="bi bi-robot text-white" style="font-size: 15px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="AI"></i>
          </a> -->
          <!-- <a style="margin-right: 20px;" class="nav-link nav-icon text-white" href="#" data-bs-toggle="offcanvas" data-bs-target="#staticBackdropAccountHandling" aria-controls="staticBackdrop">
            <i class="bi bi-card-checklist text-white" style="font-size: 15px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Account Handling"></i>
          </a> -->
        
          <a style="margin-right: 20px;" class="nav-link nav-icon text-white position-relative" href="#" data-bs-toggle="offcanvas" data-bs-target="#staticBackdropWa2way" aria-controls="staticBackdrop" >
            <i class="bi bi-whatsapp text-white" style="font-size: 15px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="whatsapp"></i>
            <span id="iconNotifNewWa" style="width: 11px;height: 11px;top: 11px;" class="position-absolute start-100 translate-middle bg-danger border border-light rounded-circle">
              <span class="visually-hidden" style="width: 5px;">New alerts</span>
            </span>
          </a>

          <!-- #Chat modules -->
          <a style="margin-right: 20px;" class="nav-link nav-icon text-white position-relative" href="#" data-bs-toggle="offcanvas" data-bs-target="#staticBackdropChat" aria-controls="staticBackdrop" >
            <i class="bi bi-chat text-white" style="font-size: 15px" data-bs-toggle="tooltip" data-bs-placement="bottom" title="chat"></i>
            <span id="iconNotifNewChat" style="width: 11px;height: 11px;top: 11px; display:none;" class="position-absolute start-100 translate-middle bg-danger border border-light rounded-circle">
              <span class="visually-hidden" style="width: 5px;">New alerts</span>
            </span>
          </a>
          <!-- #Chat modules -->

        <li class="nav-item dropdown">
          <a class="nav-link nav-icon text-white" href="#" data-bs-toggle="dropdown" >
            <i class="bi bi-bell text-white" style="font-size: 15px"></i>
            <span class="badge bg-danger badge-number" id="notif_new_total_1" style="display:none">0</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="width:400px">
            <div style="overflow-x: hidden;overflow-y: scroll;height: 400px;">
              <li class="dropdown-header">
                You have <span id="notif_new_total_2">0</span> new notifications
                <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <div id="listNotification">
              </div>
              <div>
                <li class="dropdown-footer">
                  <a href="#">Show all notifications</a>
                </li>
          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

    

        <li class="nav-item dropdown pe-3 badge rounded-pill bg-secondary">

          <a class="nav-profile d-flex align-items-center pe-0 text-white" id="linkHeader" href="#" data-bs-toggle="dropdown">
            <?php
            $urlimg = 'uploads/user/' .session()->get('fp');
            if (file_exists($urlimg)) {
              echo '<img src="' . $urlimg . '" alt="Profile" class="rounded-circle" style="width: 20px;height: 20px;">';
            } else {
              echo '<img src="assets/profilePicture/person-circle.svg" alt="Profile" class="rounded-circle" style="width: 20px;height: 20px;">';
            }
            ?>

            <span class="d-none d-md-block dropdown-toggle ps-2"><?=session()->get('USER_NAME'); ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= session()->get('USER_NAME'); ?></h6>
              <span><?= session()->get('USER_GROUP'); ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <div class="dropdown-item d-flex align-items-center">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="changemodetheme">
                  <label class="form-check-label" for="flexSwitchCheckDefault">dark</label>
                </div>
              </div>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <!-- <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> -->
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" id="logout_button" onClick="modalLogout.show();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
        <li class="nav-item d-block d-lg-block">
         
        </li>
      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar shadow-effect">
    <div class="card text-dark bg-warning mb-3 shadow-effect"  style="height: 110px;">
      <div class="card-body ">
        <div style="font-size: 25px;">
          <i id="TELEPHONY_NETWORK_STATE" class="bi bi-bar-chart-fill float-end"></i>
        </div>
        <h6 class="card-subtitle mb-1 text-white" id="TELEPHONY_LEVEL" style="font-size: 13px;font-weight: bold;"><i>loading...</i></h6>
        <h6 class="card-subtitle mb-0 text-white" style="font-style: italic;font-size: 10px;">extension</h6>
        <div style="height: 35px;">
          <h5 class="card-title text-white" style="font-size: 30px;font-weight: 900;" id="TELEPHONY_EXTENSION"><i>loading...</i></h5>
        </div>
        <span class="badge" style="margin-right: -4px;"><i class="bi bi-telephone" style="color: black;"></i></span>
        <span class="badge rounded-pill bg-light text-warning" id="TELEPHONY_CURRENT_STATUS" style="font-size: 10px;"><i>loading...</i></span>  <span class="text-white">  </span> <span class="badge rounded-pill bg-light text-warning" id="TELEPHONY_CURRENT_STATUS_REASON" style="display:none"></span><br>
        <span class="text-white" style="font-size: 11px;"></span>
      </div>
    </div>
    <hr>
    <div class="input-group input-group-sm flex-nowrap">
      <span class="input-group-text" id="addon-wrapping"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control" placeholder="search"  onkeyup="cari_menu(this)">
    </div>
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-heading"><i>loading...</i></li>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main" >
  <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
    <div class="card border-0" style="margin-bottom: 10px;">
      <div class="card-body">
        <b><span class="breadcrumb-item" id="current-page"></span></b>
        <ol class="breadcrumb float-end" id="page-title" style="margin: inherit;">
          <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door-fill"></i></a></li>
        </ol>
      </div>
    </div>
   
  </nav>
    <div class="card border-0" style="margin-bottom: 10px;">
      <div class="card-body">
        <div class="col-sm-12" id="admin-wrapper">
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 999999999">
    <div class="toast" id="showinfo" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
      <div id="toast-header" class="toast-header text-white bg-primary ">
        <strong id="toast-header-title" class="me-auto">Information</strong>
        <small>now</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="body-toast-info">

      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="newModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl" id="modalForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="header-modal">CMS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-body">

        </div>
        <div class="modal-footer" id="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">submit</button> -->
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="newModalFull" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" id="modalFormFull">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="header-modal-full">CMS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-body-full">

        </div>
        <div class="modal-footer" id="modal-footer-full">
          <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">submit</button> -->
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalLogout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-999" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header text-white bg-warning">
          <h5 class="modal-title" id="staticBackdropLabel">Confirm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you going to sign out ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-warning" onClick='$(location).attr("href", "login/logout");'>Sign out</button>
        </div>
      </div>
    </div>
  </div>

  <div class="offcanvas offcanvas-end" tabindex="-1" data-bs-scroll="true" id="staticBackdropWa2way" aria-labelledby="staticBackdropLabel">
      <div class="offcanvas-header bg-success text-white">
        <a href="#" class="link-light" id="canvasLinkBackWa"><i class="bi bi-arrow-left"></i></a>
        <i class="bi bi-whatsapp" id="canvasIconWa"></i>
        <h5 class="offcanvas-title" id="staticBackdropLabel">Whatsapp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body" id="wa2wayCanvas">
    
      </div>
  </div>
  <!-- #Chat modules -->
  <div class="offcanvas offcanvas-end" tabindex="-1" data-bs-scroll="true" id="staticBackdropChat" aria-labelledby="staticBackdropLabel">
      <div class="offcanvas-header bg-warning text-white">
        <a href="#" class="link-light" id="canvasLinkBackChat"><i class="bi bi-arrow-left"></i></a>
        <i class="bi bi-chat" id="canvasIconChat"></i>
        <h5 class="offcanvas-title" id="staticBackdropLabel">Chat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body" id="chatCanvas">
    
      </div>
  </div>
  <!-- #Chat modules -->


  <!-- ======= Footer ======= -->

  <iframe style="height:56px;width:100%; display:none;" src="" id="ecentrix" name="ecentrix" allow="microphone; camera; encrypted-media" class="frame"></iframe>
  <footer id="footer" class="footer">

    <div class="copyright">
      &copy; Copyright <strong><span>PT. INTELIX GLOBAL CROSSING</span></strong>.
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by COLLECTION TEAM
    </div>
    <br>
    <br>
    <div style="display:none;"  id="barTelephony">
    </div>
  </footer><!-- End Footer -->


  <script>
    const ecx8 = document.getElementById('ecentrix').contectWindow;
    var AGENT_ID = "<?= session()->get('USER_ID') ?>";
    var LEVEL_GROUP = "<?= session()->get('LEVEL_GROUP') ?>";
    if (LEVEL_GROUP != 'TELECOLL') {
      var LEVEL_GROUP = "SUPERVISOR";
    }
  </script>

  <script src="<?= base_url(); ?>assets/telephony/cms_ecentrix8.js"></script>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  <!-- Vendor JS Files -->
  <script>
    var GLOBAL_SESSION_VARS = new Array();
    var CSRF = {<?=$csrf_token?> : "<?=$csrf_hash?>"};

    GLOBAL_SESSION_VARS["GROUP_ID"] = "<?= session()->get('GROUP_ID'); ?>";
    GLOBAL_SESSION_VARS["LEVEL_GROUP"] = "<?= session()->get('LEVEL_GROUP'); ?>";

    GLOBAL_MAIN_VARS = new Array();
    GLOBAL_MAIN_VARS["BASE_URL"] = "<?= base_url(); ?>";
    GLOBAL_MAIN_VARS["SITE_URL"] = "<?= site_url(); ?>";
    GLOBAL_MAIN_VARS["SESSION_EXPIRE"] = "<?= $session_expire; ?>";
    GLOBAL_MAIN_VARS["ACTIVE_CALL_NO"]  = "";

    var GLOBAL_VARS = "<?= session()->get('USER_ID'); ?>";
    var USER_ID = "<?= session()->get('USER_ID'); ?>";
    
    var GLOBAL_LEVEL = "AGENT";
    var GLOBAL_HOST = "<?= base_url(); ?>";
    
    var GLOBAL_INTERVAL;

    var GLOBAL_THEME_MODE = 'LIGHT'; // LIGHT or DARK
  </script>
  
  <script src="<?= base_url(); ?>assets/bootstrap-5.3.2-dist/js/popper.min.js"></script> 
  <script src="<?= base_url(); ?>assets/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script> 
  <!-- <script src="<?= base_url(); ?>assets/bootstrap-5.0.2/assets/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/chart.js/chart.umd.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/echarts/echarts.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/simple-datatables/simple-datatables.js"></script>
  
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/php-email-form/validate.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/js/jquery.form.min.js"></script>
  <script src="<?= base_url(); ?>assets/bootbox/bootbox.js"></script>
  <script src="<?= base_url(); ?>assets/push_notification/socket.io.js"></script>
  <script src="<?= base_url(); ?>assets/push_notification/cms_notification.js?v=<?= rand(); ?>"></script>
  <script src="<?= base_url(); ?>assets/fusejs/dist/fuse.js"></script>
  <script src="<?= base_url(); ?>assets/bs5treeview-main/src/js/bstreeview.js"></script>
  <script src="<?= base_url(); ?>assets/chosen_v1.8.7/chosen.jquery.min.js"></script>
  <script src="<?= base_url(); ?>assets/tinymce/js/tinymce/tinymce.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
  <script src="<?= base_url(); ?>assets/select2-4.0.13/dist/js/select2.min.js"></script>
  

  <!-- <script src="<?= base_url(); ?>assets/agGrid/ag-grid-community.min.noStyle.js"></script> -->
  <script src="<?= base_url(); ?>assets/agGrid/ag-grid-enterprise.min.js"></script>
  <!-- <script src="<?= base_url(); ?>assets/agGrid/ag-grid-community.min.js"></script> -->

  <script language='JavaScript'>
    style = "min-height:"
    let height = screen.height - 200;
    $("#main").attr('style', 'min-height:' + height + 'px');
    var txt = "Collection Managament System - ";
    var speed = 300;
    var refresh = null;

    function action() {
      document.title = txt;
      txt = txt.substring(1, txt.length) + txt.charAt(0);
      refresh = setTimeout("action()", speed);
    }
    action();

    var myModal = new bootstrap.Modal(document.getElementById('newModal'), {
      keyboard: true
    })
    var myFullModal = new bootstrap.Modal(document.getElementById('newModalFull'), {
      keyboard: false
    })
    var modalLogout = new bootstrap.Modal(document.getElementById('modalLogout'), {
      keyboard: true
    })
  </script>

  <script type="text/javascript">
    var daftar_plugin = {
      'chosen-selectpicker': {
        'width': '200px',
        'search_contains': 'true'
      },
      'sortable': 'sortable',
      'unique-filter': 'unique-filter',
      'bt-checkbox': 'bt-checkbox',
      'invert': 'invert',
      'not-group': 'not-group'
    };

    var daftar_filter = [
      //	{ id:'', label :'', type : 'integer'},
      {
        id: 'fin_account',
        label: 'Account Number',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=fin_account&type=select&table_name=cpcrd_new"
      },
      // { id:'BILL_BAL', label :'Bill Balance', type : 'integer'},
      {
        id: 'CM_STATUS',
        label: 'Status Rekening',
        type: 'string',
        operators: ['equal', 'not_equal', 'in', 'not_in'],
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_STATUS&type=select"
      },
      // { id:'CM_DOMICILE_BRANCH', label :'Branch',  type : 'string',operators: ['equal', 'not_equal', 'in', 'not_in'],search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=BRANCH_ID_NAME&type=select"},
      {
        id: 'a.ACCOUNT_TAGGING',
        label: 'Account Tagging',
        type: 'string',
        operators: ['equal', 'not_equal', 'in', 'not_in'],
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=ACCOUNT_TAGGING&type=select"
      },
      {
        id: 'CM_BLOCK_CODE',
        label: 'Block Code',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_BLOCK_CODE&type=select&table_name=cpcrd_new"
      },
      {
        id: "CM_BUCKET",
        label: "BUCKET",
        type: 'string',
        operators: ['equal', 'not_equal', 'in', 'not_in'],
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=BUCKET&type=select"
      },
      {
        id: 'CM_CARD_NMBR',
        label: 'Loan Number',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_CARD_NMBR&type=select&table_name=cpcrd_new"
      },
      // { id:'CM_DTE_PYMT_DUE_DAY', label :'Due Date (DAYS)', type : 'integer'},
      {
        id: 'CM_DTE_PYMT_DUE',
        label: 'Due Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'CR_ZIP_CODE',
        label: 'Cust Address Zip Code',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_ADDRESS_ZIPCODE&type=select&table_name=cpcrd_ext_address&where=current"
      },
      {
        id: 'CR_EU_SEX',
        label: 'Customer\'s gender',
        type: 'string'
      },
      {
        id: 'DPD',
        label: 'DPD',
        type: 'integer'
      },

      {
        id: 'BPTP_COUNTER',
        label: 'N Times BPTP',
        type: 'integer'
      },
      {
        id: 'CTC_COUNTER',
        label: 'N Times Contact',
        type: 'integer'
      },
      {
        id: 'NCTC_COUNTER',
        label: 'N Times Not Contact',
        type: 'integer'
      },
      // { id:'CM_CARD_EXPIR_DTE_YEAR', label :'Original Maturity Date (YEARS)', type : 'integer'},
      {
        id: 'CM_CARD_EXPIR_DTE',
        label: 'Original Maturity Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'CM_DTE_LST_STMT_DAY', label :'Last Billed Date (DAYS)', type : 'integer'},
      {
        id: 'CM_DTE_LST_STMT',
        label: 'Last Billed Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'CM_DTE_LST_PYMT_DAY', label :'Last payment Date (DAYS)', type : 'integer'},
      {
        id: 'CM_DTE_LST_PYMT',
        label: 'Last Payment Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'CM_DTE_OPENED_YEAR', label :'Open Date (YEARS)', type : 'integer'},
      {
        id: 'CM_DTE_OPENED',
        label: 'Open date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'CM_TOT_BALANCE', label :'Baki Debet', type : 'integer'},
      {
        id: 'CM_CYCLE',
        label: 'Cycle',
        type: 'integer'
      },
      {
        id: 'AGENT_ID',
        label: 'User ID',
        type: 'string',
        operators: ['equal', 'not_equal', 'in', 'not_in'],
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=AGENT_ID&type=select"
      },
      {
        id: 'CM_TYPE',
        label: 'Product Code',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=PRODUCT&type=select"
      },
      {
        id: 'CM_SUB_PRDK_CTG',
        label: 'Product Sub Category',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=PRODUCTSUBCATEGORY&type=select"
      },
      // { id:'DPD_REMINDER', label :'DPD Reminder', type : 'string'},
      // { id:'PTP_REMINDER', label :'PTP Reminder', type : 'string'},
      // { id:'NEW_TO_M1_FLAG', label :'New To M1 Flag', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=NEW_TO_M1_FLAG&type=select"},
      // { id:'CM_NEW_TO_M1FLAG', label :'New To M1 Flag', type : 'string',
      // input: 'select',
      // vertical: true,
      // operators: ['equal', 'not_equal'],
      // //        optgroup: 'core',
      // placeholder: 'Select Flag M1',
      // values: {
      // 'Y' : 'Y',
      // 'N' : 'N'
      // },
      // },
      {
        id: 'CM_DTE_CHGOFF_STAT_CHANGE_MONTH',
        label: 'Charge Of Month',
        type: 'integer'
      },
      {
        id: 'CM_PRDK_TYPE',
        label: 'Product Type',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=PRODUCTTYPE&type=select"
      },
      // { id:'CM_APP_ORG', label :'Origination ID', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=ORIGINATION&type=select"},
      {
        id: 'CM_APPLICATION_TYPE',
        label: 'Application Type',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=APPLICATIONTYPE&type=select"
      },
      {
        id: 'CM_TRANSACTION_TYPE',
        label: 'Transaction Type',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=TRANSACTIONTYPE&type=select"
      },
      // { id:'CM_TYPE', label :'Product Code', type : 'string'},
      {
        id: 'CM_CRLIMIT',
        label: 'Limit',
        type: 'integer'
      },
      {
        id: 'CM_TENOR',
        label: 'Tenor',
        type: 'integer'
      },
      {
        id: 'CM_INTR_PER_DIEM',
        label: 'Interest Rate',
        type: 'integer'
      },
      {
        id: 'CM_INSTALLMENT_AMOUNT',
        label: 'Installment Amount',
        type: 'integer'
      },
      // { id:'CM_TOT_BALANCE', label :'Baki Debet', type : 'string'},
      // { id:'CM_TOT_BALANCE', label :'Baki Debet IDR', type : 'string'},
      // { id:'CM_DTE_PYMT_DUE', label :'Due Date', type : 'string'},
      {
        id: 'CM_DTE_PK',
        label: 'Tanggal PK',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'CM_DTE_LIQUIDATE',
        label: 'Tanggal Pencairan',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'CM_TOT_PRINCIPAL',
        label: 'Tunggakan Pokok',
        type: 'integer'
      },
      {
        id: 'CM_TOT_INTEREST',
        label: 'Tunggakan Bunga',
        type: 'integer'
      },
      {
        id: 'CM_RTL_MISC_FEES',
        label: 'Late Charge',
        type: 'integer'
      },
      {
        id: 'CM_OS_BALANCE',
        label: 'Outstanding Balance',
        type: 'integer'
      },
      {
        id: 'CM_OS_PRINCIPLE',
        label: 'Outstanding Principle',
        type: 'integer'
      },
      {
        id: 'CM_OS_INTEREST',
        label: 'Outstanding Interest',
        type: 'integer'
      },
      {
        id: 'CM_TOTAL_OS_AR',
        label: 'Total Outstanding AR',
        type: 'integer'
      },
      // { id:'DPD', label :'DPD', type : 'string'},
      {
        id: 'CM_COLLECTIBILITY',
        label: 'Collectability',
        type: 'string'
      },
      // { id:'CM_DTE_LST_PYMT', label :'Last Payment Date', type : 'string'},
      // { id:'CM_STATUS', label :'Status Rekening', type : 'string'},
      // { id:'CM_CARD_EXPIR_DTE', label :'Original Maturity Date', type : 'string'},
      {
        id: 'CM_CHGOFF_STATUS_FLAG',
        label: 'Charge Off Status',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CHARGE_OFF_STATUS&type=select"
      },
      {
        id: 'CM_DTE_CHGOFF_STAT_CHANGE',
        label: 'Charge off Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'CM_SECTOR_CODE',
        label: 'Kode Sektor',
        type: 'string'
      },
      {
        id: 'CM_INSTALLMENT_NO',
        label: 'Installment No.',
        type: 'integer',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_INSTALLMENT_NO&type=select&table_name=cpcrd_new"
      },
      {
        id: 'CM_PAST_DUE',
        label: 'Total Kewajiban',
        type: 'integer',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_AMOUNT_DUE&type=select&table_name=cpcrd_new"
      },
      {
        id: 'CM_AMOUNT_DUE',
        label: 'Minimum Payment',
        type: 'string'
      },
      // { id:'CM_DTE_OPENED', label :'Open Date', type : 'string'},
      // { id:'MOB', label :'MoB (Month)', type : 'integer'},
      {
        id: 'CM_PRDK_CTG',
        label: 'Product Category',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_PRODUCT_TYPE&type=select&table_name=cpcrd_new"
      },
      // { id:'CM_PRODUCT_TYPE', label :'Product Type', type : 'string'},
      {
        id: 'CM_PRODUCT_GROUP',
        label: 'Product Group',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_PRODUCT_GROUP&type=select&table_name=cpcrd_new"
      },
      // { id:'CM_BLOCK_CODE', label :'Block Code', type : 'string'},
      {
        id: 'CM_DTE_BLOCK_CODE',
        label: 'Block Code Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'CM_FPD',
        label: 'FPD',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_FPD&type=select&table_name=cpcrd_new"
      },
      {
        id: 'CR_EMPLOYMENT_P_DESC',
        label: 'Employment Position',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_CREDIT_SEGMEN&type=select&table_name=cpcrd_new"
      },
      // { id:'CM_CYCLE', label :'Cycle', type : 'string'},
      {
        id: 'CM_CHGOFF_PRICIPLE',
        label: 'Principle Charge-Off',
        type: 'integer',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_CHGOFF_PRICIPLE&type=select&table_name=cpcrd_new"
      },
      {
        id: 'CR_EU_CUSTOMER_CLASS',
        label: 'Customer Type',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CR_EU_CUSTOMER_CLASS&type=select&table_name=cpcrd_new"
      },
      {
        id: 'CR_MARITAL_STATUS',
        label: 'Marital Status',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CR_MARITAL_STATUS&type=select&table_name=cpcrd_new"
      },
      // { id:'CR_EU_SEX', label :'Gender', type : 'string'},
      // { id:'CR_DTE_BIRTH_DAY', label :'Birth Date (DAYS)', type : 'integer'},
      // { id:'CR_DTE_BIRTH_MONTH', label :'Birth Date (MONTHS)', type : 'integer'},
      {
        id: 'CR_DTE_BIRTH',
        label: 'Birth Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'CR_ID_TYPE', label :'ID Type', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=CR_ID_TYPE&type=select&table_name=cpcrd_new"},
      {
        id: 'CR_OCCUPATION',
        label: 'Occupation',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CR_OCCUPATION&type=select&table_name=cpcrd_new"
      },
      // { id:'position', label :'Position', type : 'string'},
      // { id:'CR_INCOME_BRACKET', label :'Income Bracket', type : 'integer'},
      // { id:'CR_NET_INCOME', label :'Gross Income', type : 'integer'},
      {
        id: 'CM_VIP_FLAG',
        label: 'VIP Flag',
        type: 'string'
      },
      {
        id: 'AGENCY_ID',
        label: 'Agency ID',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=AGENCY_ID&type=select"
      },

      {
        "id": "CM_CUSTOMER_NMBR",
        "label": "CIF",
        "type": "string"
      },
      // { id:'CR_ZIP_CODE', label :'Cust Address Zipcode', type : 'string'},
      {
        id: 'CR_COMPANY_ZIP_CODE',
        label: 'Company zipcode',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_ADDRESS_ZIPCODE&type=select&table_name=cpcrd_ext_address&where=work"
      },
      // { id:'employee_zipcode', label :'Cust. Employee Zipcode', type : 'integer'},
      {
        id: 'CR_MAILING_ZIPCODE',
        label: 'Correspondence Zipcode',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_ADDRESS_ZIPCODE&type=select&table_name=cpcrd_ext_address&where=home"
      },
      {
        id: 'CM_ADDRESS_ZIPCODE',
        label: 'ID Address zipcode',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_ADDRESS_ZIPCODE&type=select&table_name=cpcrd_ext_address&where=ida"
      },
      {
        id: 'CM_DOMICILE_BRANCH',
        label: 'Customer Branch ID',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CM_DOMICILE_BRANCH&type=select"
      },
      // { id:'CM_DOMICILE_BRANCH', label :'Customer Branch ID', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=CM_DOMICILE_BRANCH&type=select&table_name=cpcrd_new"},
      {
        id: 'CM_BRANCH_OFFICE',
        label: 'Customer Branch',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=BRANCH_CODE&type=select"
      },
      // { id:'branch_kode', label :'Customer Main Branch', type : 'integer'},
      // { id:'Kode_Produk', label :'Kode Produk', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PRODUK_KODE_NAMA&type=select"},
      // { id:'CM_TYPE', label :'Nama Produk', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PRODUK_KODE_NAMA&type=select"},
      // { id:'KODE_KATEGORI', label :'Kode Kategori', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=KATEGORI_KODE_NAMA&type=select"},
      // { id:'NAMA_KATEGORI', label :'Nama Kategori', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=KATEGORI_KODE_NAMA&type=select"},
      // { id:'bucket_id', label :'Bucket', type : 'string'},
      {
        id: 'area_id',
        label: 'Branch Area',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=AREA_ID_NAME&type=select"
      },
      // { id:'n_times_broken_ptp', label :'N times of Broken PTP', type : 'string',
      // input: 'select',
      // vertical: true,
      // operators: ['equal', 'not_equal'],
      // //        optgroup: 'core',
      // placeholder: 'Select N Times Broken PTP',
      // values: {
      // 'YES' : 'YES',
      // 'NO' : 'NO'
      // },
      // },
      // { id:'n_times_call_result', label :'n times of call result', type : 'string',
      // input: 'select',
      // vertical: true,
      // operators: ['equal', 'not_equal'],
      // //        optgroup: 'core',
      // placeholder: 'Select N Times Call Result',
      // values: {
      // 'YES' : 'YES',
      // 'NO' : 'NO'
      // },
      // },
      // { id:'n_times_visit_result', label :'n times of visit result', type : 'string',
      // input: 'select',
      // vertical: true,
      // operators: ['equal', 'not_equal'],
      // //        optgroup: 'core',
      // placeholder: 'Select N Times Visit Result',
      // values: {
      // 'YES' : 'YES',
      // 'NO' : 'NO'
      // },
      // },
      {
        id: 'area_tagih_id',
        label: 'Area Tagih',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=AREATAGIH_ID_NAME&type=select"
      },
      // {
      //     id: 'category_name',
      //     label: 'Category LOV',
      //     type: 'string',
      //     search: true,
      //     search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
      //         "classification_detail/get_parameter_list?param=CATEGORY_LOV&type=select"
      // },
      // { id:'tiering_label', label :'Tiering Label Scoring', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=TIERING_LABEL&type=select"},
      // { id:'score_value', label :'Score Scoring', type : 'integer',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=score&type=select"},
      // { id:'tiering_id', label :'Tiering ID Scoring', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=TIERING_ID&type=select"},
      // { id:'call_result', label :'Call Result', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=CALL_VISIT_RESULT&type=select"},
      // { id:'call_date_day', label :'Call Date (DAYS)', type : 'integer'},
      {
        id: 'call_date',
        label: 'Call Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'visit_date_day', label :'Visit Date (DAYS)', type : 'integer'},
      {
        id: 'visit_date',
        label: 'Visit Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      // { id:'ptp_date_day', label :'PTP Date (DAYS)', type : 'integer'},
      {
        id: 'ptp_date',
        label: 'PTP Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },
      {
        id: 'visit_result',
        label: 'Visit Result',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CALL_VISIT_RESULT&type=select"
      },
      {
        id: 'call_result',
        label: 'Call Result',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=CALL_RESULT&type=select"
      },
      {
        id: 'CM_RESTRUCTURE_FLAG',
        label: 'Flag Restructure',
        type: 'string',
        search: true,
        search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
          "classification_detail/get_parameter_list?param=FLAG_RESTRUCTURE_DISKON&type=select"
      },
      {
        id: 'CM_DTE_RESTRUCTURE',
        label: 'Restructure Date',
        type: 'date',
        validation: {
          format: 'YYYY/MM/DD'
        },
        plugin: 'datepicker',
        placeholder: 'yyyy-mm-dd',
        plugin_config: {
          format: 'yyyy-mm-dd',
          todayBtn: 'linked',
          todayHighlight: true,
          autoclose: true
        }
      },

      // {
      //     id: 'n_times_broken_ptp',
      //     label: 'N times of Broken PTP',
      //     type: 'string',
      //     search: true,
      //     search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
      //         "classification_detail/get_parameter_list?param=n_times_broken_ptp&type=select&table_name=cms_bucket_history_parameter"
      // },
      // {
      //     id: 'n_times_call_result',
      //     label: 'n times call result',
      //     type: 'string',
      //     search: true,
      //     search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
      //         "classification_detail/get_parameter_list_khusus_lov?param=lov3_category&type=select&table_name=cms_lov_relation&where=telecoll"
      // },
      // {
      //     id: 'n_times_visit_result',
      //     label: 'n times of visit result',
      //     type: 'string',
      //     search: true,
      //     search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
      //         "classification_detail/get_parameter_list_khusus_lov?param=lov3_category&type=select&table_name=cms_lov_relation&where=fieldcoll"
      // },
      // { id:'flag_reschedule', label :'Flag Reschedule', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=FLAG_RESTRUCTURE_DISKON&type=select"},
      // { id:'reschedule_app_date', label :'Reschedule Approve Date', type : 'date',validation: {format: 'YYYY/MM/DD'},plugin: 'datepicker',placeholder: 'yyyy-mm-dd',
      // plugin_config: {
      // format: 'yyyy-mm-dd',
      // todayBtn: 'linked',
      // todayHighlight: true,
      // autoclose: true
      // }},
      // { id:'flag_diskon', label :'Flag Diskon', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=FLAG_RESTRUCTURE_DISKON&type=select"},
      // { id:'diskon_app_date', label :'Diskon Approve Date', type : 'date',validation: {format: 'YYYY/MM/DD'},plugin: 'datepicker',placeholder: 'yyyy-mm-dd',
      // plugin_config: {
      // format: 'yyyy-mm-dd',
      // todayBtn: 'linked',
      // todayHighlight: true,
      // autoclose: true
      // }},
      // { id:'total_amount_diskon', label :'Total Amount Diskon', type : 'integer'},
      // { id:'sisa_pokok_pinjaman', label :'Sisa Pokok Pinjaman Baru', type : 'integer'},
      // { id:'flag_keep_ptp', label :'Flag Keep PTP', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PAYMENT_STATUS&type=select"},
      // { id:'flag_broken_ptp', label :'Flag Broken PTP', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PAYMENT_STATUS&type=select"},
      // { id:'payment_flag', label :'Payment Flag', type : 'string',search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PAYMENT_STATUS&type=select"},
    ];

    var daftar_restructure_parameter = [{
                id: 'CM_AMOUNT_DUE',
                label: 'OUTSTANDING',
                type: 'integer',
                operators: ['greater_or_equal']
            },
            {
                id: 'MOB',
                label: 'MOB',
                type: 'integer',
                operators: ['greater', 'less', 'greater_or_equal', 'less_or_equal', 'between']
            },
            // { id:"CM_BUCKET", label :"BUCKET", type : 'string',operators: ['equal', 'not_equal', 'in', 'not_in'],search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=BUCKET&type=select"},
            // { id:"PRODUCT_ID", label :"PRODUCT", type : 'string',operators: ['equal', 'not_equal', 'in', 'not_in'],search : true, search_url : GLOBAL_MAIN_VARS["SITE_URL"] + "classification_detail/get_parameter_list?param=PRODUCT&type=select"},
            {
                id: 'CM_BLOCK_CODE',
                label: 'Block Code',
                type: 'string',
                search: true,
                search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
                    "classification_detail/get_parameter_list?param=CM_BLOCK_CODE&type=select&table_name=cpcrd_new"
            },
            {
                id: 'CM_TYPE',
                label: 'Product Code',
                type: 'string',
                search: true,
                search_url: GLOBAL_MAIN_VARS["SITE_URL"] +
                    "classification_detail/get_parameter_list?param=PRODUCT&type=select"
            }
           
        ];

    function QueryBuilderPushValue(search_url, name) {
      let buttons = {
        "success": {
          "label": "<i class='icon-ok'></i> Save",
          "className": "btn-sm btn-success",
          "callback": function() {

            switch ($("#select_type").val()) {
              case "tree":
                let items = [];
                $.each($("input[name='product-list[]']:checked"), function() {
                  items.push($(this).val());
                });
                $('[name=' + name + ']').val(items.join(","));
                $('[name=' + name + ']').change();
                console.log("Items: " + items.join("; "));

                break;
              case "select":
                $('[name=' + name + ']').val($("#opt-param").val().join(","));
                $('[name=' + name + ']').change();
                console.log(name + " | " + $("#opt-param").val().join(","));
                break;
              default:
                $('[name=' + name + ']').val($("#opt-param").val().join(","));
                console.log(name + " | " + $("#opt-param").val().join(","));
                $('[name=' + name + ']').change();
                break;


            }
          }
        },
        "button": {
          "label": "Close",
          "className": "btn-sm"
        }
      }

      showCommonDialog3(900, 300, 'Pilih Parameter', search_url + '&name=' + name, buttons);

    };
  </script>

  <!-- Template Main JS File -->
  <script src="<?= base_url(); ?>assets/nice_admin/js/main.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery-idle.min.js"></script>
  <script src="<?= base_url(); ?>private/js/main.js?v=<?= rand(); ?>"></script>
  <script src="<?= base_url(); ?>private/js/admin_main.js?v=<?= rand(); ?>"></script>

  <!-- #Chat modules -->
  <script>
    var HOST_SOCKET_SERVER = "<?= env('HOST_SOCKET_SERVER') ?>";
    var PORT_SOCKET_SERVER = <?= env('PORT_SOCKET_SERVER') ?>; // Ganti dengan port yang sesuai jika berbeda
  </script>
  <script src="<?= base_url(); ?>assets/socket/socket.io.min.js?v=<?= rand(); ?>"></script>
  <script src="<?= base_url(); ?>private/js/chat.js?v=<?= rand(); ?>"></script>
  <script src="<?= base_url(); ?>private/js/socket.js?v=<?= rand(); ?>"></script>
  <!-- #Chat modules -->

  <script src="<?= base_url(); ?>assets/datetimepicker/moment.min.js"></script>
  <script src="<?= base_url(); ?>assets/datetimepicker/daterangepicker.js"></script>

  <!-- kebutuhan library query builder terbaru -->
  <script src="<?= base_url(); ?>assets/Interact/dist/interact.min.js"></script>
  <script src="<?= base_url(); ?>assets/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
  <script src="<?= base_url(); ?>assets/bootstrap-tooltip/js/bootstrap.min.js"></script>

  <!-- ini query builder terbaru masih belum berjalan -->
  <!-- <script src="<?= base_url(); ?>assets/query-builder/dist/js/query-builder.standalone.js"></script> -->
  <!-- <script src="<?= base_url(); ?>assets/query-builder/dist/js/query-builder.js"></script> -->

  <!-- ini query builder yang lama -->
  <script src="<?= base_url(); ?>assets/js/QueryBuilder/dist/js/query-builder.standalone.js"></script>
  <script src="<?= base_url(); ?>assets/query-builder/dist/js/query-builder.js"></script>
  <!-- <script src="<?= base_url(); ?>assets/js/QueryBuilder/dist/js/sql-parser.min.js"></script> -->




  <script language='JavaScript'>
    $(document).ready(function() {
    
      if (LEVEL_GROUP == 'TELECOLL') {
          getCallCenterConfiguration('AGENT');

      } else {
          getCallCenterConfiguration('SUPERVISOR');
      }
      setTimeout(() => {
          login();

      }, 3000);
      if(getWithExpiry('GLOBAL_THEME_MODE')){
        GLOBAL_THEME_MODE = getWithExpiry('GLOBAL_THEME_MODE');
        changeTheme(GLOBAL_THEME_MODE);
      }

    })


    $('.nav-link').click(function(e){
      
      if(typeof $(e.currentTarget).attr('onclick')==='undefined'){

      }else{
         
        $.each($('.nav-link'), function(i,val){
          $(val).removeAttr('style');
        });

        $(e.currentTarget).attr('style','background:lavender;color:#4154f1');
      }
      
    })

    $('#changemodetheme').change(function() {
        if(this.checked) {
          changeTheme('DARK');
         
        }else{
          changeTheme('LIGHT');
        }     

    });

    function changeTheme(theme){
      if(theme=='DARK') {
       

       GLOBAL_THEME_MODE = 'DARK';
       setWithExpiry('GLOBAL_THEME_MODE','DARK',28800);

       $('header').attr('data-bs-theme','dark').addClass('bg-dark');
      //  $('header span, .nav-icon').addClass('text-white');
       //  $('.toggle-sidebar-btn').addClass('text-white');

       $('.modal').attr('data-bs-theme','dark');
       $('#admin-wrapper').attr('data-bs-theme','dark')

       $("#main, #footer").addClass('bg-dark bg-opacity-75');

       $("#sidebar").addClass('bg-dark'); 
       $(".nav-link").addClass('bg-dark text-white');
       $(".ag-theme-alpine").addClass('ag-theme-alpine-dark').removeClass('ag-theme-alpine');
       
       $('#changemodetheme').prop('checked', true);
       $('*').attr('data-bs-theme', 'dark');

       $("#linkHeader").addClass('text-white');
     }else{
       GLOBAL_THEME_MODE = 'LIGHT';
       setWithExpiry('GLOBAL_THEME_MODE','LIGHT',28800);

       $('header').attr('data-bs-theme','dark').removeClass('bg-dark');
      //  $('header span, .nav-icon').removeClass('text-white');
      // $('.toggle-sidebar-btn').removeClass('text-white');

       $('.modal').attr('data-bs-theme','light');
       $('#admin-wrapper').attr('data-bs-theme','light')
       $("#main, #footer").removeClass('bg-dark bg-opacity-75');

       $("#sidebar").removeClass('bg-dark'); 
       $(".nav-link").removeClass('bg-dark text-white');
       $(".ag-theme-alpine-dark").addClass('ag-theme-alpine').removeClass('ag-theme-alpine-dark');
       $('*').attr('data-bs-theme', 'light');
       
       $("#linkHeader").addClass('text-white');
     }  
    }
  </script>
</body>

</html>