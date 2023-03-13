<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.error{
    color:red;
}
</style>
<!--colorpicker css-->
<link href="<?=ASSETS?>/plugins/spectrum-colorpicker/spectrum.css" rel="stylesheet">
<!-- page header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Account</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<!-- End page header -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            <div class="card-body">
                <form action="<?=url('account/add_billterm')?>" id="accountform" method="post">
                    <div id="wizard1">
                        <h3>General</h3>
                        <section>
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Code:<span class="tx-danger">*</span></label>
                                    <input class="form-control required" name="code" id="code"
                                        value="<?= @$billterm['code']; ?>" placeholder="Enter Code" required=""
                                        type="text" >
                                    <input name="id" value="<?= @$billterm['id']; ?>" type="hidden">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Formula:</label>
                                    <select class="form-control select2" name="formula">
                                        <option <?= ( @$billterm['formula'] == "B" ? 'selected' : '' ) ?>
                                            value="B"> Basic Amount</option>
                                        <option <?= ( @$billterm['formula'] == "A" ? 'selected' : '' ) ?>
                                            value="A"> Assessable Value</option>
                                        <option <?= ( @$billterm['formula'] == "Q1" ? 'selected' : '' ) ?>
                                            value="Q1"> Quantity1</option>
                                        <option <?= ( @$billterm['formula'] == "Q2" ? 'selected' : '' ) ?>
                                            value="Q2"> Quantity2</option>
                                        <option <?= ( @$billterm['formula'] == "Q3" ? 'selected' : '' ) ?>
                                            value="Q3"> Quantity3</option>
                                        <option <?= ( @$billterm['formula'] == "S" ? 'selected' : '' ) ?>
                                            value="S"> Line Item VAT Amount</option>
                                        <option <?= ( @$billterm['formula'] == "M" ? 'selected' : '' ) ?>
                                            value="M">Line Item Additional VAT Amount</option>
                                        <option <?= ( @$billterm['formula'] == "V" ? 'selected' : '' ) ?>
                                            value="V">vhg</option>
                                    </select>
                                </div>
                                
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Description: <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="description" id="description"
                                        value="<?= @$billterm['description']; ?>" placeholder="Enter Description" required=""
                                        type="text" >
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Calculation Method:</label>
                                    <select class="form-control select2" name="calc_method" required>
                                        <option <?= ( @$billterm['calc_method'] == "none" ? 'selected' : '' ) ?>
                                            value="none"> None</option>
                                        <option <?= ( @$billterm['calc_method'] == "percentage" ? 'selected' : '' ) ?>
                                            value="percentage"> Percentage</option>
                                        <option <?= ( @$billterm['calc_method'] == "value" ? 'selected' : '' ) ?>
                                            value="value"> Value</option>
                                        <option <?= ( @$billterm['calc_method'] == "add_value" ? 'selected' : '' ) ?>
                                            value="add_value">Add Value</option>
                                        <option <?= ( @$billterm['calc_method'] == "substract_value" ? 'selected' : '' ) ?>
                                            value="substract_value">Subtract Value</option>
                                        <option <?= ( @$billterm['calc_method'] == "multiply_by_value" ? 'selected' : '' ) ?>
                                            value="multiply_by_value">Multiply By Value</option>
                                        <option <?= ( @$billterm['calc_method'] == "divide_by_value" ? 'selected' : '' ) ?>
                                            value="divide_by_value">Divide By Value</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                        <label class="form-label">Account: <span class="tx-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="account" id="account"
                                                onchange="validate_autocomplete(this,'account')"
                                                value="<?= @$billterm['account']; ?>" required>
                                            
                                            <input type="hidden" name="account_id" id="account_id" value="<?= @$billterm['account_id']; ?>">
                                            <div class="dz-error-message tx-danger account_id"></div>
                                        </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Value:</label>
                                    <input class="form-control" name="value" id="value"
                                        value="<?= @$billterm['value']; ?>" placeholder="Enetr Value"
                                         type="text">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Sign:</label>
                                    <select class="form-control select2" name="sign" required>
                                        <option <?= ( @$billterm['sign'] == "both" ? 'selected' : '' ) ?>
                                            value="both">Both</option>
                                        <option <?= ( @$billterm['sign'] == "addition" ? 'selected' : '' ) ?>
                                            value="addition">Addition</option>
                                        <option <?= ( @$billterm['sign'] == "substraction" ? 'selected' : '' ) ?>
                                            value="substraction">Subtraction</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="form-label">Rounding Method:</label>
                                    <select name="rounding_method" class="form-control select2" required>
                                        <option <?= ( @$billterm['rounding_method'] == "none" ? 'selected' : '' ) ?>
                                            value="none">None</option>
                                        <option <?= ( @$billterm['rounding_method'] == "truncate" ? 'selected' : '' ) ?>
                                            value="truncate">Truncate</option>
                                        <option <?= ( @$billterm['rounding_method'] == "plus" ? 'selected' : '' ) ?>
                                            value="plus">Plus</option>
                                        <option <?= ( @$billterm['rounding_method'] == "minus" ? 'selected' : '' ) ?>
                                            value="minus">Minus</option>
                                        <option <?= ( @$billterm['rounding_method'] == "normal" ? 'selected' : '' ) ?>
                                            value="normal">Normal</option>
                                       
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Category:</label>
                                    <select name="category" class="form-control select2" required>
                                        <option <?= ( @$billterm['category'] == "normal" ? 'selected' : '' ) ?>
                                            value="normal">Normal</option>
                                        <option <?= ( @$billterm['category'] == "sales_tax" ? 'selected' : '' ) ?>
                                            value="sales_tax">Sales Tax</option>
                                        <option <?= ( @$billterm['category'] == "surcharge" ? 'selected' : '' ) ?>
                                            value="surcharge">Surcharge</option>
                                        <option <?= ( @$billterm['category'] == "post_bill_term" ? 'selected' : '' ) ?>
                                            value="post_bill_term">Post Bill Term</option>
                                        <option <?= ( @$billterm['category'] == "gst" ? 'selected' : '' ) ?>
                                            value="gst">GST</option>
                                        <option <?= ( @$billterm['category'] == "discount" ? 'selected' : '' ) ?>
                                            value="discount">Discount</option>
                                        <option <?= ( @$billterm['category'] == "excise" ? 'selected' : '' ) ?>
                                            value="excise">Excise</option>
                                        <option <?= ( @$billterm['category'] == "line_item_1stadd" ? 'selected' : '' ) ?>
                                            value="line_item_1stadd">Line Item 1st Addt</option>
                                        <option <?= ( @$billterm['category'] == "line_item_2ndadd" ? 'selected' : '' ) ?>
                                            value="line_item_2ndadd">Line Item 2nd Add</option>
                                        <option <?= ( @$billterm['category'] == "line_item_3rdadd" ? 'selected' : '' ) ?>
                                            value="line_item_3rdadd">Line Item 3rd Add</option>
                                        <option <?= ( @$billterm['category'] == "ed_cess" ? 'selected' : '' ) ?>
                                            value="ed_cess">Ed.Cess</option>
                                        <option <?= ( @$billterm['category'] == "hed_cess" ? 'selected' : '' ) ?>
                                            value="hed_cess">H.Ed.Cess</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                                    <label class="form-label">Allow Override</label>
                                                    <select name="override" class="form-control select2" >
                                                    
                                                            <option <?= ( @$billterm['override'] == "yes" ? 'selected' : '' ) ?> value="yes">Yes</option>
                                                            <option <?= ( @$billterm['override'] == "no" ? 'selected' : '' ) ?> value="no">No</option>
                                                        </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                                    <label class="form-label">Status<span class="tx-danger">*</span></label>
                                                    <select name="status" class="form-control select2" required>
                                                            <option label="Select type">
                                                            </option>
                                                            <option <?= ( @$billterm['status'] == "1" ? 'selected' : '' ) ?> value="1">Active</option>
                                                            <option <?= ( @$billterm['status'] == "0" ? 'selected' : '' ) ?> value="0">InActive</option>
                                                        </select>
                                </div>
                            </section>
                        <h3>Other Info</h3>
                        <section>
                            <div class="row">

                                <div class="col-lg-10 form-group">
                                    <label class="form-label">Ordinal:</label>
                                    <input class="form-control" name="ordinal" value="<?= @$billterm['ordinal']; ?>"
                                        placeholder="Enter Ordinal" type="text" required="">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Is User Defined:</label>
                                    <select class="form-control select2" name="is_userdefined">
                                        <option value="">None</option>
                                        <option <?= ( @$billterm['is_userdefined'] == "yes" ? 'selected' : '' ) ?>
                                            value="yes">Yes</option>
                                        <option <?= ( @$billterm['is_userdefined'] == "no" ? 'selected' : '' ) ?>
                                            value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Use For Stock Value:</label>
                                    <select class="form-control select2" name="use_for_stockvalue">
                                        <option value="">None</option>
                                        <option <?= ( @$billterm['use_for_stockvalue'] == "yes" ? 'selected' : '' ) ?>
                                            value="yes">Yes</option>
                                        <option <?= ( @$billterm['use_for_stockvalue'] == "no" ? 'selected' : '' ) ?>
                                            value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label">Use In Vat Calc.:</label>
                                    <select class="form-control select2" name="use_in_vatcalc">
                                        <option value="">None</option>
                                        <option <?= ( @$billterm['use_in_vatcalc'] == "yes" ? 'selected' : '' ) ?>
                                            value="yes">Yes</option>
                                        <option <?= ( @$billterm['use_in_vatcalc'] == "no" ? 'selected' : '' ) ?>
                                            value="no">No</option>
                                    </select>

                                </div>
                              <div class="form-group">
                            <div class="tx-danger description_error"></div>
                            <div class="tx-success form_proccessing"></div>
                        </div>  
                        </section>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Row -->

<script>
function afterload() {}
</script>

<?=$this->endSection()?>

<?=$this->section('scripts')?>
<!-- Specturm-colorpicker js-->
<script src="<?=ASSETS;?>/plugins/spectrum-colorpicker/spectrum.js"></script>
<script src="<?=ASSETS;?>js/jquery.validate.js"></script>
<script>
var form_loading = true;

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}
$(document).ready(function() {
    var form = $("#accountform");

    form.validate({
        ignore: "",
        validateHiddenInputs : true,
        errorPlacement: function errorPlacement(error, element) { 
            error.insertAfter(element);
            error.insertAfter(element.parent('.input-group'));
        },
        rules: {  
        },
        messages: {
        }
    });
    var finishButton = $('.wizard').find('a[href="#finish"]');
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
            var data = form.serialize();
            finishButton.html("<i class='sl sl-icon-reload'></i> Please wait...");
            //form_loading = false;
            $('.description_error').html('');
            var aurl = $('#accountform').attr('action');
            $.post(aurl, data, function(response) {
                if (response.st == 'success') {
                    window.location = "<?=url('Account/billterm')?>"
                } else {
                    finishButton.html("Create Bill Term");
                    form_loading = true;
                    $('.description_error').html(response.msg);
                }
            }).fail(function(response) {
                finishButton.html("Create Account");
                form_loading = true;
                alert('Error');
            });
        }   
    });
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.select2').select2({
		placeholder: 'Choose one',
		searchInputPlaceholder: 'Search',
		 width: '100%'
	});
    $('#showAlpha').spectrum({
        color: 'rgba(23,162,184,0.5)',
        showAlpha: true
    });
    
   
        $('#account').autocomplete({
            serviceUrl: '<?= url('Master/Getdata/search_account') ?>',
            type: 'POST',
            showNoSuggestionNotice: true,
            onSelect: function(suggestion) {
            $('#account').val(suggestion.value);
            $('#account_id').val(suggestion.data);
            
        }
    });
   

});

</script>

<?=$this->endSection()?>