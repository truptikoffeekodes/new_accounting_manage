<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('master/add_itemgrp') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" onkeyup="itm_grp_code_generate(this.value)" value="<?= @$itemgrp['name']; ?>" placeholder="Enter Name" required="" type="text">
            </div>
            
            <div class="form-group">
                <label class="form-label">Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code" id="itm_grp_code" value="<?= @$itemgrp['code']; ?>" placeholder="Enter Code" required type="text">
                <input name="id" value="<?= @$itemgrp['id']; ?>" type="hidden">
            </div>
           
            <div class="form-group">
                <label class="form-label">Status: <span class="tx-danger">*</span></label>
                <select name="status" class="form-control" required>
                    <option label="Select status">
                    </option>
                    <option <?= ( @$itemgrp['status'] == "1" ? 'selected' : '' ) ?> value="1">
                        Active
                    </option>
                    <option <?= ( @$itemgrp['status'] == "0" ? 'selected' : '' ) ?> value="0">
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

    function itm_grp_code_generate(name) {
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

        $('#itm_grp_code').val(code);
    }

function afterload(){

}
$(document).ready(function() {
    $('#itemgrp').autocomplete({
        serviceUrl: '<?= url('Master/Getdata/parent_itemgrp') ?>',
        type: 'POST',
        showNoSuggestionNotice: true,
        onSelect: function(suggestion) {
            $('#itemgrp').val(suggestion.value);
            $('#itemgrp_id').val(suggestion.data);
        }
    });
    $("#itemgrp").select2({
        width: '100%',
        dropdownParent: $('#fm_model'),
        placeholder: 'Type GL Group',
          ajax: {
            url: PATH + "Master/Getdata/parent_itemgrp",
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

