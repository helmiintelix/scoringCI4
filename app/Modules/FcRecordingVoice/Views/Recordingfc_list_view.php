<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <audio controls="controls" id="player_recording">
                <source src="#" id="source_recording" type="audio/wav" />
                Your browser does not support the audio element.
            </audio>
        </div>
        <div class="panel-body" id="panel-body-recording">

        </div>
        <a id="recording_download" href="#" download="file" style="display:none">download </a>
    </div>

    <div class="card-body">
        <div class="col-xs-12" id="parent">
            <div id="myGridFrv" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
    let ecx8_url = '<?=getenv('ECX8_URL');?>'
</script>
<script src="<?= base_url(); ?>modules/fc_recording_voice/js/fc_recording_voice.js?v=<?= rand() ?>">
</script>