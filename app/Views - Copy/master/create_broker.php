<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>


<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Master/add_broker') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label"><b>Name: </b><span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$broker['name']?>" placeholder="Enter Name"
                    onkeyup="code_generate(this.value) " required type="text">
                <input name="id" value="<?=@$broker['id']?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label"><b>Code: </b><span class="tx-danger">*</span></label>
                <input class="form-control" name="code" id="code" value="<?=@$broker['code']?>" placeholder="Enter Code"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label"><b>Address:</b> <span class="tx-danger">*</span></label>
                <textarea class="form-control" name="address" placeholder="Enter address" required=""
                    rows="3"><?=@$broker['address']?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label"><b>Pin :</b> <span class="tx-danger">*</span></label>
                <input class="form-control" name="pin" onkeypress="return isNumberKey(event)"
                    value="<?=@$broker['pin']?>" placeholder="Enter Pin" required="" type="text">
            </div>

            <div class="form-group">
                <label class="form-label"><b>Country :</b> <span class="tx-danger">*</span></label>
                <select class="form-control" id="country" name='country' required>
                    <?php if(@$broker['country']) { ?>
                    <option value="<?=@$broker['country']?>">
                        <?=@$broker['country_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label"><b>State :</b> <span class="tx-danger">*</span></label>
                <select class="form-control" id="state" name='state' required>
                    <?php if(@$broker['state']) { ?>
                    <option value="<?=@$broker['state']?>">
                        <?=@$broker['state_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label"><b>City :</b> <span class="tx-danger">*</span></label>
                <select class="form-control" id="city" name="city" required>
                    <?php if(@$broker['city']) { ?>
                    <option value="<?=@$broker['city']?>">
                        <?=@$broker['city_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label"><b>Mobile :</b> <span class="tx-danger"></span></label>
                <input class="form-control" name="mobile" value="<?=@$broker['mobile']?>" placeholder="Enter Mobile"
                    type="text">
            </div>
            <div class="form-group">
                <label class="form-label"><b>E-Mail :</b> <span class="tx-danger">*</span></label>
                <input class="form-control" name="e_mail" value="<?=@$broker['e_mail']?>" placeholder="Enter E-Mail"
                    required="" type="text">
            </div>

            <div class="form-group">
                <label class="form-label"><b>Brokerage(%) :</b> <span class="tx-danger">*</span></label>
                <input class="form-control" name="brokerage" onkeypress="return isNumberKey(event)"
                    value="<?=@$broker['brokerage']?>" placeholder="Enter Brokerage" required type="text">
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


<script>
$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wait...');
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

    $('.select2').select2({
        width: '100%',
        placeholder: "Select Option"
    });
    $("#state").select2({
        width: '100%',
        dropdownParent: $('#fm_model'),
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

    $("#country").select2({
        width: '100%',
        dropdownParent: $('#fm_model'),
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


    $("#city").select2({
        width: '100%',
        dropdownParent: $('#fm_model'),
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
}
</script>

<?= $this->endSection() ?>