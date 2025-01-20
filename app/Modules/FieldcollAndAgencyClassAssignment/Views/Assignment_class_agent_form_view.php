<form role="form" class="needs-validation" id="form_add" name="form_add" novalidate>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
    <div class="row">
        <div class="col col-sm-12">
            <div class="mb-3 ">
                <label for="opt-assigned-to-list" class="fs-6 text-capitalize">ASSIGNED TO</label>
                <?
                    $options=array(""=>'SELECT ASSIGNED TO',
                                "1"=>'TEAM',
                                "2"=>'AGENT',
                                //    "3"=>'AGENCY',
                                "4"=>'CLASS',
                                );
                    $attributes = 'id="opt-assigned-to-list" class="chosen-select form-control form-control-sm mandatory" ';
                    echo form_dropdown("opt-assigned-to-list", $options, "", $attributes);
                ?>
            </div>
            <div class="mb-3" id="showTeam">
                <label for="opt-team-list" class="fs-6 text-capitalize">TEAM LISTS</label>
                <div class="row">
                    <div class="col-8">
                        <?php
                            $attributes = 'id="opt-team-list" class="chosen-select form-control form-control-sm mandatory" ';
                            echo form_dropdown("opt-team-list", $team_list, "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" id="btn-team-add">Add Team</button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-warning btn-sm" id="btn-team-remove">Remove Team</button>
                    </div>
                </div>
            </div>
            <div class="mb-3" id="showFildColl">
                <label for="opt-collector-list" class="fs-6 text-capitalize">COLLECTOR LISTS</label>
                <div class="row">
                    <input type="hidden" id="class_id" value="<?= $class_id ?>" />
                    <div class="col-8">
                        <?php
                            $options=array(""=>'SELECT COLLECTOR');
                            $attributes = 'id="opt-collector-list" class="chosen-select form-control form-control-sm mandatory" ';
                            echo form_dropdown("opt-collector-list", $options, "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" id="btn-collector-assign">Add Collector</button>
                    </div>
                </div>
            </div>
            <div class="mb-3" id="showAgency">
                <label for="opt-agency-list" class="fs-6 text-capitalize">AGENCY LISTS</label>
                <div class="row">
                    <div class="col-8">
                        <?php
                            $attributes = 'id="opt-agency-list" class="chosen-select form-control form-control-sm mandatory" ';
                            echo form_dropdown("opt-agency-list", $agency_list, "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" id="btn-agency-assign-pt">Add Agency</button>
                    </div>
                </div>
            </div>
            <div class="mb-3" id="showAgencyCollector">
                <label for="agency_collector" class="fs-6 text-capitalize">AGENCY COLLECTOR</label>
                <div class="row">
                    <div class="col-8">
                        <?php
                            $attributes = 'id="agency_collector" class="chosen-select form-control form-control-sm mandatory" ';
                            echo form_dropdown("agency_collector", $agency_list, "", $attributes);
                        ?>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm" id="btn-agency-assign">Add Agency</button>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-none">
                <label for="assigned_agent_list" class="fs-6 text-capitalize">COLLECTOR ASSIGNED</label>
                <div class="row">
                    <div class="col-8">
                        <select name="assigned_agent_list" id="assigned_agent_list"
                            class="chosen-select form-control form-control-sm mandatory" multiple></select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-warning btn-sm" id="btn-collector-remove">Remove Collector</button>
                    </div>
                </div>
            </div>
            <div class="mb-3 table-responsive">
                <table id="table_selected_user" class="table table-hover table-bordered">
                    <thead>
                        <tr id="t_header">
                            <th>No</th>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>User Level</th>
                            <th style='display:none'>Assignment Weight</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="agent_table_body">
                    </tbody>
                </table>
            </div>
            <input type="hidden" id="txt_assigned_agent" value="<?=$assigned_agent['assigned_agent'] ?>"
                class="col-sm-12" />
            <div class="mb-3">
                <label for="opt-ac-agent" class="fs-6 text-capitalize"><b>DISTRIBUTOR</b></label>
                <?php
                     $options=array( 
                        // '' => 'SELECT DISTRIBUTION',
                                    'ROUND_ROBIN'=>'ROUND ROBIN',
                                    'REVERSE_ROUND_ROBIN'=>'REVERSED ROBIN'
                                );
                    $attributes = 'id="opt-ac-agent" class="form-control form-control-sm mandatory" ';
                    echo form_dropdown("opt-ac-agent", $options, @$assigned_agent['distribution_method'], $attributes);
                ?>
                <i id="LABEL_ROUND_ROBIN" style="display:none">*Sama rata jumlah Loan no.</i>
                <i id="LABEL_REVERSE_ROUND_ROBIN" style="display:none">*Sama rata jumlah OS Balance</i>
            </div>
            <div class="col-sm-4">
                <label for="txt-distribution-persen" style="display:none"><b>%Distribution</b></label>
                <div>
                    <input type="hidden" id="txt-distribution-persen" name="txt-distribution-persen" value="100" />
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    var parameter_value = "<?=$assigned_agent['assigned_agent'] ?>";
    var class_id = <?= $class_id ?>;
    var agent_list = $.parseJSON('<?=$assigned_agent_detail ?>');
    var count_agent = 0;
</script>
<script
    src="<?= base_url(); ?>modules/fieldcoll_and_agency_class_assignment/js/assignment_class_agent_form.js?v=<?= rand() ?>">
</script>