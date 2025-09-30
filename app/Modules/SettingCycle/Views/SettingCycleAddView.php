<form id="form_add_cycle" method="post" action="<?= site_url('settingcycle/save_cycle_add') ?>">
    <div class="modal-header">
        <h5 class="modal-title">Add Cycle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="txt-cycle-name" class="form-label">Cycle</label>
            <input type="number" class="form-control" id="txt-cycle-name"
                name="txt-cycle-name" placeholder="max 3 digit" max="999" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="txt-cycle-from" class="form-label">From (days)</label>
                <input type="number" class="form-control" id="txt-cycle-from"
                    name="txt-cycle-from" placeholder="max 2 digit" max="99" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="txt-cycle-to" class="form-label">To (days)</label>
                <input type="number" class="form-control" id="txt-cycle-to"
                    name="txt-cycle-to" placeholder="max 2 digit" max="99" required>
            </div>
            <div class="form-text text-danger" id="error-range" style="display:none;">
                "From" cannot be greater than "To"
            </div>
        </div>

        <div class="mb-3">
            <label for="opt-active-flag" class="form-label">Active Status</label>
            <?php
            $attributes = [
                'class'    => 'form-select',
                'id'       => 'opt-active-flag',
                'required' => true
            ];
            echo form_dropdown('opt-active-flag', $active_status, '', $attributes);
            ?>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>

<script type="text/javascript">
    jQuery(function($) {
        // Validasi Cycle (max 3 digit dan <= 999)
        $("#txt-cycle-name").on("input", function() {
            let val = $(this).val();
            if (!/^\d{0,3}$/.test(val) || parseInt(val) > 999) {
                $(this).val(val.slice(0, 3).replace(/[^\d]/g, '').substring(0, 3));
            }
        });

        // Validasi From & To (max 2 digit dan <= 99)
        $("#txt-cycle-from, #txt-cycle-to").on("input", function() {
            let val = $(this).val();
            if (!/^\d{0,2}$/.test(val) || parseInt(val) > 99) {
                $(this).val(val.slice(0, 2).replace(/[^\d]/g, '').substring(0, 2));
            }

            // Cek From <= To
            let from = parseInt($("#txt-cycle-from").val()) || 0;
            let to = parseInt($("#txt-cycle-to").val()) || 0;

            if (from > to) {
                $("#txt-cycle-from, #txt-cycle-to").addClass("is-invalid");
                $("#error-range").show();
            } else {
                $("#txt-cycle-from, #txt-cycle-to").removeClass("is-invalid");
                $("#error-range").hide();
            }
        });

        // Prevent submit kalau from > to
        $("#form_add_cycle").on("submit", function(e) {
            let from = parseInt($("#txt-cycle-from").val()) || 0;
            let to = parseInt($("#txt-cycle-to").val()) || 0;

            if (from > to) {
                e.preventDefault();
                showInfo('"From" tidak boleh lebih besar dari "To"');
            }
        });
    });
</script>