<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">

    <div class="col-lg-12">
        <form action="<?= url('Master/add_vehicle') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$vehicle['name']?>" onkeypress="" placeholder="Enter Code" required="" type="text">
                
            </div>
            <div class="form-group">
                <label class="form-label">Veicle No.: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code" value="<?=@$vehicle['code']?>" id="code" placeholder="Enter Code" required="" type="text">
                <input name="id" value="<?=@$vehicle['id']?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Notes: </label>
                <input class="form-control" name="note" value="<?=@$vehicle['note']?>" placeholder="Enter Notes"  type="text">
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
function afterload(){
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
                    console.log(response);
                    $('#fm_model').modal('toggle');
                    // swal("success!", "Your update successfully!", "success");
                    // datatable_load('');
                    $('#vehicle').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
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
}
</script>
<?= $this->endSection() ?>