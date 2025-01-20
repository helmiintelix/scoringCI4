kondisi_khusus_pl = JSON.parse(kondisi_khusus_pl);
bucket_pl = JSON.parse(bucket_pl);

var rules_new;
var add_filter = daftar_discount_parameter;

$('#query_builder').queryBuilder({
    // plugins: ['bt-tooltip-errors'],

    filters: add_filter, //define di admin_main_view.php
    rules: rules_new
});

$('#btn-get').on('click', function() {
  var result = $('#query_builder').queryBuilder('getSQL', false, true);

  if (!$.isEmptyObject(result)) {
    alert(JSON.stringify(result, null, 2));
  }
});

$('#query_builder').on('change',function(){
  var selectedValue = $(this).val();
  console.log('Nilai yang dipilih:', selectedValue);
  console.log("test")
  getQueryBuilder();
});

function getQueryBuilder(){
  let check = $('#query_builder').queryBuilder('getRules');
  console.log(check);
  console.log("ceekcekec")
  if(check== null){
    console.log('null');
  }else{
    let flag = $('#hirarki_flag').val();
    $.each(check.rules,function(i,val){
        
        
        // if(val.field=='PRODUCT_ID'){
          addOptionKondisiKhusus(val.value);
          addOptionBucket(val.value);
        // }
        if(flag=='3'){
            hide_all();
          
                $('#form-group-max-nor-disc-rate').show();
                $('#form-group-max-nor-disc-princt-rate').show();
                $('#form-group-max-nor-disc-int-rate').show();
            

        }
    })
  }
}  

function addOptionKondisiKhusus(product){
  // console.log('product'.product);
  $("#desc_kondisi_khusus").html('');
  
        $.each(kondisi_khusus_pl,function(i,val){
            // console.log('val',val);
            if(i!=''){
                $("#desc_kondisi_khusus").append('<option value="'+i+'" >'+i+' - '+val+'</option>');
            }else{
                $("#desc_kondisi_khusus").append('<option value="'+i+'" >'+val+'</option>');
            }
            
        });
  
    
    setTimeout(() => {
      $("#desc_kondisi_khusus ").trigger("chosen:updated");  
    }, 100);
    
}

function addOptionBucket(product){
  if($("#bucket_id").val()!=null){
    return false;
  }
  $("#bucket_id").html('');
 
        $.each(bucket_pl,function(i,val){
            // console.log('val',val);
            if(i!=''){
                $("#bucket_id").append('<option value="'+i+'" >'+i+' - '+val+'</option>');
            }else{
                $("#bucket_id").append('<option value="'+i+'" >'+val+'</option>');
            }          
        });
    
    setTimeout(() => {
      $("#bucket_id").trigger("chosen:updated");  
    }, 100);
    
}

function hide_all(){
  $('#form-group-desc-kondisi-khusus').hide();
  $('#form-group-max-kondisi-khusus-discount-rate').hide();
  $('#form-group-max-permanent-block-discount-rate').hide();
  $('#form-group-max-nor-disc-rate').hide();
  $('#form-group-max-nor-disc-princt-rate').hide();
  $('#form-group-max-nor-disc-int-rate').hide();

  $('#desc_kondisi_khusus').val('');  
  $('#txt-max-kondisi-khusus-discount-rate').val('');  
  $('#txt-max-permanent-block-discount-rate').val(''); 

  $('#txt-max-normal-discount-rate').val('');
  $('#txt-max-normal-discount-principle-rate').val('');
  $('#txt-max-normal-discount-interest-rate').val('');
}

$('.filterme').keypress(function(eve) {
  if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
    eve.preventDefault();
  }

  // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
  $('.filterme').keyup(function(eve) {
    if ($(this).val().indexOf('.') == 0) {
      $(this).val($(this).val().substring(1));
    }
  });
});

$('#hirarki_flag').on('change',function(){
  let flag = $(this).val();
  hide_all();
  
  if(flag=='1'){
        getQueryBuilder();
        $('#form-group-desc-kondisi-khusus').show();
        $('#form-group-max-kondisi-khusus-discount-rate').show();
  }else if(flag=='2'){
        $('#form-group-max-permanent-block-discount-rate').show();
  }else if(flag=='3'){
        $('#query_builder').change();
        $('#txt-max-normal-discount-rate').val('');
        $('#txt-max-normal-discount-principle-rate').val('');
        $('#txt-max-normal-discount-interest-rate').val('');
  }else{

  }

}); 

//   $(document).ready(function(){
//     $("#bucket_id , #desc_kondisi_khusus").chosen({width: "400px",no_results_text: "Tidak ditemukan"});
//     setTimeout(() => {
//       $('.rules-group-header').children().children().next()[0].style= "display:none";  
//     }, 200);
//  })

 $(document).ready(function () {
  $("#bucket_id , #desc_kondisi_khusus").chosen({
      width: "100%",
      no_results_text: "Tidak ditemukan"
  });
  setTimeout(() => {
    $('.rules-group-header').children().children().next()[0].style = "display:none";
  }, 200);
})