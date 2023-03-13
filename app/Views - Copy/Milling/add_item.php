<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-price" method="post">
            <div class="form-group">
                <div class="error-msg"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 form-group">
                    <label class="form-label">Grey Type: </label>
                    <div class="input-group">
                        <select name='type' id="challan_uom" class="form-control type">
                            <option value='mtr' <?= ( @$grayitem['challan_uom'] == "mtr" ? 'selected' : '' ) ?>>Mtr
                            </option>
                            <option value='pcs' <?= ( @$grayitem['challan_uom'] == "pcs" ? 'selected' : '' ) ?>>PCS
                            </option>
                        </select>
                    </div>
                    <input name="tr_id" type="hidden" value="<?=$tr_id?>">
                </div>
                
                <div class="col-lg-6 form-group">
                    <div class="input-group">
                        <label class="custom-switch mt-4">
                            <input type="checkbox" name="is_send"  <?=@$grayitem['is_send'] == 1 ? 'checked' : ''?> value="0" class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Want Send To Mill </span>
                        </label>
                    </div>
                </div>
                
                <div class="col-lg-6 form-group">    
                </div>

                <div class="col-lg-6 form-group">
                    <label class="form-label">Default Cut: </label>
                    <div class="input-group">
                        <input class="form-control" <?=@$grayitem['is_send'] == 1 ? '' : 'readonly'?> onkeypress="return isNumberKey(event)" onkeyup="challan_calc()" id="default_cut" placeholder="Enter Value"
                            name="default_cut" value="<?=@$grayitem['default_cut']?>" type="text">
                    </div>
                </div>

                <div class="col-lg-6 form-group">
                    <div class="input-group">
                        <label class="custom-switch mt-4">
                            <input type="checkbox" name="whole_gray" <?=@$grayitem['is_send'] == 1 ? '' : 'disabled'?>  <?=@$grayitem['whole_gray'] == 1 ? 'checked' : ''?> class="custom-switch-input" >
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Send Whole Grey To Mill </span>
                        </label>
                    </div>
                </div>
                <?php
                    if(empty(@$grayitem))
                    {
                ?>
                <div class="col-lg-4 form-group">
                    <label class="form-label">Gray: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" onkeyup="challan_calc()" placeholder="Enter Value" id="val" name="value[]" type="text"
                            required value="">
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <label class="form-label">Cut: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" <?=@$grayitem['is_send'] == 1 ? '' : 'readonly'?> placeholder="Enter Value" onkeyup="challan_calc()" name="challan_cut[]" type="text" value="">
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <label class="form-label">Send For Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" <?=@$grayitem['is_send'] == 1 ? '' : 'readonly'?> placeholder="Enter Value" name="milling[]" type="text" value="">
                    </div>
                </div>

                <?php 
                    }
                    else
                    {
                    $itemarray= explode(',',$grayitem['tot_grey']);
                    $cutarray= explode(',',$grayitem['tot_cut']);
                    $millitem= explode(',',$grayitem['send_mill']);
                    for($i=0;$i<count($itemarray);$i++)
                    {
                ?>
                <div class="col-lg-4 form-group">
                    <label class="form-label">Gray: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" onkeyup="challan_calc()" placeholder="Enter Value" name="value[]" type="text" required
                            value="<?=$itemarray[$i];?>">
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <label class="form-label">Cut: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" <?=@$grayitem['is_send'] == 1 ? '' : 'readonly'?> onkeyup="challan_calc()" placeholder="Enter Value" name="challan_cut[]" type="text"
                            value="<?=@$cutarray[$i]?>">
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <label class="form-label">Send For Milling: <span class="tx-danger">*</span></label>
                    <div class="input-group">
                        <input class="form-control" <?=@$grayitem['is_send'] == 1 ? '' : 'readonly'?> placeholder="Enter Value" name="milling[]" type="text"
                            value="<?=@$millitem[$i]?>">
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
        "<div class='col-lg-4 form-group'><label class='form-label'>Gray:</label><div class='input-group'><input class='form-control' onchange='challan_calc()' placeholder='Enter Value' name='value[]' type='text' value=''></div></div>";
    input +=
        "<div class='col-lg-4 form-group'><label class='form-label'>Cut: </label><div class='input-group'><input class='form-control' onchange='challan_calc()' placeholder='Enter Value' name='challan_cut[]' type='text'></div></div>";
    input +=
        "<div class='col-lg-4 form-group'><label class='form-label'>Send For Milling: <span class='tx-danger'>*</span></label><div class='input-group'><input class='form-control' placeholder='Enter Value' name='milling[]' type='text' value=''></div></div>";

    $("#input").append(input);
});

$('input[name="whole_gray"]').change(function() {
    if(this.checked) {
        //Do stuff
        challan_calc();
    }
});

$('input[name="is_send"]').change(function() {
    if(this.checked) {       
        $('input[name="challan_cut[]"]').attr('readonly',false);
        $('input[name="milling[]"]').attr('readonly',false);
        $('input[name="default_cut"]').attr('readonly',false);
        $('input[name="whole_gray"]').attr('disabled',false);
        $('select[name="mill_ac"]').attr('disabled',false);
        $('input[name="mill_date"]').attr('readonly',false);
        $('input[name="is_send"]').val(1);
    }else{
        $('input[name="challan_cut[]"]').attr('readonly',true).val(0);
        $('input[name="milling[]"]').attr('readonly',true).val(0);
        $('input[name="default_cut"]').attr('readonly',true).val(0);
        $('input[name="whole_gray"]').attr('disabled',true).val(0);
        $('select[name="mill_ac"]').attr('disabled',true).prop('selected',false);
        $('input[name="mill_date"]').attr('readonly',true).val('');
        $('input[name="is_send"]').val(0);
    }
});

$('.ajax-form-price').on('submit', function(e) {
    e.preventDefault();
    var type = $('.type').val();
    // console.log(type);
    // var type = [];
    // $('.type :selected').each(function(i, sel) {
    //     type[$(sel).val()] = $(sel).text();
    // });
    var value = $('input[name="value[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var challan_cut = $('input[name="challan_cut[]"]').map(function() {
        if(this.value == ''){
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

    var total = 0;
    var meter_total = 0;
    var cut_total = 0;
    
    $.each(value, function(index, value) {
        total = total + value;
    });

    $.each(milling, function(index, value) {
        meter_total = meter_total + value;
    });

    $.each(challan_cut, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }
        cut_total = cut_total + value;
    });

    cut = cut_total;
    //console.log(value.length);
    if (type == 'mtr') {
        mtr = total;
        pcs = value.length;
    } else {
        mtr = 0;
        pcs = total;
    }

    var tr = $('input[name="tr_id"]').val();    
    
    if (milling == 'NaN') {
        milling = '';
    }

    var default_cut =$('input[name="default_cut"]').val();
    var whole_gray =  $('input[name="whole_gray"]').val();
    var is_send =  $('input[name="is_send"]').val();
    
    if(default_cut == 'NaN' || default_cut == '' || default_cut == 'undefined' ){
        default_cut = '';
    }

    $('.' + tr).closest("tr").find('input[name="mtr[]"]').val(meter_total);
    $('.' + tr).closest("tr").find('input[name="meter[]"]').val(mtr);
    $('.' + tr).closest("tr").find('input[name="pcs[]"]').val(pcs);
    $('.' + tr).closest("tr").find('input[name="cut[]"]').val(cut);
    $('.' + tr).closest("tr").find('input[name="tot_grey[]"]').val(value);
    $('.' + tr).closest("tr").find('input[name="tot_mill[]"]').val(milling);
    $('.' + tr).closest("tr").find('input[name="send_pcs[]"]').val(send_pcs);
    $('.' + tr).closest("tr").find('input[name="tot_challan_cut[]"]').val(challan_cut);
    $('.' + tr).closest("tr").find('input[name="challan_uom[]"]').val(type);

    $('.' + tr).closest("tr").find('input[name="is_send[]"]').val(is_send);

    $('.' + tr).closest("tr").find('input[name="all_gray[]"]').val(whole_gray);
    $('.' + tr).closest("tr").find('input[name="def_cut[]"]').val(default_cut);

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
    var value = $('input[name="value[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var default_cut = $('input[name="default_cut"]').val();

    if (default_cut != '' && default_cut != 0) {
        for (i = 0; i < value.length; i++) {
            $('input[name="challan_cut[]"]').eq(i).val(default_cut);
        }
    }
    var  calc_meter = 0;

    var cut = $('input[name="challan_cut[]"]').map(function() {
        return parseFloat(this.value);
    }).get();
    
    var value = $('input[name="value[]"]').map(function() {
            return parseFloat(this.value);
        }).get();

    if($('input[name="whole_gray"]').is(':checked')) {

        for(i = 0; i < value.length; i++){

            if(isNaN(cut[i]) || cut[i] == 'NaN' || cut[i] == 'undefined') {
                calc_meter = value[i];
                $('input[name="milling[]"]').eq(i).val(value[i]);
            }else{
                calc_meter = value[i] - cut[i];
                $('input[name="milling[]"]').eq(i).val(calc_meter);
            }
        }
        $('input[name="whole_gray"]').val('1');
    }else{
        
        for (i = 0; i < value.length; i++) {
            
            if(isNaN(cut[i]) || cut[i] == 'NaN' || cut[i] == 'undefined' || cut[i] == 0) {
                cut[i] = 0;
                calc_meter = 0;
            }else{
                calc_meter = value[i] - cut[i];
            }            
            $('input[name="milling[]"]').eq(i).val(calc_meter);
        }        
        $('input[name="whole_gray"]').val('0');
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