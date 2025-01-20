<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <input type="hidden" name="txt-id" value="<?= $data['id']; ?>">
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="txt-nama-balai" class="fs-6 text-capitalize">Auction House Name</label>
                <?php
                    $attributes = 'class="form-control form-control-sm mandatory" id="txt-nama-balai" data-placeholder ="[select]" required';
                    echo form_dropdown('opt-balai-lelang', $master,  $data['balai_id'], $attributes);
				?>
            </div>
            <div class="mb-3 ">
                <label for="txt-nama-event" class="fs-6 text-capitalize">Event Name</label>
                <input type="text" id="txt-nama-event" name="txt-nama-event"
                    class="form-control form-control-sm mandatory" required value="<?= $data['name']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="description-event" class="fs-6 text-capitalize">Description</label>
                <textarea class="form-control form-control-sm mandatory" name="description-event" id="description-event"
                    cols="30" rows="10" required><?= $data['description']; ?></textarea>
            </div>
            <div class="mb-3 ">
                <label for="txt-tanggal-event" class="fs-6 text-capitalize">Event Date</label>
                <input type="text" id="txt-tanggal-event" name="txt-tanggal-event"
                    class="form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3 ">
                <label for="txt-location-event" class="fs-6 text-capitalize">Location</label>
                <input type="text" id="txt-location-event" name="txt-location-event"
                    class="form-control form-control-sm mandatory" required value="<?= $data['location']; ?>" />
            </div>
            <div class="mb-3 ">
                <label for="flexSwitchCheckChecked" class="fs-6 text-capitalize">Status</label>
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Active</label>
                    <input onChange="isActive(this)" class="form-check-input" type="checkbox" role="switch"
                        id="flexSwitchCheckChecked" <?= $is_active == '1' ? 'checked' : ''; ?>>
                </div>
            </div>
            <div class="mb-3 " style="display:none">
                <label for="opt-active-flag" class="fs-6 text-capitalize">Is Active</label>
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
    var eventDate = 'value="<?= $data['event_date']; ?>'
    $('#txt-tanggal-event').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'), 10),
        locale: {
            format: 'YYYY-MM-DD', // Ubah format tanggal
            cancelLabel: 'Close', // Mengganti label "Cancel" menjadi "Close"
            applyLabel: 'OK', // Mengganti label "Apply" menjadi "OK"
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'], // Nama hari dalam bahasa Inggris
            monthNames: [
                'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ] // Nama bulan dalam bahasa Inggris
        },
        showCustomRangeLabel: false, // Menyembunyikan label rentang kustom
        autoApply: true, // Menjalankan aksi secara otomatis ketika memilih tanggal
        alwaysShowCalendars: true, // Menampilkan kalender meskipun hanya ada satu tanggal yang dipilih
        startDate: eventDate
    });

    function isActive(elm) {
        if ($(elm)[0].checked) {
            $("#opt-active-flag").val('1').change();
            //$("label[for='flexSwitchCheckChecked']").text('Active');
        } else {
            $("#opt-active-flag").val('0').change();
            //$("label[for='flexSwitchCheckChecked']").text('Not Active');
        }
    }
</script>