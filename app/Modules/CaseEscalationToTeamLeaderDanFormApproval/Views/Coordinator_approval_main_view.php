<div class="row">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <?php 
                $j = 1;
                foreach($result as $row) { 
                    $activeClass = ($j == 1) ? 'active show' : ''; 
            ?>
        <div class="tab-pane fade <?= $activeClass; ?>" id="<?= $row['collection_result']; ?>" role="tabpanel"
            aria-labelledby="<?= $row['collection_result']; ?>-tab">
            <div id="<?= $row['collection_result']; ?>Cet" style="height: 450px;" class="ag-theme-alpine"></div>
        </div>
        <?php 
                $j++;
            } 
        ?>
    </div>
</div>

<script type="text/javascript">
    var classification = '<?= $classification ?>';
    var dataId = <?= json_encode($result); ?>;
</script>
<script
    src="<?= base_url(); ?>modules/case_escalation_to_team_leader_dan_form_approval/js/case_escalation_to_team_leader_dan_form_approval.js?v=<?= rand() ?>">
</script>