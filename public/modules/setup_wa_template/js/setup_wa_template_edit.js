
var maxParam = 5000;
var zz=1; 
var cc=1;
var entries = Object.entries(select);

function isActive(elm){
    if($(elm)[0].checked){
        $("#opt-active-flag").val('1').change();
        // $("label[for='flexSwitchCheckChecked']").text('Active');
    }else{
        $("#opt-active-flag").val('0').change();
        // $("label[for='flexSwitchCheckChecked']").text('Not Active');
    }
}


$("#txt_wa_max_retry").keyup(function () {
  var curchr = this.value.replaceAll(" ", "").length;
  var curval = $(this)
    .val()
    .replace(/[^0-9]/g, "");

  $("#txt_wa_max_retry").val(curval);
});


function addField() {
    $('#txt_wa_template_template_design').val($('#txt_wa_template_template_design').val()+$("#opt_field_list").val());		
    return false;
}

$("body").on("click", ".btn_rmv_params", function(){
    zz=zz-1;
    $('#form_'+this.id).remove(); 
});

var arrParams = params.split("|");
if (arrParams) {
    var params1 = document.getElementById("txt-template-input-param1");
    params1.value = arrParams[0];
    zzz = arrParams.length;
}

for (ix = 1; ix < arrParams.length; ix++) {
    $('#dynamic_field').append(`
        <div id="form_btn_rmv_params${ix}" class="form-group" style="display: flex; align-items: center;">
            <select class="form-control form-control-sm mandatory" name="txt-template-input-param[]" id="txt-template-input-param${ix}" value="[[CM_CUSTOMER_NMBR]]">
            ${
                entries.map(item => {
                    if(item[0] === arrParams[ix]) {
                        return `<option value="${item[0]}" selected>${item[1]}</option>`;
                    } else {
                        return `<option value="${item[0]}">${item[1]}</option>`;
                    }
                })
            }
            </select>
            <i  class="bi bi-x-square btn_rmv_params" id="btn_rmv_params${ix}" style="color:red;cursor: pointer;font-size:25px; margin-left:8px;"></i>
        </div>`);
}


cc=ix;
zz=ix;
     
$('.btn_add_param').click(function(){   

    // Memeriksa apakah batas maksimum parameter telah tercapai
    if (zz == 5) {
        alert("Maksimal waktu pengiriman adalah " + maxParam);
    } else {
        // Menambah variabel penghitung
        zz++;
        cc++;
        
        let html = '';
        html += `<div id="form_btn_rmv_params${zz}" class="form-group" style="display: flex; align-items: center;">`;
        html += `<select name="txt-template-input-param[]" class="form-control form-control-sm mandatory" id="txt-template-input-param'+cc+'" data-placeholder="[select]" required="">`;
        $.each(arrSelect, function(key, value) {
            html += `<option value="${key}">${value}</option>`;
        });
        html += `</select>`;
        html += `<i class="bi bi-x-square btn_rmv_params" id="btn_rmv_params${zz}" style="color:red;cursor: pointer;font-size:25px; margin-left:8px;"></i>`;
        html += `</div>`;
        $('#dynamic_field').append(html);
    }
});