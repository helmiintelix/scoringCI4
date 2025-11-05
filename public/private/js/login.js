var validate = function (formData, jqForm, options) {
  var pass = true;
  var captcha = false;
  var USING_CAPTCHA = GLOBAL_MAIN_VARS["CAPTCHA"];

  $(".mandatory", $(jqForm)).each(function () {
    if (!$(this).val()) {
      $(this).addClass("mandatory_invalid");

      pass = false;
    } else {
      $(this).removeClass("mandatory_invalid");

      $("#sign_in", $(jqForm)).each(function () {});
    }
  });
  if (USING_CAPTCHA) {
    if (document.getElementById("cpatchaTextBox").value != code) {
      captcha = false;
    }
  }
  if (!pass) {
    createCaptcha();
    return false;
  } else {
    if (USING_CAPTCHA) {
      if (!captcha) {
        $.gritter.removeAll();
        $.gritter.add({
          title: "Login form",
          text: "Invalid Captcha. try Again",
          time: 3000,
          class_name: "gritter-error gritter-light",
        });
        document.getElementById("cpatchaTextBox").value = "";
        createCaptcha();
        return false;
      }
    }
    $("#sign_in", $(jqForm)).each(function () {
      // $(this).button('loading');
    });
    setTimeout(function () {
      $("button:contains(loading...)")
        .removeAttr("disabled")
        .removeClass("disabled")
        .text("Sign in");
    }, 5000);
  }
  return pass;
};

var showFormResponse = function (msg) {
  // alert(JSON.stringify(msg.session));
  if (msg.success) {
    $(location).attr("href", GLOBAL_MAIN_VARS["BASE_URL"]);
  } else {
    $("#toast-body").html(DOMPurify.sanitize(msg.msg));
    $("#toastpassword").toast("show");
    createCaptcha();
  }
  setTimeout(() => {
    $("#sign_in").attr("disabled", false).show();
    $("#sign_in_progress").hide();
  }, 1000);
};
async function encryptData(data, key) {
  // Ensure the key is 16 bytes long (128 bits)
  const encoder = new TextEncoder();
  let keyBuffer = encoder.encode(key);
  if (keyBuffer.length < 16) {
    keyBuffer = new Uint8Array([
      ...keyBuffer,
      ...new Uint8Array(16 - keyBuffer.length),
    ]);
  } else if (keyBuffer.length > 16) {
    keyBuffer = keyBuffer.slice(0, 16);
  }

  const encodedData = encoder.encode(data);

  const cryptoKey = await window.crypto.subtle.importKey(
    "raw",
    keyBuffer,
    {
      name: "AES-GCM",
    },
    false,
    ["encrypt"]
  );

  const iv = window.crypto.getRandomValues(new Uint8Array(16));
  const encryptedData = await window.crypto.subtle.encrypt(
    {
      name: "AES-GCM",
      iv: iv,
    },
    cryptoKey,
    encodedData
  );

  return {
    iv: Array.from(iv),
    data: Array.from(new Uint8Array(encryptedData)),
  };
}

var login = async function () {
  if (!validate()) {
    return;
  }
  const username = $("#yourUsername").val();
  const password = $("#yourPassword").val();
  const data = JSON.stringify({
    username: username,
    password: password,
  });
  const datakey = key;
  const encryptedData = await encryptData(data, datakey);
  $("#sign_in").attr("disabled", true).hide();
  $("#sign_in_progress").show();
  $.ajax({
    url: "login/post_login",
    type: "POST",
    data: {
      data: JSON.stringify(encryptedData),
      csrf_security: $("#tokenCsrf").val(),
    },
    dataType: "json",
    success: showFormResponse,
    error: function (err) {
      alert("error");
      setTimeout(() => {
        $("#sign_in").attr("disabled", false).show();
        $("#sign_in_progress").hide();
      }, 1000);
    },
  });
};

function createCaptcha() {
  //clear the contents of captcha div first
  document.getElementById("captcha").innerHTML = "";
  var charsArray =
    "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
  var lengthOtp = 6;
  var captcha = [];
  for (var i = 0; i < lengthOtp; i++) {
    //below code will not allow Repetition of Characters
    var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
    if (captcha.indexOf(charsArray[index]) == -1)
      captcha.push(charsArray[index]);
    else i--;
  }
  var canv = document.createElement("canvas");
  canv.id = "captcha";
  canv.width = 130;
  canv.height = 50;
  var ctx = canv.getContext("2d");
  ctx.font = "oblique 25px 'Comic Sans MS', cursive, sans-serif";
  ctx.strokeText(captcha.join(""), 0, 30);
  //storing captcha so that can validate you can save it somewhere else according to your specific requirements
  code = captcha.join("");
  document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
}

setInterval(createCaptcha, 60000);

function validateCaptcha() {
  if (document.getElementById("cpatchaTextBox").value == code) {
    console.log(document.getElementById("cpatchaTextBox").value);
    return true;
  } else {
    alert("Invalid Captcha. try Again");
    document.getElementById("cpatchaTextBox").value = "";
    createCaptcha();
    return false;
  }
}
$(document).ready(function () {
  $("#card-login").show();
  if (!GLOBAL_MAIN_VARS["CAPTCHA"]) {
    $("#cpatchaTextBox").removeAttr("required");
    $("#cpatchaTextBox").removeClass("mandatory");
  } else {
    createCaptcha();
  }

  $("#username").focus();
  $("#username, #password, #cpatchaTextBox").keypress(function (e) {
    if (e.which == 13) {
      login();
      return false;
    }
  });

  $("#sign_in").click(function () {
    if (checkValidate()) {
      login();
    }
    return false;
  });
});
