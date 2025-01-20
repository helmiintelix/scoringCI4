<div class="row">
    <div class="col col-sm-12">
    <form role="form" class="needs-validation" id="form_edit_user" name="form_edit_user" novalidate>
        <div class="input-group input-group-default mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">KATEGORI <?=$tipe?></span>
                <select name="master_id" id="master_id" class="form-select" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" data-placeholder ="[Pilih data]" required>
                    <option value=''>[Pilih Data]</option>
                    <?php
                        foreach ($master as $key => $value) {
                            echo "<option value='".$value['id']."'>".$value['label']."</option>";
                        }
                    ?>
                </select>
        </div>
        <div class="input-group mb-3">
             <span class="input-group-text">Rp</span>
             <input type="text" placeholder="input nominal..." name="nominal" class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="nominal" required>
        </div>
        <div class="input-group mb-3">
             <span class="input-group-text"><i class="bi bi-calendar2-event"></i></span>
             <input type="hidden" name="date_format" class="form-control datepicker" id="date_format" value="<?=date('Y-m-d')?>">
             <input type="text" name="date" class="form-control datepicker" id="date" required>
        </div>
        <div class="input-group">
            <span class="input-group-text">Keterangan</span>
            <textarea class="form-control" placeholder="input keterangan..." name="keterangan" id="keterangan" aria-label="Keterangan"></textarea>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $("#nominal").keyup(()=>{
        let nominal = $("#nominal").val();
        nominal = nominal.replaceAll(',','');
        let sp = new Intl.NumberFormat('en-US').format(nominal); 
        $("#nominal").val(sp);
    })

    $(document).ready(function(){
        $(".btn-save-transaksi").hide();
        let html = '<button type="button" onClick="cek()" class="btn btn-sm btn-success btn-save-alert"><i class="icon-ok"></i> Save</button>';
        $("#modal-footer").append(html);
        $('#date').daterangepicker({
            "singleDatePicker": true,
            "startDate": new Date(),
            "locale": {
                "format": 'DD-MMM-YYYY',
                "daysOfWeek": [
                    "minggu",
                    "Senin",
                    "Selasa",
                    "Rabu",
                    "Kamis",
                    "Jumat",
                    "Sabtu"
                ],
            },
        
            "autoApply": true,
        }, function(start, end, label) {
        // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            $("#date_format").val(start.format('YYYY-MM-DD'));
        });
    })

  function cek(){
    if(!checkValidate()){
        return false;
    }

    let master_id= '';
    if($("#master_id").val()!=''){
        master_id = $("#master_id option:selected").text();
    }
    
    let txt = "Tipe " + tipe + " : " + master_id+ " | Nominal : Rp " + $("#nominal").val() + ' | keterangan : ' + $("#keterangan").val();
    Swal.fire({
        title: "Apakah anda yakin?",
        text: txt,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $(".btn-save-transaksi").click();
        }
    });
  }
</script>