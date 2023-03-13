<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('User/create_user') ?>" class="ajax-form-submit" method="post"
            enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label"> Name: </label>
                <input class="form-control" name="name" value="<?=@$user['name']?>" placeholder="Enter Name"
                    type="text">
                <input name="id" value="<?=@$user['id']?>" required="" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">User Name: <span class="tx-danger">*</span></label>
                <input class="form-control" name="user_name" value="<?=@$user['user_name']?>" placeholder="Enter Name"
                    required="" type="text">

            </div>
            <div class="form-group">
                <label class="form-label"> Email: </label>
                <input class="form-control" name="email" value="<?=@$user['email']?>" placeholder="Enter Email"
                    type="email">
            </div>
            <div class="form-group">
                <label class="form-label"> Contact:</label>
                <input class="form-control" name="contact" value="<?=@$user['contact']?>"
                    placeholder="Enter Contact No." type="text" maxlength="10" minlength="10" value=""
                    onkeypress="return isNumberKey(event)">
            </div>
            <?php
           
                if(!empty($user['id']))
                {
            ?>
            <div class="form-group">
                <label class="form-label"> Password: <span class="tx-danger">*</span></label>
                <input class="form-control" name="password" value="" id="txtPassword" placeholder="Enter Password" type="password"
                     pattern="(?=.*\d)(?=.*[a-z])(?=.*[@,_])(?=.*[A-Z]).{6,}">
            </div>
            <div class="row" style="justify-content:left;">
                <div id="para" style="margin-left:20px;color:red;margin-bottom:10px;"></div>
                <div id="letter" style="color:red;text-align:left;"></div>
                <div id="upletter" style="color:red;text-align:left;"></div>
                <div id="no" style="color:red;"></div>
                <div id="len" style="color:red;"></div>
            </div>
            <div class="form-group">
                <label class="form-label"> Conform Password: </label>
                <input class="form-control" name="conform_password" id="txtPassword" value="" placeholder="Enter Conform Password"
                    type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[@,_])(?=.*[A-Z]).{6,}">
            </div>
            <?php
                }
                else
                {
            ?>
            <div class="form-group">
                <label class="form-label"> Password: <span class="tx-danger">*</span></label>
                <input class="form-control" name="password" value="" id="txtPassword" placeholder="Enter Password" type="password"
                    required="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[@,_])(?=.*[A-Z]).{6,}">
            </div>
            <div class="row" style="justify-content:left;">
                <div id="para" style="margin-left:20px;color:red;margin-bottom:10px;"></div>
                <div id="letter" style="color:red;text-align:left;"></div>
                <div id="upletter" style="color:red;text-align:left;"></div>
                <div id="no" style="color:red;"></div>
                <div id="len" style="color:red;"></div>
            </div>
            <div class="form-group">
                <label class="form-label"> Conform Password: </label>
                <input class="form-control" name="conform_password" id="txtPassword" value="" placeholder="Enter Conform Password"
                    type="password" required="" pattern="(?=.*\d)(?=.*[a-z])(?=.*[@,_])(?=.*[A-Z]).{6,}">
            </div>
            <?php
                }
                ?>
            


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
var myInput = document.getElementById("txtPassword");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    myInput.onblur = function() {
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
            $("#letter").hide();

        } else {
            $("#letter").html("Lowercase", " ");
            $("#letter").show();
        }

        var symbol = /[@,_,.]/g;
        if (myInput.value.match(symbol)) {
            $("#letter").hide();

        } else {
            $("#letter").html("&nbsp;Symbol", " ");
            $("#letter").show();
        }

        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
            $("#upletter").hide();
        } else {
            $("#upletter").html("&nbsp;Uppercase");
            $("#upletter").show();
        }

        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
            $("#no").hide();
        } else {
            $("#no").html("&nbsp;Number");
            $("#no").show();
        }

        if (myInput.value.length >= 6) {
            $("#len").hide();
        } else {
            $("#len").html("&nbsp;Minimum 6 characters");
            $("#len").show();
        }
    }


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