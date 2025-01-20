function isActive(elm){
    if($(elm)[0].checked){
        $("#opt-active-flag").val('Y').change();
        // $("label[for='flexSwitchCheckChecked']").text('Active');
    }else{
        $("#opt-active-flag").val('N').change();
        // $("label[for='flexSwitchCheckChecked']").text('Not Active');
    }
}

$('#list_group').select2({
    placeholder: "Select options",
    allowClear: true,
    dropdownParent: $('#newModal')
});
