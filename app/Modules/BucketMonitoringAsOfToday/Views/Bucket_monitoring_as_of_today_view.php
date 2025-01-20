<div class="col-sm-6 mb-3">
    <div class="card">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="bucket_name" class="form-label">Filter by Bucket Name</label>
                    <?
                        $attributes = 'class="form-select" id="bucket_name"';
                        echo form_dropdown('bucket_name', $bucket_data, "", $attributes);
                    ?>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onClick="get_data_monitoring_bucket()"
                        id="btn-search">SEARCH <i class="bi bi-table"></i></button>
                    <!-- <button type="reset" class="btn btn-secondary" id="btn-reset">CLEAR <i
                            class="bi bi-x-circle"></i></button> -->
                </div>
            </form>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="col-xs-12 table-responsive" id="parent">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr class="text-center align-middle">
                        <th scope="col" rowspan="2">BucketId</th>
                        <th scope="col" rowspan="2">Bucket Name</th>
                        <th scope="col" rowspan="2">Product</th>
                        <th scope="col" rowspan="2" class="d-none">Total Agent</th>
                        <th scope="col" colspan="2">Call Activity</th>
                        <th scope="col" rowspan="2">Data Called</th>
                        <th scope="col" rowspan="2">Contact</th>
                        <th scope="col" rowspan="2">Not Contact</th>
                        <th scope="col" rowspan="2">Appointment</th>
                        <th scope="col" rowspan="2">PTP</th>
                        <th scope="col" rowspan="2">No PTP</th>
                        <th scope="col" rowspan="2">Special Case</th>
                        <th scope="col" rowspan="2">Other Status</th>
                        <th scope="col" rowspan="2">% Contact from Data Called</th>
                        <th scope="col" rowspan="2">% Not Contact from Data Called</th>
                        <th scope="col" rowspan="2">% PTP from Contact</th>
                        <th scope="col" rowspan="2">% PTP from Data Called</th>
                        <th scope="col" rowspan="2">% No Status from Data Called</th>
                    </tr>
                    <tr class="text-center align-middle">
                        <th scope="col">Average Talking</th>
                        <th scope="col">Average Acw</th>
                    </tr>
                </thead>
                <tbody id="content_bucket_monitoring">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    var classification = '<?= $classification ?>';
</script>
<script
    src="<?= base_url(); ?>modules/bucket_monitoring_as_of_today/js/bucket_monitoring_as_of_today.js?v=<?= rand() ?>">
</script>