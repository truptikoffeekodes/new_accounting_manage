<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_hsn') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="form-label">HSN Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="hsn_code"  value="<?= @$hsn['hsn']; ?>" placeholder="Enter HSN Code"
                    required="" type="text">
                <input class="form-control" name="id" value="<?= @$hsn['id']; ?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Description: <span class="tx-danger">*</span></label>
                <input class="form-control" name="description" value="<?= @$hsn['description']; ?>" placeholder="Enter Description"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Rate: </label>
                <input class="form-control" name="rate" value="<?= @$hsn['rate']; ?>" placeholder="Enter Rate"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Related Export/Import HSN Code: </label>
                
                <select class="form-control" id="related_hsn" name="related_code">
                    <?php if(@$hsn['related_name']) { ?>
                    <option value="<?=@$hsn['related_code']?>">
                        <?=@$hsn['related_name']?>
                    </option>
                    <?php } ?>
                </select>
                
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
                datatable_load('');
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
    });
}
$(document).ready(function() {

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    
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
});
</script>
<?= $this->endSection() ?>