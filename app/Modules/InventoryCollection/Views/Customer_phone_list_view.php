<div class="row">
    <div class="col-xs-12" id="parent">
        <table class="table table-striped table table-bordered">
            <thead>
                <th>No</th>
                <th>Contact Type</th>
                <th>Phone Number</th>
                <th>Priority</th>
                <th>Best Time</th>
            </thead>
            <tbody>
                <?php foreach($address as $key => $value){?>
                <tr>
                    <td><?= $key+1;?></td>
                    <td><?= $value["type"];?></td>
                    <td><?= $value["phone"];?></td>
                    <td><?= @$value["priority"];?></td>
                    <td><?= @$value["best_time"];?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>