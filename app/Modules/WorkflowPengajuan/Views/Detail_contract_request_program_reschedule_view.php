<?php

    use App\Models\Common_model;
    $this->Common_model = new Common_model();

?>
<style>
    .widthLabel {
        width: 250px
    }
</style>
<form class="form-horizontal" id="formDiscountRequest" name="formDiscountRequest" method="POST">
    <?php 
            // echo json_encode($data);
            // die;
			$alertPengajuan = '';
			if(@$pengajuan_data["status"]=='REJECT'){
				$rejector = $this->Common_model->get_record_values('b.name,a.notes','cms_approval a,cc_user b','a.created_by=b.id and a.id_pengajuan="'.$pengajuan_data["id"].'"  and action="APPROVAL" ');
				$jam = $this->Common_model->MonthIndo($pengajuan_data["updated_time"]);
				echo	'<div class="alert alert-danger" role="alert">'.
						'<h4 class="alert-heading">Request Rejected!</h4>'.
						'<p style="font-size: 14px;">Pengajuan PROGRAM di tolak oleh <b>'.$rejector['name'].'</b>.</p>'.
						'<p style="font-size: 12px;">NOTE : '.$rejector['notes'].'</b>.</p>'. 
						'<hr style="margin: 8px;">'.
						'<p class="mb-0" style="font-size: 10px;">'.$jam.'</p>'.
						'</div>';
			}
			else if(@$pengajuan_data["status"]=='APPROVED'){
				$rejector = $this->Common_model->get_record_value('b.name','cms_approval a,cc_user b','a.created_by=b.id and a.id_pengajuan="'.$pengajuan_data["id"].'" and action="APPROVAL" ');
				$jam = $this->Common_model->MonthIndo($pengajuan_data["updated_time"]);
				echo	'<div class="alert alert-success" role="alert">'.
						'<h4 class="alert-heading">Request Approved!</h4>'.
						'<p style="font-size: 12px;">Pengajuan PROGRAM telah di setujui .</p>'.
						'<hr style="margin: 8px;">'.
						'<p class="mb-0" style="font-size: 10px;">'.$jam.'</p>'.
						'</div>';
			}

         
            if(@$AcountStatus['data']['accountOutstandingBalanceIPP']>0){
                echo '<div class="alert alert-warning">';
                echo     '<strong>WARNING!</strong> DEBITUR SEDANG MEMILIKI CICILAN BERJALAN DAN TIDAK DAPAT DI AJUKAN PROGRAM.';
                echo '</div>';
            }
		?>
    <div class="row">
        <div class="col-sm-6">
            <h4 class="">Request Data</h4>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> CIF No.</span>
                <input type="text" class="form-control" id="lbl-cif" value="<?=$customer_data["cm_card_nmbr"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2">Customer Name</span>
                <input type="text" class="form-control" value="&nbsp;<?=$customer_data["name"]?>"
                    placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> No. Pinjaman/CC</span>
                <input type="text" class="form-control" id="card_no" value="<?=$credit_data["CM_CARD_NMBR"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Outstanding AR</span>
                <input type="text" class="form-control" id="lbl-outstanding-ar"
                    value="<?=number_format($credit_data["CM_TOTAL_OS_AR"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Principal</span>
                <input type="text" class="form-control" id="lbl-outstanding-principal"
                    value="<?= $credit_data["CM_OS_PRINCIPLE"] !== null ? number_format($credit_data["CM_OS_PRINCIPLE"], 2) : '0.00' ?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Interest</span>
                <input type="text" class="form-control" id="lbl-outstanding-interest"
                    value="<?=number_format($credit_data["CM_OS_INTEREST"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Instalment</span>
                <input type="text" class="form-control" id="lbl-installment"
                    value="<?=number_format($credit_data["CM_INSTALLMENT_AMOUNT"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Balance</span>
                <input type="text" class="form-control" id="txt_os_balance"
                    value="<?=number_format($credit_data["CM_OS_BALANCE"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>

            <br>
            <h4 class="">Financial Current Condition</h4>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Due Principal/Balance </span>
                <input type="text" class="form-control" id="lbl-principal-balance"
                    value="<?=number_format($credit_data["CM_TOT_PRINCIPAL"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Due Interest </span>
                <input type="text" class="form-control" id="lbl-due-interest"
                    value="<?=number_format($credit_data["CM_TOT_INTEREST"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Late Charge </span>
                <input type="text" class="form-control" id="lbl-late-charge"
                    value="<?=number_format($credit_data["CM_TOT_INTEREST"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Total </span>
                <input type="text" class="form-control" id="lbl-total-1"
                    value="<?=@number_format($credit_data["CM_TOT_PRINCIPAL"]+$credit_data["CM_TOT_INTEREST"]+$credit_data["CM_RTL_MISC_FEES"], 2)?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Interest Rate </span>
                <input type="text" class="form-control" id="lbl-interest-rate"
                    value="<?=number_format($credit_data["CM_INTR_PER_DIEM"], 2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Request Date </span>
                <input type="text" class="form-control" id="lbl-request-date"
                    value="<?=$this->Common_model->MonthIndo(date('Y-m-d'))?>" aria-describedby="basic-addon2" disabled>
            </div>

        </div>

        <div class="col-sm-6">
            <h4 class="">- </h4>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Outstanding Installment</span>
                <input type="text" class="form-control" id="lbl-outstanding-installment"
                    value="<?=number_format($credit_data["CM_AMNT_OUTST_INSTL"],2)?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> DPD</span>
                <input type="text" class="form-control" id="lbl-dpd" value="<?=$credit_data["DPD"];?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Bucket</span>
                <input type="text" class="form-control" id="lbl-bucket" value="<?=$credit_data["CM_BUCKET"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> MoB/Open Date</span>
                <input type="text" class="form-control" id="lbl-mob-open-date"
                    value="<?= isset($credit_data["MOB"]) ? $credit_data["MOB"] : '-' ?> / <?= isset($credit_data["CM_DTE_OPENED"]) ? $this->Common_model->MonthIndo($credit_data["CM_DTE_OPENED"]) : '-' ?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Installment No./Tenor</span>
                <!-- <input type="text" class="form-control" id="lbl-mob-open-date" value="<?=$credit_data["CM_INSTALLMENT_NO"]?>/<?=$credit_data["CM_TENOR"]?>"  aria-describedby="basic-addon2" disabled> -->
                <input type="text" class="form-control" id="lbl-mob-open-date"
                    value="<?= isset($credit_data["CM_INSTALLMENT_NO"]) ? $credit_data["CM_INSTALLMENT_NO"] : '-' ?> / <?= isset($credit_data["CM_TENOR"]) ? $credit_data["CM_TENOR"] : '-' ?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Due Date/Cycle Date</span>
                <input type="text" class="form-control" id="lbl-mob-open-date"
                    value="<?=@$this->Common_model->MonthIndo($credit_data["CM_DTE_PYMT_DUE"])?> / <?=$credit_data["CM_CYCLE"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Product code / Product Type</span>
                <input type="text" class="form-control" id="lbl-mob-open-date"
                    value="<?=$credit_data["CM_TYPE"]?>/<?=$credit_data["CM_TYPE"]?>" aria-describedby="basic-addon2"
                    disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Limit / Maturity Date</span>
                <input type="text" class="form-control" id="lbl-limit"
                    value="<?=number_format($credit_data["CM_CRLIMIT"], 2)?>" aria-describedby="basic-addon2" disabled>
            </div>


            <!-- start 04-->
            <br>
            <h4 class="">- </h4>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Collectability</span>
                <input type="text" class="form-control" id="lbl-collectability"
                    value="<?=$credit_data["CM_COLLECTIBILITY"];?>" aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Minimum Payment</span>
                <input type="text" class="form-control" id="lbl-minimum-payment"
                    value="<?=$credit_data["CM_AMOUNT_DUE"];?>" aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Block Status</span>
                <input type="text" class="form-control" id="lbl-block-status" value="<?=$credit_data["CM_BLOCK_CODE"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Charge Off Date</span>
                <input type="text" class="form-control" id="lbl-charge-off-date"
                    value="<?=$this->Common_model->MonthIndo(@$credit_data["CM_DTE_CHGOFF_STAT_CHANGE"])?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> Request By</span>
                <input type="text" class="form-control" id="lbl-request-by" value="<?=@$pengajuan_data["created_by"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>
            <div class="input-group mb-1">
                <span class="input-group-text widthLabel" id="basic-addon2"> TL Name</span>
                <input type="text" class="form-control" id="lbl-tl-name" value="<?=@$pengajuan_data["created_by"]?>"
                    aria-describedby="basic-addon2" disabled>
            </div>

            <div class="input-group mb-1" hidden>
                <span class="input-group-text widthLabel" id="basic-addon2"> Kondisi Khusus</span>
                <select class="form-select" name='flag_kondisi_khusus' id='flag_kondisi_khusus'>
                    <option value='0'>NO</option>
                    <option value='1'>YES</option>
                </select>
            </div>

            <div class="input-group mb-1" hidden>
                <span class="input-group-text widthLabel" id="basic-addon2"> Deskripsi Kondisi Khusus</span>
                <?
                            $attributes = 'class="form-select" id="desc_kondisi_khusus" disabled';
                            echo form_dropdown('desc_kondisi_khusus', @$kondisi_khusus_list, "", $attributes);
                        ?>
            </div>
            <div class="input-group mb-1" hidden>
                <span class="input-group-text widthLabel" id="basic-addon2"> Catatan Kondisi Khusus</span>
                <textarea col="5" name='txt_catatan_kondisi_khusus' id='txt_catatan_kondisi_khusus'></textarea>
            </div>

        </div>

        <!-- END DIV COL-6 -->
    </div>


    <!-- END ROW -->
    </div>