<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_screenseries') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code" value="<?=@$screenseries['code']?>" placeholder="Enter Code" required=""
                    type="text">
                <input  name="id" value="<?=@$screenseries['id']?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$screenseries['name']?>" placeholder="Enter Name" required=""
                    type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Notes: <span class="tx-danger">*</span></label>
                <input class="form-control" name="notes" value="<?=@$screenseries['notes']?>" placeholder="Enter Notes"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Status: <span class="tx-danger">*</span></label>
                <select class="form-control" tabindex="-1" name="status"aria-hidden="true">
                    <option label="Select status">
                    </option>
                    <option <?=@$screenseries['status'] == 1 ? 'Selected' : ''  ?> value="1">
                        Active
                    </option>
                    <option <?=@$screenseries['status'] == 0 ? 'Selected' : ''  ?> value="0">
                        InActive
                    </option>
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
</script>
<?= $this->endSection() ?>