<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_bank') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="form-label">Bank Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$bank['name']?>" onkeyup="code_generate(this.value)" placeholder="Enter Bank Name" required=""
                    type="text">
            </div>
            
            <div class="form-group">
                <label class="form-label">IFSC: <span class="tx-danger">*</span></label>
                <input class="form-control" name="ifsc" value="<?=@$bank['ifsc']?>"  placeholder="Enter IFSC" required=""
                    type="text">
                <input  name="id" value="<?=@$bank['id']?>" type="hidden">
                    
            </div>
            
            <div class="form-group">
                <label class="form-label">Branch Name:</label>
                <input class="form-control" name="branch_name" value="<?=@$bank['branch_name']?>" placeholder="Enter Branch Name"
                    required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">AC No:</label>
                <input class="form-control" name="ac_no" onkeypress="return isDesimalNumberKey(event)" value="<?=@$bank['ac_no']?>" placeholder="Enter Account Number"
                    required="" type="text">
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
                    // datatable_load('');
                    window.location = '';
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