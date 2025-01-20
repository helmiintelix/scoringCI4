<div class="row">
    <div class="col-sm-12 table-responsive">
        <table class="table zebra-table">
            <tr>
                <th>Cicilan</th>
                <th>Pokok Pinjaman</th>
                <th>Pokok Angsuran</th>
                <th>Bunga</th>

                <?php 
                    if(isset($value['moratorium'])){
                        echo "<th>Moratorium</th>";
                    } 
                ?>

                <th>Angsuran</th>
                <th>saldo</th>
            </tr>
            <tr>
                <?php
                    foreach ($payment_plan as $key => $value) {
                        echo "<tr>";
                        echo "<td>".number_format(ceil($value['installment_no']))."</td>";
                        echo "<td>".number_format(ceil($value['principle']))."</td>";
                        echo "<td>".number_format(ceil($value['installment_principle']))."</td>";
                        echo "<td>".number_format(ceil($value['interest']))."</td>";
                        if(!isset($value['moratorium'])){

                        }else{
                            echo "<td>".@number_format(@ceil(@$value['moratorium']))."</td>";

                        }
                        echo "<td>".number_format(ceil($value['installment_amount']))."</td>";
                        echo "<td>".number_format(ceil($value['saldo']))."</td>";
                        echo "</tr>";
                    }
                ?>
            </tr>
        </table>
    </div>
</div>