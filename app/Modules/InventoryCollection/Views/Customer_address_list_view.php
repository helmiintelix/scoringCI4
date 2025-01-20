<div class="row">
    <div class="col-xs-12" id="parent">
        <table class="table table-striped table table-bordered">
            <thead>
                <th>No</th>
                <th>Address Type</th>
                <th>Address</th>
                <th>Province</th>
                <th>City</th>
                <th>Sub District</th>
                <th>District</th>
                <th>Zipcode</th>
            </thead>
            <tbody>
                <tr>
                    <td><?= 1;?></td>
                    <td>Curr</td>
                    <td><?= $address_curr["CM_CURR_ADDR"];?></td>
                    <td><?= $address_curr["CM_CURR_PROVINCE"];?></td>
                    <td><?= $address_curr["CM_CURR_CITY"];?></td>
                    <td><?= $address_curr["CM_CURR_SUBDIST"];?></td>
                    <td><?= $address_curr["CM_CURR_DISTRICT"];?></td>
                    <td><?= $address_curr["CM_CURR_ZIPCODE"];?></td>
                </tr>
                <tr>
                    <td><?= 2;?></td>
                    <td>Home</td>
                    <td><?= $address_curr["CM_HOME_ADDR"];?></td>
                    <td><?= $address_curr["CM_HOME_PROVINCE"];?></td>
                    <td><?= $address_curr["CM_HOME_CITY"];?></td>
                    <td><?= $address_curr["CM_HOME_SUBDIST"];?></td>
                    <td><?= $address_curr["CM_HOME_DISTRICT"];?></td>
                    <td><?= $address_curr["CM_HOME_ZIPCODE"];?></td>
                </tr>
                <tr>
                    <td><?= 3;?></td>
                    <td>ID</td>
                    <td><?= $address_curr["CM_ID_ADDR"];?></td>
                    <td><?= $address_curr["CM_ID_PROVINCE"];?></td>
                    <td><?= $address_curr["CM_ID_CITY"];?></td>
                    <td><?= $address_curr["CM_ID_SUBDIST"];?></td>
                    <td><?= $address_curr["CM_ID_DISTRICT"];?></td>
                    <td><?= $address_curr["CM_ID_ZIPCODE"];?></td>
                </tr>
                <tr>
                    <td><?= 4;?></td>
                    <td>GUARANTOR</td>
                    <td><?= $address_curr["CR_GUARANTOR_LINE1"];?></td>
                    <td><?= $address_curr["CR_GUARANTOR_PROVINCE"];?></td>
                    <td><?= $address_curr["CR_GUARANTOR_CITY"];?></td>
                    <td><?= $address_curr["CR_GUARANTOR_SUB_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_GUARANTOR_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_GUARANTOR_ZIPCODE"];?></td>
                </tr>
                <tr>
                    <td><?= 5;?></td>
                    <td>Emergency </td>
                    <td><?= $address_curr["CR_EC_LINE1"];?></td>
                    <td><?= $address_curr["CR_EC_PROVINCE"];?></td>
                    <td><?= $address_curr["CR_EC_CITY"];?></td>
                    <td><?= $address_curr["CR_EC_SUB_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_EC_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_EC_ZIPCODE"];?></td>
                </tr>
                <tr>
                    <td><?= 6;?></td>
                    <td>Office </td>
                    <td><?= $address_curr["CM_CURR_ADDR"];?></td>
                    <td><?= $address_curr["CM_COMPANY_PROVINCE"];?></td>
                    <td><?= $address_curr["CR_COMPANY_CITY"];?></td>
                    <td><?= $address_curr["CR_COMPANY_SUB_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_COMPANY_DISTRICT"];?></td>
                    <td><?= $address_curr["CR_EC_ZIPCODE"];?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>