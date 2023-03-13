<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
    <form action="<?= url('Master/createuom') ?>"  class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name"  value="<?= @$uom['name']; ?>"  placeholder="Enter Name"
                    required type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Short Name: <span class="tx-danger">*</span></label>
                <input class="form-control" id="uom_code" name="code" value="<?= @$uom['code']; ?>"  placeholder="Enter Code"
                    required="" type="text">
                <input name="id" value="<?= @$uom['id']; ?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Descimal Digits: <span class="tx-danger">*</span></label>
                <input class="form-control" name="decimal" value="<?= @$uom['decimal_digit']; ?>" onkeypress="return isNumberKey(event)" placeholder="Enter Decimal Digit"
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
    $('.form_proccessing').html('Please wai...');
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
                datatable_load('');
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

function uom_code_generate(name) {
        var year = new Date().getFullYear();
        var substr = name.substring(0, 3)

        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var charactersLength = characters.length;
        var random = '';

        for (var i = 0; i < 4; i++) {
            random += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        var join = substr.concat(year);
        var finalstr = join.concat(random);

        var code = finalstr.toUpperCase();

        $('#uom_code').val(code);
    }

function afterload() {

}
</script>
<?= $this->endSection() ?>