<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Milling/CreatDayBook') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?= @$daybook['name'] ?>"
                    onkeypress="code_generate(this.value)" placeholder="Enter Name" required="" type="text">
                <input name="id" value="<?= @$daybook['id'] ?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Short Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="short_name" value="<?= @$daybook['short_name']?>" id="code"
                    placeholder="Enter ShortName" required="" type="text">
            </div>
            
            <div class="form-group">
                <label class="form-label">Type: <span class="tx-danger">*</span></label>
                <select class="form-control select2" name="type">
                    <option value="Gray" <?= (@$daybook['type'] == 'Gray') ? 'selected' : '' ?>>Gray</option>
                    <option value="Finish" <?= (@$daybook['type'] == 'Finish') ? 'selected' : '' ?>>Finish</option>
                    <option value="Work Job" <?= (@$daybook['type'] == 'send job') ? 'selected' : '' ?>>Send Form Jobwork
                    </option>
                    <option value="Mill" <?= (@$daybook['type'] == 'Mill') ? 'selected' : '' ?>>Mill</option>
                    <option value="Mill Received" <?= (@$daybook['type'] == 'Mill Received') ? 'selected' : '' ?>>Mill Received
                    </option>
                    <option value="rec job" <?= (@$daybook['type'] == 'rec job') ? 'selected' : '' ?>>Received Jobwork
                    </option>

                </select>
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
    console.log('jenith')
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

function afterload() {
    $('select[name="type"]').select2({
        placeholder: "Select Type",
        width: '100%' 
    });

}
</script>
<?= $this->endSection() ?>