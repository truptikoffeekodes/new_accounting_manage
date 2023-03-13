<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Milling/add_finish_screen') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="form-label">Item Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name"   placeholder="Enter Item Name"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code"  id="code" required placeholder="Enter Code"
                    type="text">
            </div>
            <div class="form-group">
                <label class="form-label">SKU: <span class="tx-danger">*</span></label>
                <input class="form-control" name="sku"   required placeholder="Enter SKU"
                    type="text">
            </div>
            <input type="hidden" value="milling" name="item_mode">
            <input type="hidden" value="Jobwork" name="item_type">
            <div class="form-group">
                <label class="form-label">Item Group: </label>
                <select class="form-control" id="item_grp" name='item_grp' ></select>
            </div>
            <div class="form-group">
                <label class="form-label">UOM: <span class="tx-danger">*</span></label>
                <select class="form-control select2 uom" placeholder = "Select UOM Data" name="uom[]" multiple required>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Sales Rate: <span class="tx-danger">*</span></label>
                <input class="form-control" name="sales_price" placeholder="Enter Sales Price" type="text" onkeypress="return isDesimalNumberKey(event)">
            </div>

            <div class="form-group">
                <label class="form-label">HSN: <span class="tx-danger">*</span></label>
                <input class="form-control" name="hsn"  placeholder="Enter HSN" required="" type="text">
            </div>

            <div class="form-group">
                <label class="form-label">IGST: <span class="tx-danger">*</span></label>
                <input class="form-control" name="igst"  placeholder="Enter IGST" onkeypress="return isDesimalNumberKey(event)" required type="text">
            </div>
            
            <div class="form-group">
                <div class="tx-danger error-msg"></div>
                <div class="tx-success form_proccessing"></div>
            </div>
            <div class="row pt-3">
                <div class="col-sm-6">
                    <p class="text-left">
                        <button class="btn btn-space btn-primary" id="save_data" type="submit">Submit</button>
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
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wail...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                $('#fm_model').modal('toggle');
                swal("success!", "Your update successfully!", "success");
                $('#save_data').prop('disabled', false);
            } else {
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

function afterload() {
    $("#related_hsn").select2({
        width: '100%',
        dropdownParent: $('#fm_model'),
        placeholder: 'Type HSN Code',
        ajax: {
            url: PATH + "Master/Getdata/related_hsn",
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


    $("#item_grp").select2({
        width: '100%',
        placeholder: 'Type Item Group',
        ajax: {
            url: PATH + "Master/Getdata/search_itemgrp",
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

    $(".uom").select2({
        width: '100%',
        placeholder: 'Select UOM Data',
        ajax: {
            url: PATH + "Master/Getdata/search_uom",
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

    
}

$(document).ready(function() {

   
});
</script>
<?= $this->endSection() ?>