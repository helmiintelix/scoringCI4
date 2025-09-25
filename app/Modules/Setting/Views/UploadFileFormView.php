<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Upload File Form</h5>
    </div>
    <div class="card-body">
        <?= form_open_multipart('#', ['id' => 'frm_upload', 'name' => 'frm_upload']); ?>
        <input type="hidden" id="txt-letter-id" name="txt-letter-id">

        <!-- Upload File -->
        <div class="mb-3 row">
            <label for="userfile" class="col-sm-3 col-form-label text-end">Upload File</label>
            <div class="col-sm-6">
                <input type="file" name="userfile" id="userfile" class="form-control" />
            </div>
        </div>

        <!-- LOB -->
        <div class="mb-3 row">
            <label for="opt_lob" class="col-sm-3 col-form-label text-end">LOB</label>
            <div class="col-sm-6">
                <?php
                $attributes = 'class="form-select" id="opt_lob"';
                echo form_dropdown('opt_lob', $LOB_CODE, '', $attributes);
                ?>
            </div>
        </div>

        <!-- Bucket -->
        <div class="mb-3 row">
            <label for="opt_bucket" class="col-sm-3 col-form-label text-end">Bucket</label>
            <div class="col-sm-6">
                <?php
                $attributes = 'class="form-select" id="opt_bucket"';
                echo form_dropdown('opt_bucket', $BUCKET_SC, '', $attributes);
                ?>
            </div>
        </div>

        <!-- Is Active -->
        <div class="mb-3 row">
            <label for="is_active" class="col-sm-3 col-form-label text-end">Is Active</label>
            <div class="col-sm-6">
                <?php
                $is_active_list = ['Y' => 'Yes', 'N' => 'No'];
                $attributes = 'class="form-select" id="is_active"';
                echo form_dropdown('is_active', $is_active_list, '', $attributes);
                ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-success">
                    Upload
                </button>
                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>