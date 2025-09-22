<div class="row">
    <div class="col col-sm-6">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Pendaftaran Donatur</strong>
            </div>
            <div class="card-body">
                <form role="form" class="needs-validation" id="form_add_donatur" name="form_add_donatur" novalidate>
                    <div class="col col-sm-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="nama">Nama / Lembaga</span>
                            <input type="text" class="form-control" placeholder="..." aria-label="nama" aria-describedby="nama" id="text-nama" name="text-nama" required>
                            <div class="invalid-feedback">
                                Masukan nama donatur
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Alamat</span>
                            <textarea class="form-control" aria-label="With textarea" placeholder="..." rows="4"  id="text-alamat" name="text-alamat" required></textarea>
                            <div class="invalid-feedback">
                                Masukan alamat donatur
                            </div>
                        </div>
                    </div>
                </form>  
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-sm btn-outline-danger" onClick="batal_save_donatur()">Batal</button>
                <button type="button" class="btn btn-sm btn-outline-success" onClick="save_donatur()">Simpan</button>
            </div>
        </div>
    </div>
    <div class="col col-sm-6">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Edit Donatur</strong>
            </div>
            <div class="card-body">
                <form role="form" class="needs-validation" id="form_add_donatur" name="form_add_donatur" novalidate>
                    <div class="col col-sm-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="nama">Nama / Lembaga</span>
                            <input type="text" class="form-control" placeholder="..." aria-label="nama" aria-describedby="nama" id="text-nama" name="text-nama" required>
                            <div class="invalid-feedback">
                                Masukan nama donatur
                            </div>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Alamat</span>
                            <textarea class="form-control" aria-label="With textarea" placeholder="..." rows="4"  id="text-alamat" name="text-alamat" required></textarea>
                            <div class="invalid-feedback">
                                Masukan alamat donatur
                            </div>
                        </div>
                    </div>
                </form>  
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-sm btn-outline-danger" onClick="batal_save_donatur()">Batal</button>
                <button type="button" class="btn btn-sm btn-outline-success" onClick="save_donatur()">Simpan</button>
            </div>
        </div>
    </div>
  
</div>
<div class='row'> 
    <div class="col col-sm-12">
        <ol class="list-group list-group-numbered" id="list-donatur">
        </ol>
    </div>
</div>
<script type="text/javascript">

var donaturList ;
    $(document).ready(function(){
        get_donatur();
    })

    function get_donatur(){
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/get_debitur",
            type: "get",
            dataType: 'json',
            success:function(msg){
                
                if(msg.success){
                    var html = '';
                    $("#list-donatur").html('');
                    donaturList = msg.data;
                    $.each(msg.data, function(i,val){
                     html += '<li class="list-group-item d-flex justify-content-between align-items-start">'+
                            '<div class="ms-2 me-auto">'+
                                '<div class="fw-bold">'+val.nama+'</div>'+
                                '<div><small class="text-muted"><i>'+val.id+'</i></small></div>'+
                                '<small class="text-muted">'+val.alamat+'</small>'+
                            
                            '</div>'+
                            '<div class="">'+
                                '<div><small class="text-mutes">'+val.created_time+'</small></div> '+
                                '<a onClick="editDonatur(\''+ val.id + '\')"><small class="text-primary align-items-start">edit <i class="bi bi-pencil"></i></small></a>'+
                            '</div>'+
                        '</li>';
                    });

                    $("#list-donatur").html(html);
                }

            }

        })
    }

    function save_donatur(){
        let nama = $("#text-nama").val();
        let alamat = $("#text-alamat").val();

        if(!checkValidate()){ return false}
        $.ajax({
            url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_debitur",
            type: "post",
            data : {nama: nama , alamat : alamat},
            dataType: 'json',
            success:function(msg){
                if(msg.success){
                    showInfo(msg.message);
                    var html = '<li class="list-group-item d-flex justify-content-between align-items-start">'+
                            '<div class="ms-2 me-auto">'+
                                '<div class="fw-bold">'+msg.data.nama+'</div>'+
                                '<div><small class="text-muted"><i>'+msg.data.id+'</i></small></div>'+
                                '<small class="text-muted">'+msg.data.alamat+'</small>'+
                            
                            '</div>'+
                            '<div class="">'+
                                '<div><small class="text-mutes">'+msg.data.created_time+'</small></div> '+
                                '<a onClick="editDonatur(\''+ msg.data.id + '\')"><small class="text-primary align-items-start">edit <i class="bi bi-pencil"></i></small></a>'+
                            '</div>'+
                        '</li>';
                    $("#list-donatur").append(html);
                    
                    $("#text-nama").val('');
                    $("#text-alamat").val('');
                    $('#form_add_donatur').removeClass('was-validated');
                }else{
                    showWarning(msg.message);
                }
            }
        });
    }

    function batal_save_donatur(){
        $("#text-nama").val('');
        $("#text-alamat").val('');
        $('#form_add_donatur').removeClass('was-validated');
    }

    function editDonatur(idx){
        let donatur = donaturList.find((list)=>{return list.id==idx} );
        console.log('donatur',donatur);
    }


</script>