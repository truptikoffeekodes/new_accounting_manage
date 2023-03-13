<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>


<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Master/add_transport') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">
            
            <div class="form-group">
                <label class="form-label">Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="name" value="<?=@$Transport['name']?>" onkeyup="trans_code_generate(this.value)" placeholder="Enter Name"
                    required="" type="text">
                <input value="<?=@$Transport['id']?>" name="id" type="hidden">
            </div>
            
            <div class="form-group">
                <label class="form-label">Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code" value="<?=@$Transport['code']?>" id="trans_code" placeholder="Enter Code"
                    required type="text">
            </div>
            
            <div class="form-group">
                <label class="form-label">Contact: <span class="tx-danger">*</span></label>
                <input class="form-control" onkeypress="return isNumberKey(event)" name="contact" value="<?=@$Transport['contact']?>"  placeholder="Enter Contact No"
                    required type="text">
            </div>
            
            <div class="form-group">
                <label class="form-label">Address: </label>
                <textarea class="form-control" name="address"  placeholder="Enter Address"><?=@$Transport['address']?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Pin: <span class="tx-danger">*</span></label>
                <input type="text" onkeypress="return isNumberKey(event)" class="form-control" name="pin" value="<?=@$Transport['pincode']?>"  placeholder="Enter Address"></input>
            </div>

            <div class="form-group">
                <label class="form-label">Country: <span class="tx-danger">*</span></label>
                <select class="form-control" id="trans_country" name='contry' required> </select>
            </div>

            <div class="form-group">
                <label class="form-label">State: <span class="tx-danger">*</span></label>
                <select class="form-control" id="trans_state" name='state' required> </select>
            </div>

            <div class="form-group">
                <label class="form-label">City: <span class="tx-danger">*</span></label>
                <select class="form-control" id="trans_city" name='city' required> </select>
            </div>

            <div class="row">
                <div class=" col-lg-12 form-group">
                    <div class="form-group">
                        <label class="form-label">Transport ID / GST: </label>
                        <input class="form-control" name="tranid" value="<?=@$Transport['tran_id']?>"
                            placeholder="Enter Tran.ID" required="" type="text">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Transport Mode <span class="tx-danger">*</span></label>
                        <div class="input-group">
                            <select class="form-control select2"  id="category_name"
                                name="tranmode" tabindex="-1" aria-hidden="true">
                                <option label="Select mode">
                                </option>
                                <option <?= (@$Transport['tran_mode'] == "Rail" ? 'selected' : '' ) ?> value="Rail">
                                    Rail
                                </option>
                                <option <?= (@$Transport['tran_mode'] == "Road" ? 'selected' : '' ) ?> value="Road">
                                    Road
                                </option>
                                <option <?= (@$Transport['tran_mode'] == "Air" ? 'selected' : '' ) ?> value="Air">
                                    Air
                                </option>

                            </select>
                        </div>
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
                $('#fm_model').modal('toggle');
                swal("success!", "Your update successfully!", "success");
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
    
    $('.select2').select2({
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