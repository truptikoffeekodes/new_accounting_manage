<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-taka" method="post" action="<?=url('Milling/insert_MillSale_Rettaka')?>">
            <div class="form-group">
                <div class="error-msg"></div>
            </div>
            <input name="tr_id" type="hidden" value="<?=$tr_id?>">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Taka</th>
                            <th>Weaver Taka</th>
                            <th>Quantity</th>
                            <th>Checkbox</th>
                        </tr>
                    </thead>
                    <tbody class="tbody" id="taka_detail">
                    <?php
                    if(!empty($sale_taka))
                    {
                        $total_qty=0;
                        $total_pcs=0;
                        $j = 1;
                        for($i=0;$i<count($sale_taka);$i++){
                            ?>
                        <tr>
                            <td><?=$j++?></td>
                            <input type="hidden" name="taka_id[<?=$sale_taka[$i]['taka_no']?>]" value="<?=$sale_taka[$i]['id']?>">
                            
                            <td><input type="text" name="taka_no[<?=$sale_taka[$i]['taka_no']?>]" value="<?=$sale_taka[$i]['taka_no']?>" readonly
                                    class="form-control"></td>
                            
                            <td><input type="text" name="weaver_taka[<?=$sale_taka[$i]['taka_no']?>]" onkeypress="return isNumberKey(event)"
                                    placeholder="Weaver Taka No" readonly value="<?=$sale_taka[$i]['weaver_taka']?>"
                                    class="form-control weaver_taka"></td>
                                
                            <td><input type="text" name="taka_qty[<?=$sale_taka[$i]['taka_no']?>]" readonly onkeypress="return isNumberKey(event)"
                                    onkeyup="calc_accumulate(this)"  placeholder="Enter Quantity"
                                    value="<?=$sale_taka[$i]['quantity']?>" class="form-control taka_qty"></td>
                            
                            <td style="text-align:center;"><input type="checkbox" onclick="calc_qty(this)" value="<?=$sale_taka[$i]['taka_no']?>" name="check[]" <?=(@$sale_taka[$i]['is_return']==1)  ? 'checked' : ''?> ></td>
                            
                        </tr>
                        <?php
                            // if($sale_taka[$i]['is_send_mill']==1){
                            //     $total_qty += $sale_taka[$i]['quantity'] -$sale_taka[$i]['cut'];
                            //     $total_pcs  += 1; 
                            // }
                        }            
                    }
                    ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <td colspan="2"> TOTAL</td>
                            <td><input class="form-control" id="total_pcs"  name="total_pcs" value = "<?=@$total_taka?>" readonly></td>
                            <td><input class="form-control" id="total_qty"  name="total_qty" value = "<?=@$total_meter?>" readonly></td>
                        </tr>
                    <tfooter>
                </table>
            </div>
            <div class="row">
                <p class="error-msg tx-danger"></p>
            </div>
            <div class="row" style="margin:10px 0px 0px 0px;">
                <p class="text-right">
                    <button class="btn btn-space btn-primary" type="submit">Submit</button>
                    <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
var tr = $('input[name="tr_id"]').val();
console.log(tr);
<?php 
if(empty($sale_taka)){ ?>

function afterload() {
  
}

<?php 
}else{?>

function afterload() {


}
<?php }   ?>

var total = parseInt($('#total_qty').val());
var pcs = parseInt($('#total_pcs').val());;


function calc_qty(obj){
   
    if($(obj).is(':checked')){
        total +=  parseFloat($(obj).closest("tr").find('input[name="taka_qty['+$(obj).val()+']"]').val());
        pcs += 1;
    }else{
        total -= parseFloat($(obj).closest("tr").find('input[name="taka_qty['+$(obj).val()+']"]').val());
        pcs -= 1;
    }
    $('#total_qty').val(total)
    $('#total_pcs').val(pcs)
}

  

$('.ajax-form-taka').on('submit', function(e) {
    var total_qty =   $('#total_qty').val();
    var total_pcs =   $('#total_pcs').val();
    
    $('.' + tr).closest("tr").find('input[name="ret_taka[]"]').val(total_pcs);
    $('.' + tr).closest("tr").find('input[name="ret_meter[]"]').val(total_qty);

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
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.' + tr).closest("tr").find('input[name="ret_takaTb_ids[]"]').val(response.takaTB_id);
                $('.' + tr).closest("tr").find('input[name="saleTakaTb_ids[]"]').val(response.saleTakaID);
                $('.' + tr).closest("tr").find('input[name="need_toDelete[]"]').val(response.need_toDelete);
                calculate();
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