<form role="form" class="needs-validation" id="form_add_discount_parameter" name="form_add_discount_parameter"
    novalidate>
    <input type="hidden" id="txt-card-no" name="txt-card-no">

    <div class="row">
        <div class="widget-header widget-header-small">

            <div class="spinner-border spinner-border-sm" role="status" id="loading_pencarian_account"
                style="display:none">
                <span class="visually-hidden">Loading...</span>
            </div>

        </div>

        <div class="row">
            <div>
                <form class="form-search">
                    <div class="row">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="txt-search-account"
                                placeholder="No. Pinjaman/CC" aria-label="No. Pinjaman/CC"
                                aria-describedby="btn-search-account">
                            <button class="btn btn-outline-success" type="button"
                                id="btn-search-account">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <center>
            <h4 class="">
                <i class="bi bi-bookmark"></i>
                Request Data
            </h4>
        </center>

        <div class="row">
            <div class="input-group input-group-sm mb-3" style="display:none">
                <span class="input-group-text" id="basic-addon1"> No. Pinjaman/CC</span>
                <input type="text" class="form-control bolded" id="txt-card-number" aria-describedby="basic-addon2">
            </div>
            <div class="input-group input-group-sm mb-3" style="display:none">
                <span class="input-group-text" id="basic-addon" style="width: 145px;"> CIF</span>
                <input type="text" class="form-control bolded" id="txt-cif" aria-describedby="basic-addon" readonly>
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="basic-addon2" style="width: 145px;">Customer Name </span>
                <input type="text" class="form-control bolded" id="cutomer-name" aria-describedby="basic-addon2"
                    readonly>
            </div>
            <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="basic-addon3" style="width: 145px;">Product</span>
                <input type="text" class="form-control bolded" id="product_type" aria-describedby="basic-addon3"
                    readonly>
            </div>
        </div>
    </div>

</form>

<script language="javascript">
    jQuery(function ($) {

        $('.btn-request').hide();

        function SearchAccount(keyword) {

            var url = GLOBAL_MAIN_VARS["SITE_URL"] +
                "workflow_pengajuan/workflow_pengajuan_restructure/search_account";
            $.ajax({
                type: "GET",
                url: url,
                async: false,
                dataType: "json",
                data: {
                    keyword: keyword.trim()
                },
                success: function (msg) {
                    console.log(msg.data)
                    if (msg.success == true) {
                        // $("#txt-card-no").val(msg.data[0].CM_CARD_NMBR);

                        $("#card-number").val(msg.data[0].CM_CARD_NMBR);
                        $("#txt-card-number").val(msg.data[0].CM_CARD_NMBR);
                        $("#cutomer-name").val(msg.data[0].nama);
                        $("#product_type").val(msg.data[0].CM_TYPE);
                        $("#txt-cif").val(msg.data[0].CM_CUSTOMER_NMBR);
                        $('.btn-request').show();
                        //$("#due-date").text(msg.rows[0].CM_DTE_PYMT_DUE);
                    } else {
                        showWarning(msg.message);
                    }
                    $("#loading_pencarian_account").hide();
                },
                error: function () {
                    showWarning("Error: SearchAccount");
                    $("#loading_pencarian_account").hide();
                }
            });
            // alert($("#txt-card-number").val())
        }

        $("#btn-search-account").click(function () {

            if ($("#txt-search-account").val() == '') {
                return false;
            }
            $("#loading_pencarian_account").show();
            $("#card-number").text("");
            $("#txt-card-number").val("");
            $("#cutomer-name").text("");
            $("#product_type").text("");
            setTimeout(() => {
                SearchAccount($("#txt-search-account").val());
            }, 100);

        });
    })
</script>