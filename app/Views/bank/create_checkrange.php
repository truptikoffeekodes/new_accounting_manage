<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('bank/add_checkrange') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label class="form-label">Select Bank :</label>
                    <select class="form-control select2" id="chk_bank" name='bank'>
                        <?php if(@$checkrange['bank_id']) { ?>
                        <option value="<?=@$checkrange['bank_id']?>">
                            <?=@$checkrange['bank_name']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label">From:</label>
                    <input class="form-control" type="text" onchange="calculate()"
                        onkeypress="return isDesimalNumberKey(event)" name="from_range[]"
                        placeholder="Enter Check Range" value="<?=@$checkrange['from_range']?>">
                    <input name="id" value="<?=@$checkrange['id']?>" type="hidden">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">To:</label>
                    <input class="form-control" type="text" onchange="calculate()"
                        onkeypress="return isDesimalNumberKey(event)" name="to_range[]" placeholder="Enter Check Range"
                        value="<?=@$checkrange['to_range']?>">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 form-group">
                    <label class="form-label">Total Check:</label>
                    <input readonly class="form-control" id="total" type="text" name="total" onchange="calculate()"
                        value="<?=@$checkrange['sub_total']?>">
                </div>
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
<?php 
if(isset($id))
{?>
calculate();
<?php } ?>

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

function calculate() {

    var from_range = $('input[name="from_range[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();
    console.log(from_range);

    var to_range = $('input[name="to_range[]"]').map(function() {
        return parseFloat(this.value); // $(this).val()
    }).get();
    if(from_range == 'NaN'){
        from_range = 0;
    }
    if(to_range == 'NaN'){
        to_range = 0;
    }
    var total = 0.0;
    var final_sub = to_range - from_range;
    $('#total').val(final_sub);
  
}

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
                $('#check_no').val(response.checkno)
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

$("#chk_bank").select2({
    width: '100%',
    placeholder: 'Type Bank',
    ajax: {
        url: PATH + "Master/Getdata/search_bank",
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
</script>
<?= $this->endSection() ?>