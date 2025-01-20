<!-- <div class="row mb-3">
    <div class="form-group col-sm-12">
        <div class="mb-1">
            <i>*select the job list of the team you want</i>
        </div>
        <div class="d-flex align-items-center">
            <div id="div_opt" class="me-3">
                <?php
                    $attributes = 'class="form-control" id="team_handling"';
                    echo form_dropdown('team_handling', $list_team, "", $attributes);
                ?>
            </div>
            <button type="button" id="btnGetAccount" class="btn btn-success btn-sm me-3" disabled="disabled">GET
                ACCOUNT</button>
            <button type="button" id="btnStopGetAccount" class="btn btn-danger btn-sm me-3" style="display:none">STOP
                WAITING</button>
            <span class="me-2">dialing mode =</span>
            <span id="dialing_mode_desc"></span>
        </div>
    </div>
</div> -->


<div class="col-xs-12 mb-3">
    <div class="btn-group  btn-group-sm" role="group" aria-label="Basic outlined example">
        <button type="button" class="btn btn-outline-success" id="btn-export">Export</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridMa" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script src="<?= base_url(); ?>modules/my_account/js/my_account.js?v=<?= rand() ?>"></script>