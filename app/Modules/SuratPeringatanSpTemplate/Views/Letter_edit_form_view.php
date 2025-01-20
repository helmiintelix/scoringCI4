<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-letter-id" class="fs-6 text-capitalize">ID SP</label>
                <input type="text" id="txt-letter-id" name="txt-letter-id"
                    class="form-control form-control-sm mandatory" required value="<?= $id; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="txt-letter-info" class="fs-6 text-capitalize">Nama SP</label>
                <input type="text" id="txt-letter-info" name="txt-letter-info"
                    class="form-control form-control-sm mandatory" required value="<?= $data['info']; ?>" />
            </div>
            <div class="mb-3" id="rules_ptp">
                <label for="opt-template-product-code" class="fs-6 text-capitalize">Product</label>
                <?php
				$attributes = 'class="chosen-select form-control form-control-sm mandatory" id="opt-template-product-code" data-placeholder="-Please Select Product-"';
				echo form_dropdown('opt-template-product-code[]', $product, $data['product'], $attributes);
				?>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-6">
                        <label for="txt-letter-dpd-from" class="fs-6 text-capitalize"> DPD FROM</label>
                        <input type="text" id="txt-letter-dpd-from" name="txt-letter-dpd-from"
                            class="form-control form-control-sm mandatory" value="<?= $data['dpd_from']; ?>" />
                    </div>
                    <div class="col-6">
                        <label for="txt-letter-dpd-to" class="fs-6 text-capitalize"> TO</label>
                        <input type="text" id="txt-letter-dpd-to" name="txt-letter-dpd-to"
                            class="form-control form-control-sm mandatory" value="<?= $data['dpd_to']; ?>" />
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-9">
                        <label for="opt_field_list" class="fs-6 text-capitalize">List Field</label>
                        <select class="form-control form-control-sm mandatory" name="opt_field_list"
                            id="opt_field_list">
                            <option value="[[CM_CARD_NMBR]]">No Pinjaman </option>
                            <option value="[[CM_CUSTOMER_NMBR]]">No CIF</option>
                            <option value="[[date]]">Tanggal Hari ini </option>
                            <option value="[[tanggal_bayar]]">Tanggal Bayar </option>
                            <!-- <option value="[[no_surat]]">No Surat </option> -->
                            <option value="[[angsuran_bulanan]]">Angsuran Bulanan</option>
                            <!-- CM_INSTALLMENT_AMOUNT -->
                            <option value="[[dpd]]">DPD</option>
                            <!-- <option value="[[jenis_usaha]]">Jenis Usaha</option> -->
                            <option value="[[CM_COLLECTIBILITY]]">Kolektibilitas</option> <!-- CM_COLLECTIBILITY -->
                            <option value="[[due_date]]">Tanggal Jatuh Tempo</option> <!-- CM_DTE_PYMT_DUE -->
                            <option value="[[pay_amount]]">Bayar Terakhir </option> <!-- CM_DTE_LST_PYMT -->
                            <option value="[[angsuran_ke]]">Angsuran Ke</option> <!-- CM_INSTALLMENT_NO -->
                            <option value="[[CR_NAME_1]]">Nama Debitur</option> <!-- CM_NAME_1 -->
                            <option value="[[home_phone]]">Telepon</option>
                            <!-- relasi ke table cpcrd_ext contacttype ambil yg curcellph -->
                            <option value="[[mobile_phone]]">Handphone</option>
                            <!-- relasi ke table cpcrd_ext contacttype ambil yg cellph -->
                            <option value="[[company_name]]">Nama Perusahaan</option> <!-- cr_employer -->
                            <option value="[[office_phone]]">Telepon Kantor</option>
                            <!-- relasi ke table cpcrd_ext contacttype ambil yg workph -->
                            <!--<option value="[[cust_address_1]]">Alamat Rumah</option> -->
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan addrline1 -->
                            <!--<option value="[[cust_address_2]]">Alamat Rumah line 2</option> -->
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan addrline2 -->
                            <!--<option value="[[cust_address_3]]">Alamat Rumah line 3</option>-->
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan addrline3-->
                            <!--<option value="[[kecamatan]]">Kecamatan</option>-->
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan district (nambah) -->
                            <!--<option value="[[kelurahan]]">Kelurahan</option>-->
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan subdistrict (nambah) -->
                            <option value="[[zipcode]]">Zipcode</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype home dan postalcode (nambah) -->
                            <option value="[[CM_CURR_ADDR]]">Alamat Current</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype Current dan addrline1 (nambah) -->
                            <option value="[[CM_CURR_PROVINCE]]">Alamat Current Provinsi</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype Current dan addrline2 (nambah) -->
                            <option value="[[CM_CURR_CITY]]">Alamat Current Kota</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype Current dan addrline3 (nambah) -->
                            <option value="[[CM_CURR_DISTRICT]]">Kecamatan Current</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype Current dan district (nambah) -->
                            <option value="[[CM_CURR_SUBDIST]]">Kelurahan Current</option>
                            <!-- relasi ke table cpcrd_ext ambil yg addrtype Current dan subdistrict (nambah) -->
                            <!--<option value="[[city_current]]">Kota Current</option> relasi ke table cpcrd_ext ambil yg addrtype Current dan city (nambah) -->
                            <option value="[[total_os]]">Total OS</option> <!-- CM_OS_BALANCE -->
                            <option value="[[terbilang_total_os]]">Terbilang Total OS</option>
                            <option value="[[nama_cabang]]">Nama Cabang</option>
                            <!-- CM_DOMISIL_BRANCH relasi ke table CMS_BRANCH field branchid -->
                            <!-- <option value="[[no_rek_pendebetan]]">NO Rekening Pendebetan</option> -->
                            <option value="[[no_pk]]">NO PK</option>
                            <option value="[[tgl_pk]]">TGL PK</option> <!-- CM_DTE_PK -->
                            <option value="[[no_sp1]]">NO SP I</option>
                            <option value="[[tgl_sp1]]">TGL SP I</option>
                            <option value="[[jumlah_pencairan]]">Jumlah Pencairan</option> <!-- CR_LIMIT -->
                            <option value="[[tgl_pencairan]]">TGL Pencairan</option> <!-- CM_DTE_LIQUI_DATE -->
                            <option value="[[tgl_akhir]]">TGL Akhir</option> <!-- CM_CARD_EXPIR_DTE -->
                            <option value="[[jangka_waktu_pinjaman]]">Jangka Waktu Pinjaman</option> <!-- CM_TENOR -->
                            <option value="[[total_outstanding_ar]]">Total OUTSTANDING AR</option>
                            <!-- CM_TOTAL_OS_AR -->
                            <!-- <option value="[[table_ringkasan_fasilitas]]">Table Ringkasan Fasilitas</option> -->
                            <!-- <option value="[[table_ringkasan_jaminan]]">Table Ringkasan Jaminan</option> -->
                        </select>
                    </div>
                    <div class="col-3 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-xs btn-outline-danger" id="btnAddField"
                            onclick="addField();return false;"><i class="bi bi-journals"></i> Add Field</button>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <textarea maxlength="50" class="form-control form-control-sm mandatory" name="txt-letter-content"
                    id="txt-letter-content" style="height: 300px;"><?= $data['content']; ?></textarea>
            </div>
            <input type="hidden" id="content" name="content">

        </div>
    </div>

</form>
<script src="<?= base_url(); ?>modules/surat_peringatan_sp_template/js/script_edit_form.js?v=<?= rand() ?>"></script>