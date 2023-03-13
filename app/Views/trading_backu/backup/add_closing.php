<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<style>
.ui-datepicker {
    z-index: 1600 !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <form action = "<?=url('trading/add_closing')?>" class="ajax-form-submit" method="post">
            <div class="form-group">
                <div class="error-msg"></div>
            </div>
            <div class="row">
                <div class="col-lg-6 form-group">
                    <label class="form-label">Date: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control fc-datepicker" placeholder ="YYYY/MM/DD" name="date[]" type="text" required
                            value="<?=@$closing['date']?>">
                        <input  name="id[]" type="hidden" value="<?=@$closing['id']?>">
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">Closing: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Closing Amount" name="closing[]" type="text" value="<?=@$closing['closing']?>">
                    </div>
                </div>
            </div>
            <div class="row" id="input"></div>
            <div class="row">

                <div class="">
                    <p class="text-left">
                        <button class="btn btn-space btn-primary" type="button" id="addinput">Add</button>
                        <button class="btn btn-space btn-primary" onclick="calculate()" type="submit">Submit</button>
                        <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                    </p>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
$("#addinput").click(function() {
    var input =
        "<div class='col-lg-6 form-group'><label class='form-label'>Date: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control fc-datepicker' placeholder='Enter Value' name='date[]' type='text' required value=''></div></div><div class='col-lg-6 form-group'><label class='form-label'>Closing: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' name='closing[]' type='text' value=''></div></div>";
    $("#input").append(input);
});

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
    
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $(".select_size").select2({
        width: '100%',
        placeholder: 'Choose One'
    });

    $(".select_color").select2({
        width: '100%',
        placeholder: 'Choose One',
        ajax: {
            url: PATH + "Product/Getdata/GetColor",
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