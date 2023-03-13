<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Company/add_companygrp') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <div class="row">
                
                
                <div class="col-lg-6 form-group">
                    <label class="form-label">Name.: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="name" value="<?=@$companygrp['name']?>" placeholder="Enter Name"
                        required="" type="text">
                </div>

                <div class="col-lg-6 form-group">
                    <label class="form-label">Code: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="code" id="company_grp_code"  value="<?=@$companygrp['code']?>" placeholder="Enter Code"
                        required="" type="text">
                    <input value="<?=@$companygrp['id']?>" name="id" type="hidden">
                </div>


                <div class="col-lg-6 form-group">
                    <label class="form-label">Short Name: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="short" value="<?=@$companygrp['sname']?>" placeholder="Enter Name"
                        required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">Print Name: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="print" value="<?=@$companygrp['pname']?>"
                        placeholder="Enter Name:" required="" type="text">
                </div>
                <div class="col-lg-12 form-group">
                    <label class="form-label">Address: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="add" value="<?=@$companygrp['address']?>"
                        placeholder="Enter Bank Name" required="" type="text">
                </div>
                <div class="col-lg-12 form-group">
                    <label class="form-label">Country: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="Country" value="<?=@$companygrp['country']?>"
                        placeholder="Enter Country" required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">State: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="state" value="<?=@$companygrp['state']?>"
                        placeholder="Enter State" required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">City: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="city" value="<?=@$companygrp['city']?>" placeholder="Enter City:"
                        required="" type="text">
                </div>
                <div class="col-lg-4 form-group">
                    <label class="form-label">PIN: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="pin" value="<?=@$companygrp['pin']?>" placeholder="Enter PIN"
                        required="" type="text">
                </div>
                <div class="col-lg-4 form-group">
                    <label class="form-label">Phone: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="phone" value="<?=@$companygrp['phone']?>"
                        placeholder="Enter Phone" required="" type="text">
                </div>
                <div class="col-lg-4 form-group">
                    <label class="form-label">Fax: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="fax" value="<?=@$companygrp['fax']?>" placeholder="Enter Fax"
                        required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">Email: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="email" value="<?=@$companygrp['email']?>"
                        placeholder="Enter Email" required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">Web URL: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="URL" value="<?=@$companygrp['weburl']?>" placeholder="Enter URL"
                        required="" type="text">
                </div>
                <div class="col-lg-6 form-group">
                    <label class="form-label">Slogan: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="slogan" value="<?=@$companygrp['slogan']?>"
                        placeholder="Enter Slogan" required="" type="text">
                </div>

                <div class="col-lg-6 form-group">
                    <label class="form-label">Notes: <span class="tx-danger">*</span></label>
                    <input class="form-control" name="note" value="<?=@$companygrp['notes']?>" placeholder="Enter Note"
                        required="" type="text">
                </div>


                <div class="col-lg-12 form-group">
                    <label class="form-label">Status: <span class="tx-danger">*</span></label>
                    <select class="form-control" name="status" tabindex="-1" aria-hidden="true">
                        <option label="Choose one">
                        </option>
                        <option <?= (@$companygrp['status'] == "1" ? 'selected' : '' ) ?> value="1">
                            Active
                        </option>
                        <option <?= (@$companygrp['status'] == "0" ? 'selected' : '' ) ?> value="0">
                            Inactive
                        </option>
                    </select>
                    <label class="form-label">Savelogo: <span class="tx-danger">*</span></label>
                    <select class="form-control" name="slogo" tabindex="-1" aria-hidden="true">
                        <option label="Choose one">
                        </option>
                        <option <?= (@$companygrp['savelogo'] == "1" ? 'selected' : '' ) ?> value="1">
                            Yes
                        </option>
                        <option <?= (@$companygrp['savelogo'] == "0" ? 'selected' : '' ) ?> value="0">
                            No
                        </option>
                    </select>
                </div>
                <div class="col-lg-8 form-group">
                    <div class="form-group">
                        <label class="">Logo Pic</label>
                        <input type="file" name="company_logo" value="" data-default-file="" class="dropify"
                            data-height="100" />
                    </div>
                </div>
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

<link href="<?=ASSETS; ?>/plugins/fileuploads/css/fileupload.css" rel="stylesheet" type="text/css" />
<!--Fileuploads js-->
<script src="<?=ASSETS; ?>/plugins/fileuploads/js/fileupload.js"></script>
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
                //$('#fm_model').modal('toggle');
                //swal("success!", "Your update successfully!", "success");
                window.location = "<?=url('Company/company_grp')?>"
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
       $('.select2').select2({
        placeholder: 'Choose one',
        searchInputPlaceholder: 'Search',
        width: '100%'
    });
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (2M max).'
        }
    });
}
</script>
<?= $this->endSection() ?>