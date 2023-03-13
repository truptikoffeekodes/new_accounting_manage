<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>


<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Master/add_transport') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$Transport['name']?>"
                    onkeyup="trans_code_generate(this.value)" placeholder="Enter Name" required type="text">
                <input value="<?=@$Transport['id']?>" name="id" type="hidden">
            </div>

            <div class="form-group">
                <label class="form-label">Code: </label>
                <input class="form-control" name="code" value="<?=@$Transport['code']?>" id="trans_code"
                    placeholder="Enter Code" type="text">
            </div>

            <div class="form-group">
                <label class="form-label">Contact: </label>
                <input class="form-control" onkeypress="return isNumberKey(event)" name="contact"
                    value="<?=@$Transport['contact']?>" placeholder="Enter Contact No" type="text">
            </div>

            <div class="form-group">
                <label class="form-label">Address: </label>
                <textarea class="form-control" name="address"
                    placeholder="Enter Address"><?=@$Transport['address']?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Pin: </label>
                <input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="pin"
                    value="<?=@$Transport['pincode']?>" placeholder="Enter Address"></input>
            </div>

            <div class="form-group">
                <label class="form-label">Country:</label>
                <select class="form-control" id="trans_country" name='country'>
                    <?php if(@$Transport['country_name']) { ?>
                    <option value="<?=@$Transport['country'] ?>">
                        <?=@$Transport['country_name'] ?>
                    </option>
                    <?php }else{ ?>
                    <option value="101" selected>India </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">State:</label>
                <select class="form-control" id="trans_state" name='state'>
                    <?php if(@$Transport['state_name']) { ?>
                    <option value="<?=@$Transport['state'] ?>">
                        <?=@$Transport['state_name'] ?>
                    </option>
                    <?php }else{ ?>
                    <option value="12" selected>Gujarat</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">City: </label>
                <select class="form-control" id="trans_city" name='city'>
                    <?php if(@$Transport['city_name']) { ?>
                    <option value="<?=@$Transport['city'] ?>">
                        <?=@$Transport['city_name'] ?>
                    </option>
                    <?php }?>
                </select>
            </div>

            <div class="row">
                <div class=" col-lg-12 form-group">
                    <div class="form-group">
                        <label class="form-label">Transport ID / GST: </label>
                        <input class="form-control" name="tranid" onkeyup="this.value = this.value.toUpperCase();" value="<?=@$Transport['tran_id']?>"
                            placeholder="Enter Tran.ID" type="text">
                    </div>

                    <div class="form-group">
                        <div class="tx-danger error-msg"></div>
                        <div class="tx-success form_proccessing"></div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <input type="submit" class="btn ripple btn-primary" id="save_data " name="submit"
                            value="Submit">
                    </div>
                </div>
            </div>
            <from>
    </div>
</div>


<script>
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
                //console.log(response);
                $('#fm_model').modal('toggle');
                $('#transport').append('<option selected value="'+response.id+'">'+response.data.name+'</option>');
                // swal("success!", "Your update successfully!", "success");
                // datatable_load('');
                $('#save_data').prop('disabled', false);
            } else {
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                //datatable_load('');
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


function trans_code_generate(name) {
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

    $('#trans_code').val(code);
}

function afterload() {

    $('select[name="tranmode"]').select2({
        width: '100%',
        placeholder: "Select Option"
    });

    $("#trans_state").select2({
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
                    country: $('#trans_country').val()
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

    $("#trans_city").select2({
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
                    state: $('#trans_state').val()
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

    $("#trans_country").select2({
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
}
</script>
<?= $this->endSection() ?>