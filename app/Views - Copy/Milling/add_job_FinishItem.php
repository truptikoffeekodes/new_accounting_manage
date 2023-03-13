<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-price" method="post">
            <div class="form-group">
                <div class="error-msg"></div>
            </div>
            <div class="row">
                <input name="tr_id" type="hidden" value="<?=$tr_id?>">
                <!-- <div class="col-lg-12 form-group">
                    <div class="input-group">
                        <label class="custom-switch mt-4">
                            <input type="checkbox" name="is_sendJOB" onchange="calc_pending()"
                                <?=@$finishitem['is_sendJOB'] == 1 ? 'checked' : ''?> class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Send Whole Finish To JOB </span>
                        </label>
                    </div>
                </div> -->
                <?php
                if(empty(@$jobitem))
                {
                ?>
                
                <div class="col-lg-4 form-group">
                    <label class="form-label">Send For Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly onchange="calc_pending()" placeholder="Enter Value"
                            name="send_job[]" type="text" >
                    </div>
                </div>
                
                <div class="col-lg-4 form-group">
                    <label class="form-label">Received From Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" onkeyup="calc_pending()"  placeholder="Enter Value" id="rec_job"
                            name="rec_job[]" type="text" >
                    </div>
                </div>
            
                <div class="col-lg-4 form-group">
                    <label class="form-label">Pending Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly placeholder="Enter Value" name="pending_job[]" type="text">
                    </div>
                </div>

                <?php
                }  else
                {
                    $send_mtr= explode(',',@$jobitem['tot_send_mtr']);
                    // $rec_mtr= explode(',',@$jobitem['tot_rec_mtr']);
                    // $pending_mtr= explode(',',@$jobitem['tot_pending_mtr']);

                for($i=0;$i<count($send_mtr);$i++)
                {
                ?>
                <div class="col-lg-4 form-group">
                    <label class="form-label">Send For Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly placeholder="Enter Value" onkeyup="calc_pending()" name="send_job[]"
                            type="text" value="<?=@$send_mtr[$i] ?>">
                    </div>
                </div>
                
                <!-- <div class="col-lg-3 form-group">
                    <label class="form-label">Received Before: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" disabled readonly placeholder="Enter Value" onkeyup="calc_pending()" name="[]"
                            type="text" value="">
                    </div>
                </div> -->
                
                <div class="col-lg-4 form-group">
                    <label class="form-label">Pending Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly placeholder="Enter Value" name="pending_job[]" value="<?=$pending_mtr[$i]?>" type="text">
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <label class="form-label">Finish Jobwork: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control"  onkeyup="calc_pending()" placeholder="Enter Value"
                            name="rec_job[]" type="text" required value="">
                    </div>
                </div>

                <?php
                    }
                }
                ?>
                <?php
                // else if(empty($jid)){
                    
                //     $send_mtr= explode(',',@$jobitem['tot_send_mtr']);
                //     $rec_mtr= explode(',',@$jobitem['tot_rec_mtr']);
                //     $pending_mtr= explode(',',@$jobitem['tot_pending_mtr']);

                //     for($i=0;$i<count($send_mtr);$i++)
                //     {
                //     ?>
                <!-- // <div class="col-lg-3 form-group">
                //     <label class="form-label">Send Job: <span class="tx-danger">*</span></label>
                //     <div class="input-group">
                //         <input class="form-control" readonly placeholder="Enter Value"  onkeyup="calc_pending()" name="send_job[]"
                //             type="text" value="<?=@$send_mtr[$i] ?>">
                //     </div>
                // </div>
                
                // <div class="col-lg-3 form-group">
                //     <label class="form-label">Received Before: <span class="tx-danger">*</span></label>
                //     <div class="input-group">
                //         <input class="form-control" disabled readonly placeholder="Enter Value"  name="rec_before[]"
                //             type="text" value="<?=@$rec_mtr[$i]?>">
                //     </div>
                // </div>
                
                // <div class="col-lg-3 form-group">
                //     <label class="form-label">Pending Jobwork: <span class="tx-danger">*</span></label>
                //     <div class="input-group">
                //         <input class="form-control" readonly placeholder="Enter Value"onkeyup="calc_pending()" name="pending_job[]" value="<?=$pending_mtr[$i]?>" type="text">
                //     </div>
                // </div>

                // <div class="col-lg-3 form-group">
                //     <label class="form-label">Finish Jobwork: <span class="tx-danger">*</span></label>
                //     <div class="input-group">
                //         <input class="form-control"  <?=@$pending_mtr[$i] == 0 ? 'readonly' : '' ?> onkeyup="calc_pending(); check_validation()" onkeyup="" placeholder="Enter Value"
                //             name="rec_job[]" type="text" required value="">
                //     </div>
                //     <ul class="parsley-errors-list filled" id="parsley-id-43">
                //         <li class="parsley-required error_msg"></li>
                //     </ul>

                // </div> -->
                //  
                //     }
                // }
                
               
    </div>
    <div class="row" id="input"></div>
    <div class="row">
        <div class="">
            <p class="text-left">
                <?php if(empty(@$jobitem)) { ?>
                <button class="btn btn-space btn-primary" type="button" id="addinput">Add</button>
                <?php } ?>
                <button class="btn btn-space btn-primary" id ="save_btn" onclick="calculate()" type="submit">Submit</button>
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
        "<div class='col-lg-4 form-group'><label class='form-label'>Send For Jobwork: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' onchange='calc_pending()' name='send_job[]' readonly type='text' value=''></div></div>";
    
    input +=
        "<div class='col-lg-4 form-group'><label class='form-label'>Received From Jobwork:</label><div class='input-group'><input class='form-control' onkeyup='calc_pending()' placeholder='Enter Value' name='rec_job[]'  type='text' value=''></div></div>";

    input +=
        "<div class='col-lg-4 form-group'><label class='form-label'>Pending Jobwork:<span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' readonly name='pending_job[]' type='text'></div></div>";

    $("#input").append(input);
});

$('.ajax-form-price').on('submit', function(e) {
    
    e.preventDefault();
    var rec_job = $('input[name="rec_job[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        return parseFloat(this.value);
    }).get();

    var sendJOB_pcs = 0;

    var send_job = $('input[name="send_job[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        sendJOB_pcs++;
        return parseFloat(this.value);
    }).get();
    
    var pendingJOB_pcs=0;
    var pending_job = $('input[name="pending_job[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        pendingJOB_pcs++;
        return parseFloat(this.value);
    }).get();

    var recJOB_pcs =0
    var recJOB = $('input[name="rec_job[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        recJOB_pcs++;
        return parseFloat(this.value);
    }).get();

    var total = 0;
    var sendJOB_total = 0;
    var pendingJOB_total = 0;
    var recJOB_total = 0;

    $.each(send_job, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        sendJOB_total += value;
    });

    $.each(recJOB, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        recJOB_total += value;
    });

    $.each(pending_job, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        pendingJOB_total += value;
    });

    var tr = $('input[name="tr_id"]').val();
    var is_sendJOB = $('input[name="is_sendJOB"]').val();

    $('.' + tr).closest("tr").find('input[name="tot_send_mtr[]"]').val(send_job);
    $('.' + tr).closest("tr").find('input[name="tot_rec_mtr[]"]').val(rec_job);
    $('.' + tr).closest("tr").find('input[name="tot_pending_mtr[]"]').val(pending_job);

    $('.' + tr).closest("tr").find('input[name="tot_pending_pcs[]"]').val(pendingJOB_pcs);
    $('.' + tr).closest("tr").find('input[name="tot_rec_pcs[]"]').val(recJOB_pcs);
    
    // $('.' + tr).closest("tr").find('input[name="challan_uom[]"]').val(type);
    // $('.' + tr).closest("tr").find('input[name="all_gray[]"]').val(whole_gray);

    $('.' + tr).closest("tr").find('input[name="sendJOB_pcs[]"]').val(sendJOB_pcs);
    $('.' + tr).closest("tr").find('input[name="sendJOB_mtr[]"]').val(sendJOB_total);

    $('.' + tr).closest("tr").find('input[name="recJOB_pcs[]"]').val(recJOB_pcs);
    $('.' + tr).closest("tr").find('input[name="recJOB_mtr[]"]').val(recJOB_total);

    $('.' + tr).closest("tr").find('input[name="pendingJOB_pcs[]"]').val(pendingJOB_pcs);
    $('.' + tr).closest("tr").find('input[name="pendingJOB_mtr[]"]').val(pendingJOB_total);

    $('#fm_model').modal('toggle');
    $('#save_data').prop('disabled', false);
    calculate();
    return false;
});


function check_validation(){
    
    var rec_job = $('input[name="rec_job[]"]').map(function() {
        return parseFloat(this.value);
    }).get();   

    var pending = $('input[name="pending_job[]"]').map(function() {
        return parseFloat(this.value);
    }).get();   

    var send_job = $('input[name="send_job[]"]').map(function() {
        return parseFloat(this.value);
    }).get();   

    var rec_before = $('input[name="rec_before[]"]').map(function() {
        return parseFloat(this.value);
    }).get();   
    
    for(i = 0; i < send_job.length; i++) {
        console.log('jenith')
        if((send_job[i] -rec_before[i])  < rec_job[i] ){
            $('#save_btn').prop('disabled', true);
            $('.error_msg').eq(i).html('Finish is less than Pending');
            
        } else{
            $('#save_btn').prop('disabled', false);
            $('.error_msg').eq(i).html('');
            
        }
    }


}
function calc_pending() {
    var rec_pcs = 0;
    var rec_job = $('input[name="rec_job[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        rec_pcs++;
        return parseFloat(this.value);
    }).get();

    var rec_before = $('input[name="rec_before[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        rec_pcs++;
        return parseFloat(this.value);
    }).get();

    var pcs = 0;
    var send_job = $('input[name="send_job[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        pcs++;
        return parseFloat(this.value);
    }).get();

    // var cut = $('input[name="challan_cut[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();

    // var  = $('input[name="finish[]"]').map(function() {
    //     if (this.value == '') {
    //         return 0;
    //     }
    //     return parseFloat(this.value);
    // }).get();

    var pending = 0;
    for(i = 0; i < send_job.length; i++) {
        pending = send_job[i] -rec_before[i]- rec_job[i];
        $('input[name="pending_job[]"]').eq(i).val(pending);
    }
    var pending_pcs = 0 ;
    var pending_job = $('input[name="pending_job[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        pending_pcs++;
        return parseFloat(this.value);
    }).get();


    // if (default_cut != '') {
    //     for (i = 0; i < value.length; i++) {
    //         $('input[name="challan_cut[]"]').eq(i).val(default_cut);
    //     }
    // }
    // if ($('input[name="whole_gray"]').is(':checked')) {
    //     var value = $('input[name="value[]"]').map(function() {
    //         return parseFloat(this.value);
    //     }).get();

    //     for (i = 0; i < value.length; i++) {
    //         // console.log('cut[i]' + cut[i])
    //         // if(cut[i] != '' || !isNaN(cut[i]) || cut[i] != 'NaN' || cut[i] != 'undefined') {
    //         //     calc_meter = value[i] - [i];
    //         // }else{
    //         //     calc_meter = value[i];
    //         // }
    //         $('input[name="milling[]"]').eq(i).val(value[i]);
    //     }
    //     $('input[name="whole_gray"]').val('1');
    // } else {
    //     for (i = 0; i < value.length; i++) {
    //         // console.log('value' + value[i])
    //         $('input[name="milling[]"]').eq(i).val('');
    //     }
    //     $('input[name="whole_gray"]').val('0');
    // }

    // if (rec_mil != '' || rec_mil != 'NaN' || rec_mil != 'undefined' || !isNaN(rec_mil)) {
    //     for (j = 0; j < rec_mil.length; j++) {
    //         finish_cut = send_mill[j] - rec_mil[j];
    //         if (isNaN(finish_cut)) {
    //             finish_cut = '0';
    //         }
    //         $('input[name="finish_cut[]"]').eq(j).val(finish_cut);
    //     }
    // }

}

// $('input[name="whole_gray"]').click(function() {

// });


function afterload() {

    $('input[name="is_sendJOB"]').click(function() {
        var finish = $('input[name="finish[]"]').map(function() {
            if (this.value == '') {
                return 0;
            }
            return parseFloat(this.value);
        }).get();

        if ($('input[name="is_sendJOB"]').is(':checked')) {
            for (i = 0; i < finish.length; i++) {
                $('input[name="send_job[]"]').eq(i).val(finish[i]);
            }
            $('input[name="is_sendJOB"]').val('1');
        }else{
            for (i = 0; i < finish.length; i++) {
                $('input[name="send_job[]"]').eq(i).val(0);
            }
            $('input[name="is_sendJOB"]').val('0');
        }
    });

    // if($('#add_item').val() != ''){
    //     var add_item = $('#add_item').val().split(',');
    //     var uom = $('input[name="challanitem_type"]').val();

    //     $('#challan_uom option[value='+ uom +']').prop('selected', true).change();

    //     $('#val').val(add_item[0]);
    //     for(i=1;i<add_item.length;i++){
    //         var input ="<div class='col-lg-6 form-group'><label class='form-label'>Value: </label><div class='input-group'><input class='form-control' placeholder='Enter Value' value='"+ add_item[i] +"' name='value[]' type='text'  value=''></div></div><div class='col-lg-6 form-group'><label class='form-label'>return: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' name='return[]' type='text' value=''></div></div>";
    //         $("#input").append(input);    
    //     }
    // }

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
            data: function(params){
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