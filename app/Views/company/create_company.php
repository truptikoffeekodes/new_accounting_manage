<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.error {
    color: red;
}
</style>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Company</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Company</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="<?=url('Company/CreateCompany')?>" id="companyform" enctype="multipart/form-data"
                            method="post">
                            <div id="wizard1">
                                <h3>General</h3>
                                <section>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label"><b>Trade Name</b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" required name="company_name"
                                                placeholder="Enter Name" style="text-transform:uppercase"
                                                onkeyup="code_generate(this.value)" type="group"
                                                value="<?=@$company['name']?>">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="form-label"> <b>Code:</b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" id="code" name="code"
                                                value="<?=@$company['code']?>" placeholder="Enter Code" required
                                                type="text">
                                            <input class="form-control" name="id" value="<?=@$company['id']?>"
                                                type="hidden">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="form-label"> <b>Company Group: </b></label>
                                            <div class="input-group">
                                                <select class="form-control" name="group" id="companygrp"></select>

                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <a data-toggle="modal" data-target="#fm_model"
                                                            data-title="Add Company Group"
                                                            href="<?=url('Company/create_companygrp')?>"><i
                                                                style="font-size:20px;"
                                                                class="fe fe-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label"><b>Financial year:</b> </label><br>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="form-label">From <span
                                                    class="tx-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control fc-datepicker" required
                                                            name="financial_year_form" placeholder="YYYY-MM-DD"
                                                            type="text" value="<?=@$company['financial_form']?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 form-group">
                                                    <label class="form-label"> To <span
                                                    class="tx-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                                            </div>
                                                        </div>
                                                        <input class="form-control fc-datepicker" required
                                                            name="financial_year_to" placeholder="YYYY-MM-DD"
                                                            type="text" value="<?=@$company['financial_to']?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Legal Person:</b> <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" name="contact_person"
                                                style="text-transform:capitalize;" required
                                                value="<?= @$company['contact_person'] ?>"
                                                placeholder="Enter Contact Person" type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Contact:</b> </label>
                                            <input class="form-control" name="altername_contact"
                                                value="<?= @$company['alternate_contact'] ?>"  
                                                onkeypress="return isNumberKey(event)"
                                                placeholder="Enter Alternate Contact" type="text">
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Whatsap No.:</b> <span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" name="whatsap"  value="<?=@$company['whatsap']?>"
                                                onkeypress="return isNumberKey(event)" placeholder="Enter Mobile No."
                                                required="" type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Email: </b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" name="email" onkeyup="this.value = this.value.toLowerCase();" value="<?=@$company['email']?>"
                                                placeholder="Enter Email" required type="text">
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>official Address:</b></label>
                                            <textarea class="form-control"
                                                name="address"><?= @$company['address'] ?></textarea>
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Country.:</b> <span
                                                    class="tx-danger">*</span></label>
                                            <select class="form-control" id="country" required name='country'>
                                                <?php if(@$company['country_name']) { ?>
                                                <option value="<?=@$company['country']?>">
                                                    <?=@$company['country_name']?>
                                                </option>
                                                <?php }else{ ?>
                                                <option value="101" selected>India </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>State: </b><span
                                                    class="tx-danger">*</span></label>
                                            <select class="form-control" id="state" required name='state'>
                                                <?php if(@$company['state_name']) { ?>
                                                <option value="<?=@$company['state']?>">
                                                    <?=@$company['state_name']?>
                                                </option>
                                                <?php }else{ ?>
                                                <option value="22" selected>Maharashtra</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>City: </b></label>
                                            <select class="form-control" id="city" name="city">
                                                <?php if(@$company['city_name']) { ?>
                                                <option value="<?=@$company['city']?>"><?=@$company['city_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="form-label"><b> PIN Code: </b></label>
                                            <input type="text"  name="pin" onkeypress="return isNumberKey(event)"
                                                value="<?= (!empty($company)) ? $company['pin'] : '' ?>"
                                                class="form-control" placeholder="Enter Pin" >
                                        </div>

                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label"><b> Sign Caption: </b></label>
                                            <input type="file" name="sign_caption"
                                                value="<?= (!empty($company)) ? $company['sign_capture'] : '' ?>"
                                                data-default-file="<?= (!empty($company)) ? $company['sign_capture'] : '' ?>"
                                                class="dropify" data-height="100">
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="form-label"><b> Logo: </b></label>
                                            <input type="file" name="logo"
                                                value="<?= (!empty($company)) ? $company['logo'] : '' ?>"
                                                data-default-file="<?= (!empty($company)) ? $company['logo'] : '' ?>"
                                                class="dropify" data-height="100">
                                        </div>
                                    </div>
                                </section>

                                <h3>Gst Detail</h3>
                                <section>
                                    <?php 
                                if(!empty($company)){
                                    $gst_date = user_date($company['gst_date']);
                                    $localtax_date = user_date($company['localtax_date']);
                                    $gst_date = user_date($company['gst_date']);
                                    $cst_date = user_date($company['cst_date']);
                                }
                                ?>
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label class="form-label"> <b>GST No.:</b> </label>
                                            <input class="form-control" name="GST" value="<?=@$company['gst_no']?>" 
                                                placeholder="Gst Number" type="text">
                                            <br>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-calendar lh--9 op-6"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" value="<?=@$gst_date?>"
                                                    name="gst_date" placeholder="YYYY-MM-DD" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="form-label"><b>Local Sales Tax No.:</b> </label>
                                            <input class="form-control" name="local_tax"
                                                value="<?=@$company['localtax_no']?>" placeholder="Enter Tax No."
                                                type="text">
                                            <br>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-calendar lh--9 op-6"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" name="local_tax_date"
                                                    placeholder="DD-MM-YYYY" type="text" value="<?=@$localtax_date?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="form-label"> <b>Central Sales Tax No.:</b> </label>
                                            <input class="form-control" name="central_tax"
                                                value="<?=@$company['centraltax_no']?>"
                                                placeholder="Enter Central Tax No." type="text">
                                            <br>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-calendar lh--9 op-6"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" value="<?=@$centraltax_date?>"
                                                    placeholder="DD-MM-YYYY" type="text" name="central_tax_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="form-label"> <b>CST Tin No.:</b> </label>
                                            <input class="form-control" name="cst_tin" value="<?=@$company['cst_no']?>"
                                                placeholder="CST Tin No." type="text">
                                            <br>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-calendar lh--9 op-6"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control fc-datepicker" value="<?=@$cst_date?>"
                                                    name="cst_tin_date" placeholder="DD-MM-YYYY" type="text">
                                            </div>
                                        </div>

                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label class="form-label"><b> Income Tax PAN: </b><span
                                                    class="tx-danger">*</span></label>
                                            <input class="form-control" name="income_tax_pan" required onkeyup="this.value = this.value.toUpperCase();"
                                                value="<?=@$company['incomtax_pan']?>" placeholder="Enter PAN No."
                                                type="text">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label class="form-label"> <b>Ward No:</b> </label>
                                            <input class="form-control" name="ward_no" value="<?=@$company['ward_no']?>"
                                                placeholder="Enter Ward No." type="text">

                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="mg-b-10"><b>Form of Company</b> <span
                                                    class="tx-danger">*</span></label>
                                            <select class="form-control select2" name="company_form" required>
                                                <option value="Sole Proprietorship">Sole Proprietorship
                                                </option>
                                                <option value="Partnership">Partnership</option>
                                                <!-- <option value="Corporation">Corporation</option> -->
                                                <option value="Private Limited">Private Limited</option>
                                                <!-- <option value="Public Limited">Public Limited</option> -->
                                                <option value="LLP">LLP</option>
                                                <option value="HUF">HUF</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label class="mg-b-10"><b>Business Type</b></label>
                                            <select name="business_type" name="business_type[]"
                                                class="form-control select2">
                                                <option value="Manufacturer">Manufacturer</option>
                                                <option value="Trader">Trader</option>
                                                <option value="Professional">Professional</option>
                                                <option value="Services">Services</option>
                                                <option value="Transport">Transport</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 form-group">
                                            <label class="form-label"><b> Buisness Code: </b> </label>
                                            <input class="form-control" name="buisness_code" id="buisness_code"
                                                value="<?=@$company['buisness_code']?>"
                                                placeholder="Enter Business Code" type="text">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Reg. Certi. No.:</b> </label>
                                            <input class="form-control" name="reg_cert"
                                                value="<?=@$company['reg_certi']?>" placeholder="Enter Registration No"
                                                type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Enroll Certificate / MSME No.:</b> </label>
                                            <input class="form-control" name="enrol_certi"
                                                value="<?=@$company['enrol_certi']?>" placeholder="Enter Enroll No."
                                                type="text">
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label class="form-label"><b>Impo/Expo Code:</b> </label>
                                            <input class="form-control" name="impo_expo"
                                                value="<?=@$company['impo_expo']?>" placeholder="Enter Import Export No"
                                                type="text">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Bank Holder:</b> </label>
                                            <input class="form-control" name="bank_holder"
                                                value="<?=@$company['bank_ac_name']?>" placeholder="Enter Bank Holder Name"
                                                type="text">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Bank A/C No.:</b> </label>
                                            <input class="form-control" name="bank_ac"
                                                value="<?=@$company['bank_ac']?>" placeholder="Enter Bank A/C No."
                                                type="text">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>IFSC .:</b> </label>
                                            <input class="form-control" name="ifsc" style="text-transform:uppercase"
                                                value="<?=@$company['ifsc']?>" placeholder="Enter IFSC code."
                                                type="text">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label class="form-label"><b>Bank Name:</b> </label>
                                            <input class="form-control" name="bank_name"
                                                value="<?=@$company['bank_name']?>" placeholder="Enter Bank Name"
                                                type="text">
                                        </div>
                                    </div>
                                </section>
                                <div class="form-group">
                                    <div class="tx-danger description_error"></div>
                                    <div class="tx-success form_proccessing"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?=ASSETS;?>js/jquery.validate.js"></script>
<script>
var form_loading = true;


$(document).ready(function() {

    var form = $("#companyform");
    var finishButton = $('.wizard').find('a[href="#finish"]'); // Declare Variable finishButton 

    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            var placement = $(element).data('error');
            console.log(placement)
            if (placement) {
                $(placement).html(error);
            } else {
                error.insertAfter(element);    
            }
        },
        rules: {
            whatsap: {
                required: true,
                minlength : 10,
                maxlength : 12,
            },
            contact: {
                minlength : 10,
                maxlength : 12,
            },
            GST: {
                minlength : 15,
                maxlength : 15,
            },
            contact_person: {
                required: true,
            },
            email: {
                required: true,
            },
            company_name: {
                required: true,
            },
            country: {
                required: true,
            },
            state: {
                required: true,
            },
            income_tax_pan: {
                required: true,
                minlength : 10,
                maxlength : 10,
            }
        },
        messages: {
            whatsap: {
                required: "Please Enter Whatsapp Number",
            },
            contact_person: {
                required: "Please Enter Contact Person Name",
            },
            email: {
                required: "Please Enter Email",
            },
            company_name: {
                required: "Please Enter Company Name",
            },
            country: {
                required: "Please select Country",
            },
            State: {
                required: "Please Select State",
            },
            construction_age: {
                required: "Please select construction age",
            },
            income_tax_pan: {
                required: "Please Enter Pan No",
            },
            
        }
    });

    var finishButton = $('.wizard').find('a[href="#finish"]'); // Declare Variable finishButton 

    $('#wizard1').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
        onStepChanging: function(event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            if (form_loading) {
                return true;
            } else {
                return false;
            }
        },
        onFinished: function(event, currentIndex) {
            // var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }
            // var data = form.serialize();
            finishButton.html("<i class='sl sl-icon-reload'></i> Please wait...");

            form_loading = false;
            $('.description_error').html('');
            var aurl = $('#companyform').attr('action');
            $.ajax({
                type: "POST",
                url: aurl,
                cache: false,
                contentType: false,
                processData: false,
                data: formdata ? formdata : form.serialize(),
                success: function(response) {
                    if (response.st == 'success') {
                        window.location = "<?=url('Company')?>";
                    } else {
                        finishButton.html("Create Company");
                        form_loading = true;
                        $('.description_error').html(response.msg);
                    }
                },
                error: function() {
                    finishButton.html("Create Company");
                    form_loading = true;
                    alert('Error');
                }
            });
            // $.post(aurl, data, function(response) {
            //     if (response.st == 'success') {
            //         window.location = "<?=url('Company')?>";
            //     } else {
            //         //finishButton.html("Create Company");
            //         //form_loading = true;
            //         $('.description_error').html(response.msg);
            //     }
            // }).fail(function(response) {
            //     finishButton.html("Create Company");
            //     form_loading = true;
            //     alert('Error');
            // });
        }
    });

    finishButton = $('.wizard').find('a[href="#finish"]'); // Assign Value After Wizard

    $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });
    $('.select2-no-search').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (2M max).'
        }
    });

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    // $('.fc-datepicker').mask('99-99-9999');

    $("#country").select2({
        width: '100%',
        placeholder: 'Type Country Name',
        ajax: {
            url: PATH + "Company/Getdata/search_country",
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
            url: PATH + "Company/Getdata/search_state",
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

    $("#city").select2({
        width: '100%',
        placeholder: 'Type City',
        ajax: {
            url: PATH + "Company/Getdata/search_city",
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

    $("#companygrp").select2({
        width: 'resolve',
        placeholder: 'Select CompanyGroup',
        ajax: {
            url: PATH + "Company/Getdata/searchCompanyGroup",
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

});
</script>
<?= $this->endSection() ?>