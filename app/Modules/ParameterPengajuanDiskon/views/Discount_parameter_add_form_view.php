<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-diskon-parameter-name" class="fs-6 text-capitalize">Parameter Name</label>
                <input type="text" id="txt-diskon-parameter-name" name="txt-diskon-parameter-name"
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
                    <?php foreach ($hirarki as $a => $b) {
                        echo "<option value='".$a."'>".$b."</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3" id="form-group-desc-kondisi-khusus" style="display: none;">
                <label for="desc_kondisi_khusus" class="fs-6 text-capitalize">Desc Kondisi Khusus</label>
                <select class="form-control form-control-sm mandatory" name="desc_kondisi_khusus[]"
                    id="desc_kondisi_khusus" multiple>
                </select>
            </div>
            <div class="mb-3" id="form-group-max-kondisi-khusus" style="display: none;">
                <label for="txt-max-kondisi-khusus-discount-rate" class="fs-6 text-capitalize">MAX. Kondisi Khusus Disc
                    Rate (%) </label>
                <input type="text" id="txt-max-kondisi-khusus-discount-rate" name="txt-max-kondisi-khusus-discount-rate"
                    class="filterme form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3" id="form-group-max-kondisi-khusus-discount-rate" style="display: none;">
                <label for="txt-max-permanent-block-discount-rate" class="fs-6 text-capitalize">MAX. Permanent Block Disc
                    Rate (%) </label>
                <input type="text" id="txt-max-permanent-block-discount-rate" name="txt-max-permanent-block-discount-rate"
                    class="filterme form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3" id="form-group-max-nor-disc-rate" style="display: none;">
                <label for="txt-max-normal-discount-rate" class="fs-6 text-capitalize">MAX. Normal Disc Rate
                    (%)</label>
                <input type="text" id="txt-max-normal-discount-rate" name="txt-max-normal-discount-rate"
                    class="filterme form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3" id="form-group-max-nor-disc-princt-rate" style="display: none;">
                <label for="txt-max-normal-discount-principle-rate" class="fs-6 text-capitalize">MAX. Normal Disc
                    Principle Rate (%)</label>
                <input type="text" id="txt-max-normal-discount-principle-rate"
                    name="txt-max-normal-discount-principle-rate"
                    class="filterme form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3" id="form-group-max-nor-disc-int-rate" style="display: none;">
                <label for="txt-max-normal-discount-interest-rate" class="fs-6 text-capitalize">MAX. Normal Disc
                    Interest
                    Rate (%)</label>
                <input type="text" id="txt-max-normal-discount-interest-rate"
                    name="txt-max-normal-discount-interest-rate"
                    class="filterme form-control form-control-sm mandatory" />
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
    var kondisi_khusus_list_pl = '<?=$kondisi_khusus_list_pl?>';
    kondisi_khusus_pl = JSON.parse(kondisi_khusus_list_pl);
    var bucket_pl = '<?=$bucket_pl?>';
    var rules_new;
    var add_filter = daftar_discount_parameter;
</script>
<script src="<?= base_url(); ?>modules/parameter_pengajuan_diskon/js/script_add_form.js?v=<?= rand() ?>">
</script>