<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px!important;
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
                    <label class="form-label">Grey Type: </label>
                    <div class="input-group">
                        <select name='type' id="challan_uom" class="form-control type">
                            <option value='mtr' <?= ( @$grayitem['challan_uom'] == "mtr" ? 'selected' : '' ) ?>>Mtr
                            </option>
                            <option value='pcs' <?= ( @$grayitem['challan_uom'] == "pcs" ? 'selected' : '' ) ?>>PCS
                            </option>
                        </select>
                    </div>
                    
                </div> -->
                <!-- <div class="col-lg-6 form-group">
                    <label class="form-label">Default Cut: </label>
                    <div class="input-group">
                        <input class="form-control" onkeypress="return isNumberKey(event)" onchange="challan_calc()"
                            id="default_cut" placeholder="Enter Value" name="default_cut"
                            value="<?=@$grayitem['default_cut']?>" type="text">
                    </div>
                </div> -->
                <!-- <div class="col-lg-6 form-group">
                    <div class="input-group">
                        <label class="custom-switch mt-4">
                            <input type="checkbox" name="whole_gray" onchange="challan_calc()"
                                <?=@$grayitem['whole_gray'] == 1 ? 'checked' : ''?> class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Send Whole Grey To Mill </span>
                        </label>
                    </div>
                </div> -->
                <?php
                    if(empty(@$grayitem))
                    {
                ?>
                <div class="col-lg-3 form-group">
                    <label class="form-label">Gray: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" onchange="challan_calc()" placeholder="Enter Value" id="val"
                            name="value[]" type="text" required value="">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Send For Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Value" name="milling[]" type="text" value="">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Received From Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Value" onchange="challan_calc()" name="rec_milling[]" type="text" value="">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Finish Cut: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Value" name="finish_cut[]" type="text" value="">
                    </div>
                </div>

                <?php 
                    }
                    else
                    {
                    
                    $itemarray= explode(',',$grayitem['tot_grey']);
                    $cutarray= explode(',',$grayitem['tot_cut']);
                    $millitem= explode(',',$grayitem['send_mill']);
                    $rec_millitem= explode(',',@$grayitem['tot_rec']);
                    $finish_cut= explode(',',@$grayitem['tot_finish_cut']);
                    for($i=0;$i<count($itemarray);$i++)
                    {
                ?>
                <div class="col-lg-3 form-group">
                    <label class="form-label">Gray:  <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly onchange="challan_calc()" placeholder="Enter Value" name="value[]"
                            type="text" required value="<?=$itemarray[$i];?>">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Send For Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" readonly placeholder="Enter Value" name="milling[]" type="text"
                            value="<?=@$millitem[$i]?>">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Received From Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Value" onchange="challan_calc()" name="rec_milling[]" type="text"
                            value="<?=@$rec_millitem[$i]?>">
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <label class="form-label">Finish Cut: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" placeholder="Enter Value" name="finish_cut[]" type="text"
                            value="<?=@$finish_cut[$i]?>">
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="row" id="input"></div>
            <div class="row">
                <div class="">
                    <p class="text-left">
                        <?php if(empty(@$grayitem)) { ?>
                            <button class="btn btn-space btn-primary" type="button" id="addinput">Add</button>
                        <?php } ?>
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
        "<div class='col-lg-3 form-group'><label class='form-label'>Gray:</label><div class='input-group'><input class='form-control' onchange='challan_calc()' placeholder='Enter Value' name='value[]' type='text' value=''></div></div>";
    
    input +=
        "<div class='col-lg-3 form-group'><label class='form-label'>Send For Milling: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' name='milling[]' type='text' value=''></div></div>";
    input +=
        "<div class='col-lg-3 form-group'><label class='form-label'>Received From Milling: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' onchange='challan_calc()' placeholder='Enter Value' name='rec_milling[]' type='text' '></div></div>";
    input +=
        "<div class='col-lg-3 form-group'><label class='form-label'>Finish Cut: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' name='finish_cut[]' type='text'></div></div>";

    $("#input").append(input);
});

$('.ajax-form-price').on('submit', function(e) {
    e.preventDefault();
    var type = $('.type').val();
    //console.log(type);
    // var type = [];
    // $('.type :selected').each(function(i, sel) {
    //     type[$(sel).val()] = $(sel).text();
    // });
    var value = $('input[name="value[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var finish_cut = $('input[name="finish_cut[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        return parseFloat(this.value);
    }).get();

    var send_pcs = 0;
    var milling = $('input[name="milling[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        send_pcs++;
        return parseFloat(this.value);
    }).get();

    var finish_pcs = 0;
    var rec_milling = $('input[name="rec_milling[]"]').map(function() {
        if (this.value == '' || this.value == 0) {
            return 0;
        }
        finish_pcs++;
        return parseFloat(this.value);
    }).get();

    
    var total = 0;
    var mtr_total = 0;
    var meter_total = 0;
    var sum_finish_cut = 0;
    var sum_rec_milling = 0;

    $.each(value, function(index, value) {
        mtr_total = mtr_total + value;
        
    });

    $.each(finish_cut, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        sum_finish_cut = sum_finish_cut + value;
    });

    $.each(rec_milling, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        sum_rec_milling = sum_rec_milling + value;
    });

    $.each(milling, function(index, value) {
        meter_total = meter_total + value;
    });

    
    //console.log(value.length);
    // if (type == 'mtr') {
    //     mtr = total;
    //     pcs = value.length;
    // } else {
    //     mtr = 0;
    //     pcs = total;
    // }
    pcs = value.length;
        
    

    var tr = $('input[name="tr_id"]').val();

    if (milling == 'NaN') {
        milling = '';
    }

    $('.' + tr).closest("tr").find('input[name="mtr[]"]').val(meter_total);
    $('.' + tr).closest("tr").find('input[name="meter[]"]').val(mtr_total);
    $('.' + tr).closest("tr").find('input[name="pcs[]"]').val(send_pcs);
    $('.' + tr).closest("tr").find('input[name="tot_grey[]"]').val(value);
    $('.' + tr).closest("tr").find('input[name="tot_mill[]"]').val(milling);
    $('.' + tr).closest("tr").find('input[name="finish_pcs[]"]').val(finish_pcs);
    // $('.' + tr).closest("tr").find('input[name="challan_uom[]"]').val(type);
    // $('.' + tr).closest("tr").find('input[name="all_gray[]"]').val(whole_gray);

    $('.' + tr).closest("tr").find('input[name="tot_recMill[]"]').val(rec_milling);
    $('.' + tr).closest("tr").find('input[name="tot_finish_cut[]"]').val(finish_cut);

    $('.' + tr).closest("tr").find('input[name="tot_finishcut[]"]').val(sum_finish_cut);
    $('.' + tr).closest("tr").find('input[name="rec_mtr[]"]').val(sum_rec_milling);

    // $('input[name="challanitem_type[]"]').val(type);

    // $('input[name="mtr[]"]').val(mtr);
    // $('input[name="meter[]"]').val(mtr);
    // $('input[name="pcs[]"]').val(pcs);
    // $('input[name="add_item[]"]').val();

    $('#fm_model').modal('toggle');
    $('#save_data').prop('disabled', false);
    calculate();
    return false;
});

function challan_calc() {
    // var value = $('input[name="value[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();

    var send_mill = $('input[name="milling[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    // var cut = $('input[name="challan_cut[]"]').map(function() {
    //     return parseFloat(this.value);
    // }).get();

    var rec_mil = $('input[name="rec_milling[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    // var default_cut = $('input[name="default_cut"]').val();
    
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
    

    if(rec_mil != '' || rec_mil != 'NaN' || rec_mil != 'undefined' || !isNaN(rec_mil)){
        for (j = 0; j < rec_mil.length; j++){
            finish_cut = send_mill[j] -  rec_mil[j];
            if(isNaN(finish_cut)){
                finish_cut = '0';
            }
            $('input[name="finish_cut[]"]').eq(j).val(finish_cut);
        }
    }

}

// $('input[name="whole_gray"]').click(function() {


// });


function afterload() {

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