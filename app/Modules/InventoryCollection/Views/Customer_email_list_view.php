<div class="row">
    <div class="col-xs-12" id="parent">
        <table class="table table-striped table table-bordered">
            <thead>
                <th>No</th>
                <th>Contact Type</th>
                <th>Mail Address</th>
            </thead>
            <tbody>
                <?php foreach($address as $key => $value){?>
                <tr>
                    <td><?= $key+1;?></td>
                    <td><?= $value["contact_type"];?></td>
                    <td><?= $value["mailphone"];?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>

        <div id="customer-address-list-grid-pager"></div>
    </div>
</div>