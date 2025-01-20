<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-restructure-parameter-name" class="fs-6 text-capitalize">Parameter Name</label>
                <input type="text" id="txt-restructure-parameter-name" name="txt-restructure-parameter-name"
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="query_builder" class="fs-6 text-capitalize">Parameter Detail</label>
                <div class="col-sm-12" id='query_builder'></div>
            </div>
            <div class="mb-3 ">
                <label for="txt-discount-Bucket" class="fs-6 text-capitalize">Bucket</label>
                <select class="form-control form-control-sm mandatory" id="bucket_id" name="bucket_id[]" multiple>
                </select>
            </div>
            <div class="mb-3 ">
                <label for="txt-discount-flag-hirarki" class="fs-6 text-capitalize">Flag Hirarki</label>
                <select class="form-control form-control-sm mandatory" name="hirarki_flag" id="hirarki_flag">
                    <?php foreach ($flag_hirarki as $a => $b) {
                        echo "<option value='".$a."'>".$b."</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3" id="form-group-desc-kondisi-khusus" style="display: none;">
                <label for="desc_kondisi_khusus" class="fs-6 text-capitalize">Desc Khondisi Khusus</label>
                <select class="form-control form-control-sm mandatory" name="desc_kondisi_khusus[]"
                    id="desc_kondisi_khusus" multiple>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipe_pengajuan" class="fs-6 text-capitalize">Type Pengajuan</label>
                <select class="form-control form-control-sm mandatory" name="tipe_pengajuan" id="tipe_pengajuan">
                    <option value='RSTR'>RESTRUCTURE</option>
                    <option value='RSCH'>RESCHEDULE</option>
                </select>
            </div>
            <div class="mb-3 ">
                <label for="txt-max-disc-rate" class="fs-6 text-capitalize">MAX. Disc Rate (%)</label>
                <input type="text" id="txt-max-disc-rate" name="txt-max-disc-rate"
                    class="filterme form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-max-interest-rate" class="fs-6 text-capitalize">MAX. Interest Rate (%)</label>
                <input type="text" id="txt-max-interest-rate" name="txt-max-interest-rate"
                    class="filterme form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-max-tenor" class="fs-6 text-capitalize">MAX. Tenor</label>
                <input type="text" id="txt-max-tenor" name="txt-max-tenor"
                    class="filterme form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="txt-ratio-cicilan" class="fs-6 text-capitalize">Ratio Cicilan (%)</label>
                <input type="text" id="txt-ratio-cicilan" name="txt-ratio-cicilan"
                    class="filterme form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" checked>
                </div>
            </div>

            <div class="mb-3 " style="display:none">
                <label for="form-field-select-2" class="fs-6 text-capitalize">Is Active</label>
                <?php
				$options = array(
					'1' => 'ENABLE',
					'0'	=> 'DISABLE',
				);
				$attributes = 'class="form-control form-control-sm  mandatory" id="opt-active-flag" data-placeholder ="[select]" required';
				echo form_dropdown('opt-active-flag', $options, '1', $attributes);
				?>
            </div>

        </div>
    </div>

</form>

<script type="text/javascript">
    var kondisi_khusus_pl_rsch = '<?=$kondisi_khusus_list_pl_rsch?>';
    var kondisi_khusus_pl_rstr = '<?=$kondisi_khusus_list_pl_rstr?>';
    var bucket_pl = '<?=$bucket_pl?>';
    var rules_new;
    var add_filter = daftar_restructure_parameter;
</script>
<script src="<?= base_url(); ?>modules/parameter_pengajuan_restructure/js/script_add_form.js?v=<?= rand() ?>">
</script>