<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Account/add_voucher') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" required="" placeholder="Name"  name="name" value="<?= @$voucher_type['name'] ?>" type="text">
                <input name="id" value="<?= @$voucher_type['id'] ?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Under voucher:</label>
                <select class="form-control select2" id="parent_id" name="parent_id">
                    <?php if (@$voucher_type['parent_id']) { ?>
                        <option value="<?= @$voucher_type['parent_id'] ?>">
                            <?= @$voucher_type['parent_name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Method of numbering: </label>
                <select class="form-control" id="method_of_numbering" name="method_of_numbering" onchange="display_sub(this.value)">
                    <option <?= (@$voucher_type['method_of_numbering'] == "automatic" ? 'selected' : '') ?> value="automatic" selected>Automatic</option>
                    <option <?= (@$voucher_type['method_of_numbering'] == "automatic_manual_override" ? 'selected' : '') ?> value="automatic_manual_override">Automatic(manual Override)</option>
                    <option <?= (@$voucher_type['method_of_numbering'] == "manual" ? 'selected' : '') ?> value="manual">Manual</option>
                    <option <?= (@$voucher_type['method_of_numbering'] == "none" ? 'selected' : '') ?> value="none">None</option>
                </select>
            </div>
            <div class="form-group" id="prevent_duplicate" style="display:<?= @$voucher_type['method_of_numbering'] == 'automatic_manual_override' ? 'block;' : 'none;' ?>">
                <label class="form-label"> Prevent duplicate: </label>
                <select class="form-control select2" name="prevent_duplicate">
                    <option <?= (@$voucher_type['prevent_duplicate'] == "0" ? 'selected' : 'selected') ?> value="0">
                        No</option>
                    <option <?= (@$voucher_type['prevent_duplicate'] == "1" ? 'selected' : '') ?> value="1">
                        Yes</option>
                </select>
            </div>
            <div class="form-group" id="prefix" style="display:<?= @$voucher_type['method_of_numbering'] == 'automatic_manual_override' ? 'block;' : 'none;' ?>">
                <label class="form-label">Prefix : </label>
                <input class="form-control" placeholder="Prefix" name="prefix" value="<?= @$voucher_type['prefix'] ?>" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Set as: <span class="tx-danger">*</span></label>

                <label class="rdiobox"><input name="set_as" <?= (@$voucher_type['set_as'] == "0" ? 'checked' : '') ?> value="0" type="radio" onchange="calculate()">
                    <span>Default</span></label>

                <label class="rdiobox"><input name="set_as" <?= (@$voucher_type['set_as'] == "1" ? 'checked' : '') ?> value="1" type="radio" onchange="calculate()"> <span>Optional</span></label>

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
            </from>
    </div>
</div>
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
                    // window.location = "";
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

    function afterload() {}
    $(document).ready(function() {


        $("#parent_id").select2({
            width: '100%',
            dropdownParent: $('#fm_model'),
            placeholder: 'Voucher Type',
            ajax: {
                url: PATH + "account/Getdata/parent_voucher",
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
    function display_sub(method)
    {
        if(method == "automatic_manual_override")
        {
            $("#prevent_duplicate").show();
            $("#prefix").show();
        }
        else
        {
            $("#prevent_duplicate").hide();
            $("#prefix").hide();
        }
    }
</script>

<?= $this->endSection() ?>