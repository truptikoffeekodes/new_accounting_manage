<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
       
        <form action="<?= url('company/add_gst/'.$id) ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Gst No: <span class="tx-danger">*</span></label>
                <input class="form-control" readonly value="<?=@$gst['gst_no']?>" type="text">
            </div>

            <div class="form-group">
                <label class="form-label">GST Address: <span class="tx-danger">*</span></label>
                <textarea class="form-control" name="gst_address" value="" placeholder="Enter GST Address"
                    type="text"><?=@$gst['gst_address']?></textarea>
                <input name="id" value="<?=@$id?>" type="hidden">

            </div>

            <div class="form-group">
                <label class="form-label">Country:</label>
                <select class="form-control" id="country" name='country'>
                    <?php if(@$gst['country_name']) { ?>
                    <option value="<?=@$gst['country']?>">
                        <?=@$gst['country_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">State:</label>
                <select class="form-control" id="state" name='state'>
                    <?php if(@$gst['state_name']) { ?>
                    <option value="<?=@$gst['state']?>">
                        <?=@$gst['state_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">City:</label>
                <select class="form-control" id="city" name="city">
                    <?php if(@$gst['city_name']) { ?>
                    <option value="<?=@$gst['city']?>"><?=@$gst['city_name']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Registration Type:</label>
                <select class="form-control select2" name="gst_type">
                    <option value="Regular" <?=@$gst['gst_type'] == 'Regular' ? 'selected' : '' ?>>Regular </option>
                    <option value="Composition" <?=@$gst['gst_type'] == 'Composition' ? 'selected' : '' ?>>Composition </option>
                    <option value="Other" <?=@$gst['gst_type'] == 'Other' ? 'selected' : '' ?>>Other </option>
                </select>
                
            </div>
            <div class="form-group">
                <label class="form-label">Periodicity Of GSTR1:</label>
                <select class="form-control select2" name="gst_period">
                    <option value="Monthly" <?=@$gst['gst_period'] == 'Monthly' ? 'selected' : '' ?>>Monthly </option>
                    <option value="Quarterly" <?=@$gst['gst_period'] == 'Quarterly' ? 'selected' : '' ?>>Quarterly </option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tax Liabilities On Advance Receipts:</label>
                <select class="form-control select2" name="advance_rec">
                    <option value="No" <?=@$gst['advance_rec'] == 'No' ? 'selected' : '' ?>>NO </option>
                    <option value="Yes" <?=@$gst['advance_rec'] == 'Yes' ? 'selected' : '' ?>>YES </option>
                    
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tax Liabilities On Reverse Charge:</label>
                <select class="form-control select2" name="rev_charge">
                    <option value="No" <?=@$gst['rev_charge'] == 'No' ? 'selected' : '' ?>>NO </option>
                    <option value="Yes" <?=@$gst['rev_charge'] == 'Yes' ? 'selected' : '' ?>>YES </option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">e-Way Bill Applicable:</label>
                <select class="form-control select2" name="eway">
                    <option value="No" <?=@$gst['eway'] == 'No' ? 'selected' : '' ?>>NO </option>

                </select>
            </div>
            <?php
                if(!empty($gst['eway_date'])){
                    
                    $fn_date = date_create($gst['eway_date']);
                    $date = date_format($fn_date,'d-m-Y');    
                }
            ?>
            <div class="form-group">
                <label class="form-label">e-Way Bill Applicable From:</label>
                <input class="form-control fc-datepicker" name="eway_date"
                    value="<?=@$date?>"
                     type="text" >
                
            </div>
            <div class="form-group">
                <label class="form-label">Threshold Limit:</label>
                <input class="form-control" value="<?=@$gst['threshold']?>" Placeholder="Enter Threshold Amount" name="threshold" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Applicable For Intrastate:</label>
                <select class="form-control select2" name="intra_state">
                    <option value="No" <?=@$gst['intra_state'] == 'No' ? 'selected' : '' ?>>NO </option>
                    <option value="Yes" <?=@$gst['intra_state'] == 'Yes' ? 'selected' : '' ?>>YES </option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Intrastate Threshold Limit:</label>
                <input class="form-control" value="<?=@$gst['intra_threshold']?>"  Placeholder="Enter Threshold Amount" name="intra_threshold" type="text">
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
$(".select2").select2({
        width: '100%',
    });
function afterload() {
    $('#fm_model').on('shown.bs.modal', function() {
        
        // $('.fc-datepicker').datepicker({
        //     format: "dd/mm/yyyy",
        //     startDate: "01-01-2015",
        //     endDate: "01-01-2020",
        //     todayBtn: "linked",
        //     autoclose: true,
        //     todayHighlight: true,
        //     container: '#fm_model modal-body'
        // });

        $('.fc-datepicker').mask('99-99-9999');
    
    });
    $("#country").select2({
        width: '100%',
        placeholder: 'Type Country Name',
        ajax: {
            url: PATH + "Company/Getdata/search_country",
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

    $("#state").select2({
        width: '100%',
        placeholder: 'Type State',

        ajax: {
            url: PATH + "Company/Getdata/search_state",
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
    
    $("#city").select2({
        width: '100%',
        placeholder: 'Type City',
        ajax: {
            url: PATH + "Company/Getdata/search_city",
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