<style>
.hide {
    display: none !important;
}
</style>
<div class="card">

	<div class="card-header">
        Checklist Asset
	</div>

	<div class="card-body">
		<div class="col-xs-5" id="parent">
        <div class="col-lg-6">
                <div class="mb-3 ">
                    <label for="form-field-select-2" class="fs-6 text-capitalize">PILIH ASSET</label>
                    <?php
                        $attributes = 'class="form-control form-control-sm" id="opt-asset" ';
                        echo form_dropdown('opt-asset', $asset_type,'', $attributes);
                    ?>
                </div>
            </div>
            <?
				foreach( $asset_type as $asset=>$b){
			?>
            <form role="form" class="needs-validation hide" name="formAdd-<?=$asset;?>" id="formAdd-<?=$asset;?>" novalidate>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <input type="hidden" value="<?=$asset;?>" name="asset_type">
                <header class="main-box-header clearfix">
					
						<div class="col-lg-4 filter-block">							
							<a class="btn btn-primary pull-left" onClick="addFieldAsset('<?=$asset;?>')" id="btnAddField-<?=$asset;?>">
								<i class="fa fa-plus-circle fa-lg"></i> Add Field
							</a>
						</div>
					</header>
					<br>
				<div id="loadingPage"></div>
				<div id="matrix">
                <?php
					if(!empty($matrix[$asset])){
						foreach($matrix[$asset] as $a => $b){
					
							echo '<div class="form-group">'.
                                '<label for="txtCode" class="col-lg-2 control-label">PERTANYAAN</label>'.
                                '<div class="row">'.
                                    '<div class="col-lg-5">'.
                                        '<input type="text" class="form-control mandatory" name="txtFieldName['.$a.']" value="'.$b['field_name'].'" id="txtFieldName" placeholder="Field name" data-required="true" required>'.
                                    '</div>'.
                                    '<div class="col-lg-1">'.
                                        '<input type="text" class="form-control mandatory" name="txtFieldOrder['.$a.']" value="'.$b['order_by'].'" id="txtFieldOrder" placeholder="Sort" required>'.
                                    '</div>'.
                                    '<div class="col-lg-1">'.
                                        '<div id="addAttr_'.$b['field_name_id'].'"></div>'.
                                        "<a class='btn btn-danger btn-sm pull-left' id='remove-".$a."' href='#' onClick='remove_field(".$a.")' id='remove_field'><i class=''>X</i></a>".
                                    '</div>'.
                                '</div>'.
                            '</div>';
						}
					}					
				?>
                    <div id="addField-<?=$asset?>"></div>
                </div>
                <div id="divSubmit" class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="button" onClick="form_add_submit('<?=$asset;?>')" id="save_<?=$asset?>" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
            <?
                }
            ?>
		</div>
	</div>
</div>
<div class="row"></div><div class="row"></div>

<script type="text/javascript">
    var inc = 0;
    var selectedAsset = '';
    $('#opt-asset').change(function() {
        selectedAsset = $(this).val(); 
        var questionCount = $('#formAdd-' + selectedAsset + ' [name^="txtFieldName"]').length; // Menghitung jumlah elemen dengan atribut name yang dimulai dengan "txtFieldName"
        console.log("Jumlah pertanyaan untuk " + selectedAsset + ": " + questionCount);
        inc = questionCount;
    });
	var classification = '<?= $classification ?>';

</script>
<script src="<?= base_url(); ?>modules/checklist_asset/js/checklist_asset.js?v=<?= rand() ?>"></script>