<div class="row">
    <div class="col-xs-12">
		<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
			<button type="button" class="btn btn-outline-primary" id="btn-add-pengeluaran">Tambah</button>
		</div>
	</div>
    <div class="col-xs-12">
        <table class="table table-striped table-hover">
            <thead>
                <th>#</th>
                <th>Label</th>
                <th>dibuat oleh</th>
                <th>waktu pembuatan</th>
                <th>action</th>
            </thead>
            <tbody id="list_pengeluaran">
            <?php
                $i=1;
                foreach ($list_pengeluaran as $key => $value) {
                  echo "<tr id='".$value['id']."'>";
                  echo "<td>".$i."</td>";
                  echo "<td>".$value['label']."</td>";
                  echo "<td>".$value['created_by']."</td>";
                  echo "<td>".$value['created_time']."</td>";
                  echo "<td>";
                    echo '<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">'.
                        '<button type="button" class="btn btn-outline-success" onClick="editpengeluaran(\''.$value['id'].'\','.$i.')" id="btn-edit">Edit</button>'.
                        '<button type="button" class="btn btn-outline-danger" onClick="editDelete(\''.$value['id'].'\',\''.$value['label'].'\')"id="btn-del">Delete</button>'.
                    '</div>';
                    echo "</td>";
                  echo "</tr>";
                  $i++;
                }
              
            ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var no = <?=$i?>;
    $("#btn-add-pengeluaran").click(()=>{
        var buttons = {
            "success":
            {

                "label": "<i class='icon-ok'></i> Save",
                "className": "btn-sm btn-success",
                "callback": function () {
                var options = {
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_master_pengeluaran",
                    type: "post",
                    // beforeSubmit: jqformValidate,
                    success: function(msg){
                        if(msg.success){
                            showInfo(msg.message);
                            
                            $.each(msg.data,function(i,val){
                                let html = '';
                                html+= "<tr class='table-success' id='"+val['id']+"' >";
                                html+= "<td>"+no+"</td>";
                                html+= "<td>"+val['label']+"</td>";
                                html+= "<td>"+val['created_by']+"</td>";
                                html+= "<td>"+val['created_time']+"</td>";
                                html+= "<td>";
                                html+= '<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">';
                                html+= '<button type="button" class="btn btn-outline-success" onClick="editpengeluaran(\''+val['id']+'\',\''+no+'\')" id="btn-edit">Edit</button>';
                                html+= '<button type="button" class="btn btn-outline-danger" onClick="editDelete(\''+val['id']+'\',\''+val['label']+'\')"id="btn-del">Delete</button>';
                                html+='</div>';
                                html+= "</td>";
                                html+= "</tr>";
                                console.log('html', html);
                                $("#list_pengeluaran").append(html);
                                no++;

                                window.scrollTo(0, document.body.scrollHeight);

                                setTimeout(() => {
                                    $("#"+val['id']).removeClass('table-success');
                                }, 5000);
                            })
                        }else{
                            showWarning(msg.message);
                        }

                    },
                    dataType: 'json',
                };

                $('form').ajaxSubmit(options);
                }
            },
            "button":
            {
                "label": "Close",
                "className": "btn-sm"
            }
        }

            showCommonDialog(400, 400, 'TAMBAH MASTER PENGELUARAN', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/add_master_pengeluaran_form', buttons);
    });

    function editpengeluaran (id,nourut){
        
        var buttons = {
            "success":
            {

                "label": "<i class='icon-ok'></i> Save",
                "className": "btn-sm btn-success",
                "callback": function () {
                    var options = {
                        url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/save_edit_master_pengeluaran",
                        type: "post",
                        // beforeSubmit: jqformValidate,
                        success: function(msg){
                            if(msg.success){
                                showInfo(msg.message);
                            
                                $.each(msg.data,function(i,val){
                                  
                                    let html = '';
                                    html+= "<td>"+nourut+"</td>";
                                    html+= "<td>"+val['label']+"</td>";
                                    html+= "<td>"+val['created_by']+"</td>";
                                    html+= "<td>"+val['created_time']+"</td>";
                                    html+= "<td>";
                                    html+= '<div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">';
                                    html+= '<button type="button" class="btn btn-outline-success" onClick="editpengeluaran(\''+val['id']+'\','+nourut+')" id="btn-edit">Edit</button>';
                                    html+= '<button type="button" class="btn btn-outline-danger" onClick="editDelete(\''+val['id']+'\',\''+val['label']+'\')"id="btn-del">Delete</button>';
                                    html+='</div>';
                                    html+= "</td>";
                                    console.log('html', html);
                                    $("#"+val['id']).html(html).addClass('table-success');
                                    no++;

                                  

                                    setTimeout(() => {
                                        $("#"+val['id']).removeClass('table-success');
                                    }, 5000);
                                })
                            }else{
                                showWarning(msg.message);
                            }
                        },
                        dataType: 'json',
                    };

                    $('form').ajaxSubmit(options);
                    }
            },
            "button":
            {
                "label": "Close",
                "className": "btn-sm"
            }
        }

        showCommonDialog(400, 400, 'EDIT MASTER PENGELUARAN', GLOBAL_MAIN_VARS["SITE_URL"] + 'settings/edit_master_pengeluaran_form?id='+id, buttons);
        
    }

    function editDelete(id, label){
        Swal.fire({
            title: "Apakah yakin akan menghapus <strong>"+label+"</strong> ",
            showDenyButton: true,
            showCancelButton: true,
            denyButtonText: `delete`
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "success");
            }
        });

        Swal.fire({
            title: "Apakah yakin?",
            text: "menghapus data "+label+" ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
            }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: GLOBAL_MAIN_VARS["SITE_URL"] + "settings/delete_data_pengeluaran",
                    dataType: "json",
                    type: "POST",
                    data :{id:id},
                    async: false,
                    success: function (msg) {
                        if(msg.success){
                            $("#"+id).remove();

                            Swal.fire({
                                title: "Deleted!",
                                text: msg.message,
                                icon: "success"
                            });
                        }else{
                            showWarning(msg.message);
                        }
                        
                    }
                });

               
            }
        });
    }

    
</script>