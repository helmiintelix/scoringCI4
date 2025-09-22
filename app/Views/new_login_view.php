<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login - Collection Managament System</title>
  <link rel="icon" href="<?= base_url(); ?>assets/Logo_ecentrix.svg">
  <meta content="" name="description">
  <meta content="" name="keywords">
  <style>
    .css-selector {
        background: linear-gradient(127deg, #1047fa, #f8721e);
        background-size: 400% 400%;

        -webkit-animation: AnimationName 5s ease infinite;
        -moz-animation: AnimationName 5s ease infinite;
        animation: AnimationName 5s ease infinite;
    }

    @-webkit-keyframes AnimationName {
        0%{background-position:0% 71%}
        50%{background-position:100% 30%}
        100%{background-position:0% 71%}
    }
    @-moz-keyframes AnimationName {
        0%{background-position:0% 71%}
        50%{background-position:100% 30%}
        100%{background-position:0% 71%}
    }
    @keyframes AnimationName {
        0%{background-position:0% 71%}
        50%{background-position:100% 30%}
        100%{background-position:0% 71%}
    }

      


    .background-div {
      position: relative;
      width: 100%;
      height: 400px;
    }

    .background-div::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('./assets/ecentrixTower.jpeg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      filter: opacity(10%);
      z-index: -1; /* Agar latar belakang berada di bawah konten lain */
    }

    .background-div img {
      position: relative;
      z-index: 1; /* Agar gambar tetap berada di atas latar belakang */
    }
  </style>
  <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-5.0.2/css/bootstrap.min.css" /> -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-5.0.2/boxicons.min.css" />
  <link rel="stylesheet" href="<?= base_url(); ?>assets/bootstrap-5.3.2-dist/css/bootstrap.min.css" />
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?= base_url(); ?>assets/nice_admin/vendor/simple-datatables/style.css" rel="stylesheet">
  <script src="<?= base_url(); ?>assets/bootstrap-5.0.2/js/jquery-3.6.4.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery/jquery.form.js"></script>
  <script>
    var key = '<?= $key; ?>';
  </script>
  <link href="<?= base_url(); ?>/assets/nice_admin/css/style.css" rel="stylesheet">
  <script src="<?= base_url(); ?>private/js/login.js"></script>
  
</head>

<body>

  <main class="bg-secondary bg-opacity-10">
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container" >
          <div id="card-login" style="display:none" class="row justify-content-center">
           
              <div class="shadow-lg col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center rounded-start-2 background-div">
                <img src="<?= base_url(); ?>assets/Logo_ecentrix.svg" class="img-fluid py-5" alt="Page Not Found" style="height: 300px;">
              </div>
              <div class=" shadow-lg col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center p-4 rounded-end-2" style="background-color: white;height: 400px;">
            

                    <div class="pt-4 pb-2">
                      <a href="#" class="logo d-flex align-items-center w-auto p-1 justify-content-center">
                        <span class="d-none d-sm-block">Scoring</span>
                      </a>
                      <p class="text-center small">Enter your username & password to login</p>
                    </div>

                    <form class="row g-3 needs-validation" id="form_login">
                    <input type="hidden" id="tokenCsrf" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Username</label>
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">@</span>
                          <input type="text" name="username" class="form-control" id="yourUsername" required>
                          <div class="invalid-feedback">Please enter your username.</div>
                        </div>
                      </div>

                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <div class="input-group has-validation" style="cursor:pointer">
                          <span class="input-group-text" id="showPass" onClick="showPass()"><i class="bi bi-eye"></i></span>
                          <input type="password" name="password" class="form-control" id="yourPassword" required>
                          <div class="invalid-feedback">Please enter your password.</div>
                        </div>
                      </div>
                      
                      <!-- <div class="col-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                          <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                      </div> -->
                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit" id="sign_in">Login</button>
                        <button class="btn btn-primary w-100" type="button" id="sign_in_progress" disabled style="display:none">
                          <span class="spinner-border spinner-border-sm " aria-hidden="true"></span>
                          <span role="status">Loading...</span>
                        </button>
                      </div>
                      <div class="col-12">
                        <!-- <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p> -->
                      </div>
                    </form>

            

                <div class="credit">
                  <small class='text-secondary' >Designed by Collection Team</small>
                </div>

              </div>
            
          </div>
        </div>

      </section>

    </div>


    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
      <div id="toastpassword" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header text-white bg-warning">
          <strong class="me-auto">information</strong>
          <small>now</small>
        </div>
        <div class="toast-body" id="toast-body">
          <input type="captcha" hidden>
        </div>
      </div>
    </div>

  </main><!-- End #main -->
  <script>
    function checkValidate() {
      var forms = document.querySelectorAll('.needs-validation')
      let valid = true;
      // Loop over them and prevent submission
      Array.prototype.slice.call(forms)
        .forEach(function(form) {

          if (!form.checkValidity()) {

            valid = false;
          }

          form.classList.add('was-validated')

        })

      return valid;
    }
    var GLOBAL_MAIN_VARS = new Array();
    var site_url = "<?= site_url(); ?>";
    GLOBAL_MAIN_VARS["BASE_URL"] = "<?= base_url(); ?>";
    GLOBAL_MAIN_VARS["SITE_URL"] = "<?= base_url(); ?>";
    GLOBAL_MAIN_VARS["CAPTCHA"] = <?=$_ENV['CAPTCHA'];?>;

    function showPass() {
      let yourPassword = $("#yourPassword").attr('type');

      if (yourPassword == 'password') {
        $("#showPass").html('<i class="bi bi-eye-slash"></i>');
        $("#yourPassword").attr('type', 'text');
      } else {
        $("#showPass").html('<i class="bi bi-eye"></i>');
        $("#yourPassword").attr('type', 'password');
      }
    }
    

  </script>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="<?= base_url(); ?>assets/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/chart.js/chart.umd.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/echarts/echarts.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/quill/quill.min.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?= base_url(); ?>assets/nice_admin/vendor/php-email-form/validate.js"></script>
</body>

</html>