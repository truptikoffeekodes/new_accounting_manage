<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> Dashboard </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Master</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Master/add_warehouse') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            <!-- Row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Name: </b><span class="tx-danger">*</span></label>
                                    <input class="form-control" name="name" value="<?=@$warehouse['name']?>"
                                        placeholder="Enter Name" onkeyup="code_generate(this.value) " required
                                        type="text">
                                    <input name="id" value="<?=@$warehouse['id']?>" type="hidden">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Code: </b><span class="tx-danger">*</span></label>
                                    <input class="form-control" name="code" id="code" value="<?=@$warehouse['code']?>"
                                        placeholder="Enter Code" required="" type="text">
                                </div>
                                
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Warehouse Address:</b> <span class="tx-danger">*</span></label>
                                    <textarea class="form-control" name="address" placeholder="Enter address"
                                        required rows="3"><?=@$warehouse['address']?></textarea>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Pin :</b> <span class="tx-danger">*</span></label>
                                    <input class="form-control" name="pin" onkeypress="return isNumberKey(event)" value="<?=@$warehouse['pin']?>"
                                        placeholder="Enter Pin" required type="text">
                                </div>
                                
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>City :</b> <span class="tx-danger">*</span></label>
                                    <select class="form-control" id="city" required name="city">
                                            <?php if(@$warehouse['city_name']) { ?>
                                            <option value="<?=@$warehouse['city']?>"><?=@$warehouse['city_name']?>
                                            </option>
                                            <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>State :</b> <span class="tx-danger">*</span></label>
                                    <select class="form-control" id="state" name='state'>
                                            <option value="12" selected>Gujarat</option>
                                            <?php if(@$warehouse['state_name']) { ?>
                                            <option value="<?=@$warehouse['state']?>">
                                                <?=@$warehouse['state_name']?>
                                            </option>
                                            <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Country :</b> <span class="tx-danger">*</span></label>
                                    <select class="form-control" id="country" name='country'>
                                            <option value="101" selected>India </option>
                                            <?php if(@$warehouse['country_name']) { ?>
                                            <option value="<?=@$warehouse['country'] ?>">
                                                <?=@$warehouse['country_name'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Area:</b> <span class="tx-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Enter Area" name="area"
                                            value="<?=@$warehouse['area']?>" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Phone :</b></label>
                                    <input class="form-control" name="phone" onkeypress="return isNumberKey(event)" value="<?=@$warehouse['phone']?>"
                                        placeholder="Enter Phone"  type="text">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Whatsapp No:</b> </label>
                                    <input class="form-control" name="whatsapp" onkeypress="return isNumberKey(event)" value="<?=@$warehouse['whatsapp']?>"
                                        placeholder="Enter Whatsapp Number" type="text">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Mobile :</b> <span class="tx-danger"></span></label>
                                    <input class="form-control" name="mobile" onkeypress="return isNumberKey(event)" value="<?=@$warehouse['mobile']?>"
                                        placeholder="Enter Mobile" type="text">
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>E-Mail :</b> </label>
                                    <input class="form-control" name="e_mail" value="<?=@$warehouse['email']?>"
                                        placeholder="Enter E-Mail" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="tx-danger error-msg"></div>
                                <div class="tx-success form_proccessing"></div>
                            </div>
                            <div class="row pt-3">
                                <div class="col-sm-6">
                                    <p class="text-left">
                                        <button class="btn btn-space btn-primary" id="save_data"
                                            type="submit">Submit</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?=$this->endSection() ?>

<?=$this->section('scripts')?>

<script>
$('.select2').select2({
    width: '100%',
    placeholder: "Select Option"
});

$("#country").select2({
        width: '100%',
        placeholder: 'Type Country Name',
        ajax: {
            url: PATH + "Master/Getdata/search_country",
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
            url: PATH + "Master/Getdata/search_state",
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
            url: PATH + "Master/Getdata/search_city",
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


$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wait...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                // $('#fm_model').modal('toggle');
                swal("success!", "Your update successfully!", "success");
                // datatable_load('');?
                // $('#save_data').prop('disabled', false);
                window.location = "<?=url('master/warehouse')?>"
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
</script>

<?= $this->endSection() ?>