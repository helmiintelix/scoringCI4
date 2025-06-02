var USER_IDX = USER_ID;
var total_unread = 0;

var HOST = "https://democoll.ecentrix.net:8201";
var WEBHOOK = "https://democoll74.ecentrix.net/webhook_cms_ci4/";

const socketPushNotif = io(HOST, { transports: ["websocket"] });

socketPushNotif.on("connect", () => {
  //console.log('socket');
  socketPushNotif.emit(
    "register",
    { USER_ID: USER_IDX, SOCKET_ID: socketPushNotif.id },
    (response) => {
      console.log(response); // "got it"
    }
  );

  socketPushNotif.on("receivenotif", (arg, callback) => {
    console.log("arg", arg);

    showInfo(arg.message, 3000);
    renderNotif(arg);
    let a = callback("OKE!");
    console.log("callback", a);
  });

  /*socketPushNotif.on('server-webhook-whatsapp', function (data) {
        try {
            // Dekripsi data yang diterima
            //const decryptedData = JSON.parse(common.decrypt(data, process.env.ACCESS_TOKEN_SECRET));
            const decryptedData = JSON.parse(data);
            let dateString = decryptedData.results[0].receivedAt;

            // Membuat objek Date
            let date = new Date(dateString);

            // Fungsi untuk menambahkan 0 jika angka kurang dari 10
            
            var account_no= getUserAccountNo(decryptedData.results[0].from);
            console.log('iki yaa gesss')
            console.log(account_no)
            // Mendapatkan komponen tanggal dan waktu
            let year = date.getFullYear();
            let month = padZero(date.getMonth() + 1); // Bulan dimulai dari 0
            let day = padZero(date.getDate());
            let hours = padZero(date.getHours());
            let minutes = padZero(date.getMinutes());
            let seconds = padZero(date.getSeconds());

            // Format tanggal ke yyyy-mm-dd HH:ii:ss
            let formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            
            //jika reply masih dihandle bot
            if (decryptedData.results[0].message.type=='CHATBOT') {
                $(".conversation-container").append('<div class="message sent"><span id="random">'+decryptedData.results[0].message.text+'</span><span class="metadata"><span class="time">'+decryptedData.results[0].receivedAt+'</span></span><span class="metadata" style="float: left;"><span class="time"><i  class="bi bi-check2-all tooltip_all" id="tooltip_all'+$('#coba_tooltips .message.sent').length+'"></i></span></span></div>');
        
            }else{
                $(".conversation-container").append('<div class="message received"><span id="random">'+decryptedData.results[0].message.text+'</span><span class="metadata"><span class="time">'+formattedDate+'</span></span></div>');
                
                //jika cust reply dengan 1 mau chat to agent
                if (decryptedData.results[0].message.text=='0') {
                    $("#txt_wa").prop('disabled',false)
                    $("#btn_send_wa").prop('disabled',false)
                }
            }
            // Lakukan sesuatu dengan data pushnotif yang diterima
            // Misalnya, proses atau simpan data ke database atau lakukan tindakan lainnya
        } catch (error) {
            console.log('errr:');
            //console.error(moment.tz('Asia/Jakarta').format('YYYY-MM-DD HH:mm:ss'), 'Error processing pushnotif:', error);
        }
    });*/

  socketPushNotif.on("server-webhook-whatsapp", function (data) {
    console.log("new message", data);
    try {
      const decryptedData = JSON.parse(data);
      let dateString = decryptedData.results[0].receivedAt;

      let date = new Date(dateString);

      getUserAccountNo(
        decryptedData.results[0].from,
        decryptedData.results[0].messageId
      )
        .then(function (ret_data) {
          console.log("iki yaa gesss");
          console.log(ret_data); // Akan mendapatkan account_no yang benar, bukan undefined
          let htmlAppend = "";
          let keymsg = uuid();
          let year = date.getFullYear();
          let month = padZero(date.getMonth() + 1);
          let day = padZero(date.getDate());
          let hours = padZero(date.getHours());
          let minutes = padZero(date.getMinutes());
          let seconds = padZero(date.getSeconds());

          let formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

          if (decryptedData.results[0].message.type == "CHATBOT") {
            $("#coba_tooltips" + ret_data.account_no).append(
              '<div class="message sent"><span id="random">' +
                decryptedData.results[0].message.text +
                '</span><br><span class="metadata"><span class="time">' +
                decryptedData.results[0].receivedAt +
                '</span></span><span class="metadata" style="float: left;"><span class="time"><i class="bi bi-check2-all tooltip_all" id="tooltip_all' +
                $("#coba_tooltips" + ret_data.account_no + " .message.sent")
                  .length +
                '"></i></span></span></div>'
            );
          } else {
            if (decryptedData.results[0].message.type == "IMAGE") {
              $("#coba_tooltips" + ret_data.account_no).append(
                '<div class="message received"><img src="' +
                  WEBHOOK +
                  "api/file_upload/" +
                  ret_data.file_name +
                  '" ondblclick="download_file(this)" id="' +
                  ret_data.file_name +
                  '" alt="" width="185" height="200"><br><span id="random">' +
                  decryptedData.results[0].message.caption +
                  '</span><br><span class="metadata"><span class="time">' +
                  formattedDate +
                  "</span></span></div>"
              );
            } else if (decryptedData.results[0].message.type == "VIDEO") {
              $("#coba_tooltips" + ret_data.account_no).append(
                '<div class="message received">' +
                  '<video width="185" height="200" controls>' +
                  '<source src="' +
                  WEBHOOK +
                  "api/file_upload/" +
                  ret_data.file_name +
                  '" type="' +
                  ret_data.file_type +
                  '">' +
                  "Your browser does not support the video tag." +
                  "</video><br>" +
                  '<span id="random">' +
                  decryptedData.results[0].message.caption +
                  "</span><br>" +
                  '<span class="metadata"><span class="time">' +
                  formattedDate +
                  "</span></span>" +
                  "</div>"
              );
            } else if (decryptedData.results[0].message.type == "AUDIO") {
              $("#coba_tooltips" + ret_data.account_no).append(
                '<div class="message received">' +
                  '<video width="185" height="200" controls>' +
                  '<source src="' +
                  WEBHOOK +
                  "api/file_upload/" +
                  ret_data.file_name +
                  '" type="' +
                  ret_data.file_type +
                  '">' +
                  "Your browser does not support the audio element." +
                  "</video><br>" +
                  '<span id="random">' +
                  decryptedData.results[0].message.caption +
                  "</span><br>" +
                  '<span class="metadata"><span class="time">' +
                  formattedDate +
                  "</span></span>" +
                  "</div>"
              );
            } else if (decryptedData.results[0].message.type == "DOCUMENT") {
              $("#coba_tooltips" + ret_data.account_no).append(
                '<div class="message received">' +
                  '<a href="' +
                  WEBHOOK +
                  "api/file_upload/" +
                  ret_data.file_name +
                  '" target="_blank"><br>' +
                  '<i class="bi bi-file-earmark-pdf"></i> ' +
                  ret_data.file_name +
                  "</a><br>" +
                  '<span id="random">' +
                  decryptedData.results[0].message.caption +
                  "</span><br>" +
                  '<span class="metadata"><span class="time">' +
                  formattedDate +
                  "</span></span>" +
                  "</div>"
              );
            } else {
              console.log("masuk sini");
              htmlAppend =
                '<div class="message received"><span id="random">' +
                decryptedData.results[0].message.text +
                "<br></span>" +
                '<span class="metadata">' +
                '<span class="time" id="show_time_' +
                keymsg +
                '">' +
                formattedDate +
                "</span></span>" +
                '<input type="hidden" class="time_notification" id = "id_show_time_' +
                keymsg +
                '" show_time = "show_time_' +
                keymsg +
                '" value="' +
                formattedDate +
                '" >';
              $("#coba_tooltips" + ret_data.account_no).append(htmlAppend);
              console.log("masuk sini", htmlAppend);
              // $("#coba_tooltips" + ret_data.account_no).append('<div class="message received"><span id="random">' + decryptedData.results[0].message.text + '</span><br><span class="metadata"><span class="time">' + formattedDate + '</span></span></div>');
            }

            if (decryptedData.results[0].message.text == "0") {
              $("#txt_wa").prop("disabled", false);
              $("#btn_send_wa").prop("disabled", false);
              $("#inbound_message_id").val(decryptedData.results[0].messageId);
              $("#to_number").val(decryptedData.results[0].from);
              $.ajax({
                type: "GET",
                url: GLOBAL_MAIN_VARS["SITE_URL"] + "PushNotif/getInboundId",
                dataType: "json",
                data: {
                  USER_ID: USER_IDX,
                  no_hp: decryptedData.results[0].from,
                  account_no: ret_data.account_no,
                },
                success: function (msg) {
                  if (msg) {
                    $("#inbound_message_id").val(msg.inbound_message_id);
                  }
                },
                error: function (error) {
                  reject(error); // Menangani error jika ada masalah dalam request
                },
              });
            }
          }
          updateShowTime();
          // $('#waConversationActivity .container').scrollTop($('#waConversationActivity .container')[0].scrollHeight);
          $("#waConversationActivity .container").animate(
            {
              scrollTop: 99 * 99,
            },
            1000
          );
        })
        .catch(function (error) {
          console.log("Error:", error);
        });
    } catch (error) {
      console.log("errr:", error);
    }
  });
});

socketPushNotif.on("disconnect", () => {
  console.log(socketPushNotif.id); // undefined
});
socketPushNotif.on("connect_error", () => {
  console.log("connect_error"); // undefined
});

var sendNotification = (notification_id) => {
  socketPushNotif.emit(
    "pushnotif",
    {
      notification_id: notification_id,
    },
    (response) => {
      console.log(response); // "got it"
    }
  );
};

var getFirst = () => {
  $.ajax({
    type: "POST",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "/PushNotif/getFirst",
    async: true,
    dataType: "json",
    data: { USER_ID: USER_IDX },
    success: function (msg) {
      if (msg.success) {
        //clear first
        $("#listNotification").html("");
        // total_unread = msg.total_unread;
        // if (total_unread > 0) {
        //     $("#notif_new_total_1").html(msg.total_unread).show();
        // }

        $("#notif_new_total_2").html(msg.total_unread);
        $.each(msg.data, (i, val) => {
          renderNotif(val);
        });
      }
    },
  });
};

var renderNotif = (data) => {
  // console.log('data', data);
  total_unread = data.total_unread;
  if (total_unread > 0) {
    $("#notif_new_total_1").html(data.total_unread).show();
  }
  $("#notification_" + data.notification_id).remove();
  $("#divider_" + data.notification_id).remove();

  let now = new Date().getTime();
  let notif_time = new Date(data.created_time);

  let difTime = now - notif_time;

  let isRead = data.is_read;
  let style = "cursor: pointer;";
  let notif = "";
  let icon = "";
  let time = timeDif(data.created_time);

  // 'INFORMATION','WARNING','DANGER','SUCCESS'
  if (data.notification_type == "INFORMATION") {
    icon = '<i class="bi bi-info-circle text-primary"></i>';
  } else if (data.notification_type == "WARNING") {
    icon = '<i class="bi bi-info-circle text-warning"></i>';
  } else if (data.notification_type == "DANGER") {
    icon = '<i class="bi bi-x-circle text-danger"></i>';
  } else if (data.notification_type == "SUCCESS") {
    icon = ' <i class="bi bi-check-circle text-success"></i>';
  }

  if (isRead == "0") {
    style = 'style="background-color: beige;cursor: pointer;"';
    notif =
      '<span id="flagNew_' +
      data.notification_id +
      '" class="badge bg-danger float-sm-end" style="font-size: 8px;">New</span>';
  }

  let html =
    '<li id="notification_' +
    data.notification_id +
    '" class="notification-item" ' +
    style +
    " onclick=\"loadMenu('" +
    data.menu_desc +
    "' ,'" +
    data.url +
    "');readNotif('" +
    data.notification_id +
    "')\" > " +
    icon +
    '<div style="width:100%"> ' +
    '<div class="row" style="margin-bottom: -6px;">' +
    '<div class="col">' +
    '<p class="text-muted" style="font-style: italic;opacity: 0.5;font-size:11px">from: ' +
    data.fromName +
    "</p>" +
    "</div>" +
    '<div class="col">' +
    notif +
    "</div>" +
    "</div>" +
    '<span style="font-weight: bold;font-size: 15px;">' +
    data.title +
    "</span>" +
    '<p class="float-sm-end" id="show_time_' +
    data.notification_id +
    '">' +
    time +
    "</p>" +
    '<p style="font-size:12px;max-width: 300px;" class="" >' +
    data.message +
    "</p>" +
    '<input type="hidden" class="time_notification" id="id_show_time_' +
    data.notification_id +
    '" show_time = "show_time_' +
    data.notification_id +
    '" value="' +
    data.created_time +
    '" >' +
    "</div>" +
    "</li>" +
    '<li id="divider_' +
    data.notification_id +
    '">' +
    '<hr class="dropdown-divider">' +
    "</li>";
  $("#listNotification").prepend(html);
};

var timeDif = (time, returnWithTime = false) => {
  var date1 = new Date(time);
  var date2 = new Date();

  let bulanTahunNotif = date1.getMonth() + "" + date1.getFullYear();
  let bulanTahunCurrent = date2.getMonth() + "" + date2.getFullYear();
  if (bulanTahunNotif == bulanTahunCurrent) {
    //jika di bulan dan tahun yang sama
    if (date1.getDate() == date2.getDate()) {
      //jika di hari yang sama
      var diff = date2.getTime() - date1.getTime();

      var msec = diff;
      var hh = Math.floor(msec / 1000 / 60 / 60);
      msec -= hh * 1000 * 60 * 60;
      var mm = Math.floor(msec / 1000 / 60);
      msec -= mm * 1000 * 60;
      var ss = Math.floor(msec / 1000);
      msec -= ss * 1000;

      // console.log(hh + ":" + mm + ":" + ss);
      if (hh >= 6) {
        return "a few hours ago"; //lebih dari sama dengan 6 jam
      } else if (hh <= 5 && hh >= 2) {
        //selisih sama dengan 2-5 jam
        return hh + " hours ago";
      } else if (hh == 1) {
        //selisih 1 jam
        return "an hour ago";
      } else if (hh < 1 && mm > 2) {
        //selisih kurang dari 1 jam dan lebih dari 2 menit
        return mm + " minutes ago";
      } else {
        return "just now";
      }
    } else if (date1.getDate() != date2.getDate()) {
      //jika di hari yang berbeda
      let selisih = date2.getDate() - date1.getDate(); //mencari selisih
      if (selisih == 1) {
        return "yesterday";
      } else if (selisih > 1 && selisih < 7) {
        return selisih + " days ago";
      } else {
        let tanggal =
          date1.getDate() < 10 ? "0" + date1.getDate() : date1.getDate();
        let bulan =
          date1.getMonth() + 1 < 10
            ? "0" + (date1.getMonth() + 1)
            : date1.getMonth() + 1;
        let tahun = date1.getFullYear();

        if (returnWithTime) {
          return time;
        } else {
          return tahun + "-" + bulan + "-" + tanggal;
        }
      }
    }
  } else {
    let tanggal =
      date1.getDate() < 10 ? "0" + date1.getDate() : date1.getDate();
    let bulan =
      date1.getMonth() + 1 < 10
        ? "0" + (date1.getMonth() + 1)
        : date1.getMonth() + 1;
    let tahun = date1.getFullYear();

    if (returnWithTime) {
      return time;
    } else {
      return tahun + "-" + bulan + "-" + tanggal;
    }
  }
};

var readNotif = (id) => {
  $.ajax({
    type: "POST",
    url: GLOBAL_MAIN_VARS["SITE_URL"] + "PushNotif/readNotif",
    async: true,
    dataType: "json",
    data: { USER_ID: USER_IDX, notification_id: id },
    success: function (msg) {
      $("#flagNew_" + id).remove();
      $("#notification_" + id).attr("style", "cursor: pointer");
      total_unread -= 1;
      if (total_unread == 0) {
        $("#notif_new_total_1").html(total_unread).hide();
        $("#notif_new_total_2").html(total_unread);
      }
      if (total_unread < 0) {
        total_unread = 0;
      }
    },
  });
};

var updateShowTime = () => {
  let time = $(".time_notification");
  $.each(time, (i, val) => {
    let idTime = $(val).attr("show_time");

    if (val.value != "null" && val.value != "") {
      let time = timeDif(val.value);
      $("#" + idTime).html(time);
    } else {
      $("#" + idTime).html("-");
    }
  });
};

function padZero(num) {
  return num < 10 ? "0" + num : num;
}

var getUserAccountNo = (no_hp, messageId) => {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "GET",
      url: GLOBAL_MAIN_VARS["SITE_URL"] + "PushNotif/getUserAccountNo",
      dataType: "json",
      data: { USER_ID: USER_IDX, no_hp: no_hp, messageId: messageId },
      success: function (msg) {
        /*if (msg && msg.length > 0) {
                    console.log('iki');
                    resolve(msg[0].contract_number_handling);  // Mengembalikan nilai sebagai hasil resolve
                    resolve({
                        account_no: msg[0].contract_number_handling,
                        paired_id: msg[0].paired_id  // Asumsi paired_id adalah bagian dari response
                    });
                } else {
                    console.log('ora');
                    resolve(0);  // Mengembalikan 0 jika tidak ada data
                    resolve({
                        account_no: 0,  // Jika tidak ada data, kembalikan nilai default
                        paired_id: 0    // Kembalikan paired_id sebagai 0
                    });
                }*/

        if (msg) {
          resolve({
            account_no: msg.contract_number_handling,
            file_name: msg.pairedMessageId, // Asumsi paired_id adalah bagian dari response
            file_type: msg.callbackData,
          });
        } else {
          resolve({
            account_no: 0, // Jika tidak ada data, kembalikan nilai default
            file_name: 0, // Kembalikan paired_id sebagai 0
            file_type: 0,
          });
        }
      },
      error: function (error) {
        reject(error); // Menangani error jika ada masalah dalam request
      },
    });
  });
};

getFirst();
setTimeout(() => {
  setInterval(() => {
    updateShowTime();
  }, 2000);
}, 3000);
