
var maxParam = 5000;
var zz=1; 
var cc=1;

function isActive(elm){
    if($(elm)[0].checked){
        $("#opt-active-flag").val('1').change();
        // $("label[for='flexSwitchCheckChecked']").text('Active');
    }else{
        $("#opt-active-flag").val('0').change();
        // $("label[for='flexSwitchCheckChecked']").text('Not Active');
    }
}




/*function addField() {
    $('#txt_wa_template_template_design').val($('#txt_wa_template_template_design').val()+$("#opt_field_list").val());		
    return false;
}*/
     
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
$("body").on("click", ".btn_rmv_params", function(){
    zz=zz-1;
    $('#form_'+this.id).remove(); 
});
