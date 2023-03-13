<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_account') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">

            <div class="form-group">
                <div class="input-group">
                    <select class="form-control" id="glgrp_ac" name='glgrp' required>
                        <?php if($type == 'sundry_creditor'){ ?>
                        <option value="13">Sundry Creditors</option>
                        <?php }else if($type == 'sundry_debtor'){ ?>
                        <option value="19">Sundry Debtors</option>
                        <?php }else if($type == 'broker'){ ?>
                        <option value="9">Broker</option>
                        <?php }else if($type == 'bank'){ ?>
                        <option value="22">Banks</option>
                        <?php }else{}?>
                    </select>
                </div>
            </div>

            <div class="form-group" id="name_hide">
                <label class="form-label"><b>Name </b><span class="tx-danger">*</span></label>
                <input class="form-control" name="name" id="name" value="" onkeyup="acc_code_generate(this.value)"
                    placeholder="Enter Name" style="text-transform: capitalize;" required type="text">
                    <input type="hidden" id="type" value="<?=@$type;?>">
            </div>

            <div class="form-group" id="name_show"
                style="display:<?=@$account_view['gl_grp'] == 'Duties and taxes' ? 'block;' : 'none;' ?>">
                <label class="form-label"><b>Name:</b></label>
                <select class="form-control select2" name="taxes_name" id="taxes_name">
                    <option value="">None</option>
                    <option <?=(@$account_view['name'] == "igst" ? 'selected' : '')?> value="igst">
                        IGST</option>
                    <option <?=(@$account_view['name'] == "sgst" ? 'selected' : '')?> value="sgst">
                        SGST</option>
                    <option <?=(@$account_view['name'] == "cgst" ? 'selected' : '')?> value="cgst">
                        CGST</option>
                    <option <?=(@$account_view['name'] == "cess" ? 'selected' : '')?> value="cess">
                        CESS</option>
                    <option <?=(@$account_view['name'] == "tds" ? 'selected' : '')?> value="tds">
                        TDS</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label"><b>Owner Name </b></label>
                <input class="form-control" name="own_name" value="" placeholder="Enter Name" type="text">
            </div>
            <div class="form-group">
                <label class="form-label"><b>Code:</b><span class="tx-danger">*</span></label>
                <input class="form-control" name="code" id="acc_code" value="" placeholder="Enter Code" type="text">
                <input name="id" value="" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label"><b>Party Group:</b></label>
                <div class="input-group">
                    <select class="form-control" id="party" name='party'>

                    </select>
                </div>
            </div>
            <div id="gl_hide">
                <div class="form-group">
                    <label class="form-label"><b>GST Address:</b></label>
                    <input class="form-control" name="gst_add" id="add" value="" placeholder="Enter GST Address"
                        type="text">
                </div>
                <div class="form-group">
                    <label class="form-label"><b>Opening Bal (on <?=user_date(session('financial_form'))?>
                            ):</b></label>
                    <input class="form-control" name="opening_bal" onkeypress="return isDesimalNumberKey(event)"
                        value="" placeholder="Enter Opening Balance" type="text">
                </div>
                <div class="form-group">
                    <label class="form-label"><b>Opening Bal. Amt:</b></label>
                    <select class="form-control select2" id="openingBal_type" name="opening_type">
                        <option value="Credit">Credit</option>
                        <option value="Debit">Debit</option>
                    </select>
                </div>
                <div class="form-group" id="due_day_div">
                    <label class="form-label"><b>Interest Rate(%):</b></label>
                    <input class="form-control" name="intrate" value="<?=@$account_view['intrest_rate'];?>"
                        placeholder="Enter Interest Rate (%)" type="text">
                </div>
                <div class="form-group" id="due_day_div">
                    <label class="form-label"><b>Default due Days:</b></label>
                    <input class="form-control" name="due" value="" placeholder="Eneter Default Due days" type="text">
                </div>
                <div class="form-group">
                    <label class="form-label"><b>Country:</b></label>
                    <select class="form-control" id="country" name='country'>
                        <option value="101" selected>India </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><b>State:</b></label>
                    <select class="form-control" id="state" name='state'>
                        <option value="12" selected>Gujarat</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><b>City:</b></label>
                    <select class="form-control" id="acc_city" name="city">
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label"><b>whatsapp:</b></label>
                    <input class="form-control" name="whatspp" value="" minlength="10" maxlength="10"
                        placeholder="Enter Whatsapp No." type="text" onkeypress="return isNumberKey(event)">
                </div>
                <div class="form-group">
                    <label class="form-label"><b>Income Tax PAN:</b></label>
                    <input class="form-control" name="taxpan" value="" minlength="10" maxlength="10"
                        style="text-transform: uppercase;" placeholder="Enter Income Tax PAN" type="text">
                </div>
                <div class="form-group" id="gst_type_div" style="display:<?=@$gst_type_display?>">
                    <label class="form-label"><b>GST Type:</b></label>
                    <select class="form-control select2" id="gst_type" name="gst_type">

                        <option value="Unregister">Unregister</option>
                        <option value="Regular">Regular</option>
                        <option value="Composition">Composition</option>
                        <option value="Unknown">Unknown</option>
                        <option value="Consumer">Consumer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group" id="gstno_div" style="display:none;">
                    <label class="form-label"><b>GST No:</b></label>
                    <input class="form-control" name="gst" minlength="15" maxlength="15"
                        style="text-transform: uppercase;" id="gst_number" placeholder="Enter GST No" type="text">
                </div>

                <?php if($type == 'bank') { ?>
                <div class="form-group" id="ac_type">
                    <label class="form-label"><b>Bank A/C Type:</b></label>
                    <select class="form-control" name='ac_type'>

                        <option value="Current" >
                            Current
                        </option>
                        <option value="Saving" >
                            Saving
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label"><b>Bank Branch:</b></label>
                    <input class="form-control" name="bankbranch" value=""
                        placeholder="Enter Bank Branch" type="text">
                </div>

                <div class="form-group">
                    <label class="form-label"><b>Bank A/C No:</b></label>
                    <input class="form-control" name="bankac" value=""
                        placeholder="Enter Bank A/C NO" type="text">
                </div>

                <div class="form-group">
                    <label class="form-label"><b>Bank IFSC:</b></label>
                    <input class="form-control" name="bankifsc" value=""
                        placeholder="Enter Bank IFSC" onkeyup="this.value = this.value.toUpperCase();" type="text">
                </div>
            </div>

            <?php } ?>

            <div class="form-group">
                <div class="tx-danger error-msg-acc"></div>
                <div class="tx-success form_proccessing_acc"></div>
            </div>

            <div class="row pt-3">
                <div class="col-sm-6">
                    <p class="text-left">
                        <button class="btn btn-space btn-primary" id="acc_save_data" type="submit">Submit</button>
                        <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Page Header -->

<script>
$('.ajax-form-submit').on('submit', function(e) {
    $('#acc_save_data').prop('disabled', true);
    $('.error-msg-acc').html('');
    $('.form_proccessing_acc').html('Please wail...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                $('#fm_model').modal('toggle');
                var type = $('#type').val();
                //alert(gl);
                if(type == 'sundry_debtor' || type == 'sundry_creditor')
                {
                    $('#account').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
                    get_account_data(response.id,response.data);
                }
                else if(type == 'broker')
                {
                    $('#broker').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
                }
                // else if(type == 'bank')
                // {

                // }
                else
                {
                    $('#party_account').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
                    get_account_data(response.id,response.data);
                }
                $('#acc_save_data').prop('disabled', false);

            } else {
                $('.form_proccessing_acc').html('');
                $('#acc_save_data').prop('disabled', false);
                $('.error-msg-acc').html(response.msg);
            }
        },
        error: function() {
            $('#acc_save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

function acc_code_generate(name) {

    var year = new Date().getFullYear();
    var substr = name.substring(0, 3)

    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    var random = '';

    for (var i = 0; i < 4; i++) {
        random += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    var join = substr.concat(year);
    var finalstr = join.concat(random);

    var code = finalstr.toUpperCase();

    $('#acc_code').val(code);
}

function afterload() {

    $('select[name="opening_type"]').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });
    $('select[name="taxes_name"]').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });

    $('select[name="gst_type"]').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });

    $('select[name="ac_type"]').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });

    $('#fm_model').on('shown.bs.modal', function() {

        $('.fc-datepicker').datepicker({
            format: "dd/mm/yyyy",
            startDate: "01-01-2015",
            endDate: "01-01-2020",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#fm_model modal-body'
        });

        $("#glgrp_ac").select2({
            width: '100%',
            placeholder: 'Type GL',
            ajax: {
                url: PATH + "Master/Getdata/parent_glgrp",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });


        $('#glgrp_ac').on('select2:select', function(e) {
            var data = e.params.data;

            var glDiv = document.getElementById("gl_hide");
            var brokrage = document.getElementById("brokrage");
            //var hsn_div = document.getElementById("hsn_div");

            var Name = document.getElementById("name_show");
            var HideName = document.getElementById("name_hide");
            var gstno_div = document.getElementById("gstno_div");
            var taxability_div = document.getElementById("taxability_div");
            var gst_type_div = document.getElementById("gst_type_div");
            var hsn_div = document.getElementById("hsn_div");

            var bank_div = document.getElementById("bank_div");
            var h3_bank = document.getElementById("h3_bank");

            var tax_div = document.getElementById("tax_div");
            var h3_tax = document.getElementById("h3_tax_div");

            var bank_holder = document.getElementById("bank_holder");
            var ac_type = document.getElementById("ac_type");

            var trans_div = document.getElementById("trans_div");

            var text = data.text;
            var main_id = data.main_id;
            var tx_bn_hide = data.tx_bn_hide;
            var bank_id = data.bank_id;
            var opening_balDr = data.opening_balDr;
            var opening_balCr = data.opening_balCr;
            var creditor_debtor = data.creditor_debtor;

            if (opening_balDr == '1' || opening_balDr == '3') {
                $('select[name="opening_type"]').val('Debit').trigger('change');
            } else {
                $('select[name="opening_type"]').val('Credit').trigger('change');
            }

            if (opening_balCr == '2' || opening_balCr == '4') {
                $('select[name="opening_type"]').val('Credit').trigger('change');
            } else {
                $('select[name="opening_type"]').val('Debit').trigger('change');
            }



            console.log(data);
            if (text == 'Expenses' || text == 'Incomes' || data.parent_id == data.expense_id || data
                .parent_id == data.income_id) {
                glDiv.style.display = "none";

            } else {
                glDiv.style.display = "block";

            }
            if (text == 'Expenses' || text == 'Incomes' || data.new_hide == data.expense_id || data
                .tx_bn_hide == data.income_id) {
                glDiv.style.display = "none";

            } else {
                glDiv.style.display = "block";

            }
            if (text == 'Sundry Creditors' || text == 'Sundry Debtors' || data.parent_id == data
                .expense_id || data
                .parent_id == data.income_id) {
                //console.log("yes");
                $('#state').attr('required', true);
            } else {
                //console.log("no");
                $("#state").attr("required", false);
            }
            if (text == 'Duties and taxes') {
                HideName.style.display = "none";
                Name.style.display = "block";
                $('#name').attr('disabled');
            } else {
                Name.style.display = "none";
                HideName.style.display = "block";
                $('#taxes_name').attr('disabled');
            }
        });

        $('#gst_type').on('select2:select', function(e) {

            var gstDiv = document.getElementById("gst_div");
            var data = e.params.data;
            var gst_no = document.getElementById("gstno_div");

            if (data.id == 'Regular' || data.id == 'Composition') {

                gst_no.style.display = "block";
            } else {
                gst_no.style.display = "none";
                gstDiv.style.display = "none";
            }
        });

        $("#country").select2({
            width: '100%',
            placeholder: 'Type Country Name',
            ajax: {
                url: PATH + "Master/Getdata/search_country",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#party").select2({
            width: '100%',
            placeholder: 'Type Party',
            ajax: {
                url: PATH + "Master/Getdata/search_party",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#state").select2({
            width: '100%',
            placeholder: 'Type State',
            ajax: {
                url: PATH + "Master/Getdata/search_state",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        country: $('#country').val()
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#acc_city").select2({
            width: '100%',
            placeholder: 'Type City',
            ajax: {
                url: PATH + "Master/Getdata/search_city",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        state: $('#state').val()
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    });
    $('#gst_number').mask('99-aaaaa-9999-a-9-Z-*');
}
</script>
<?= $this->endSection() ?>