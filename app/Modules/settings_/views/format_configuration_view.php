<div class="row">
    <div class="card">

        <div class="card-body">
             <div class="row">
                <div class="col-sm-12 text-secondary" style='font-size:12px'>
                    <i>terakhir diperbarui : </i><i id="updated_time"><?=$updated_time?></i>
                    </div>
                </div>
             <div class="row">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Judul</span>
                        <input type="text" placeholder="..." id="judul" value="<?=$judul1?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                 
                </div>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">sub Judul</span>
                        <input type="text"  placeholder="..."id="subjudul" value="<?=$judul2?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Sebagai Kiri</span>
                        <input type="text" placeholder="..." id="sebagai1" value="<?=$sebagai1?>"class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                 
                </div>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Nama Kiri</span>
                        <input type="text" placeholder="..." id="nama1" value="<?=$nama1?>"class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Sebagai Kanan</span>
                        <input type="text" placeholder="..." id="sebagai2" value="<?=$sebagai2?>"class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                 
                </div>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Nama Kanan</span>
                        <input type="text" placeholder="..." id="nama2"value="<?=$nama2?>" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="button" id="btn-save-surat" class="btn btn-success" disabled>Save</button>
        </div>

    </div>
</div>
<script>
    $('input').keyup(function(){
        console.log('change...');
        $("#btn-save-surat").attr('disabled',false);
    })
   
   $("#btn-save-surat").click(function(){

        Swal.fire({
            title: "Apakah anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_format",
                    type: "post",
                    data:{
                        sebagai1:$("#sebagai1").val(),
                        sebagai2:$("#sebagai2").val(),
                        nama1:$("#nama1").val(),
                        nama2:$("#nama2").val(),
                        judul1:$("#judul").val(),
                        judul2:$("#subjudul").val()
                    },
                    success: function (msg) {
                    if(msg.success){
                            showInfo(msg.message);
                            $("#updated_time").html(msg.updated_time);
                    }
                    },
                    dataType: 'json',
                });
            }
        });
      
   })
</script>