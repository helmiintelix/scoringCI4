<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
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
                    class="form-control form-control-sm mandatory" required />
            </div>
            <div class="mb-3 ">
                <label for="description-event" class="fs-6 text-capitalize">Description</label>
                <textarea class="form-control form-control-sm mandatory" name="description-event" id="description-event"
                    cols="30" rows="10" required></textarea>
            </div>
            <div class="mb-3 ">
                <label for="txt-tanggal-event" class="fs-6 text-capitalize">Event Date</label>
                <input type="text" id="txt-tanggal-event" name="txt-tanggal-event"
                    class="form-control form-control-sm mandatory" />
            </div>
            <div class="mb-3 ">
                <label for="txt-location-event" class="fs-6 text-capitalize">Location</label>
                <input type="text" id="txt-location-event" name="txt-location-event"
                    class="form-control form-control-sm mandatory" required />
            </div>
        </div>
    </div>

</form>
<script type="text/javascript">
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
        alwaysShowCalendars: true // Menampilkan kalender meskipun hanya ada satu tanggal yang dipilih
    });
</script>