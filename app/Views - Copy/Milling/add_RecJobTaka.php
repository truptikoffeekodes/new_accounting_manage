<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px!important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-taka" method="post" action="<?=url('Milling/insert_RecJobTaka')?>">
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
                            <th style="width: 10px!important;">Check</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody class="tbody" id="taka_detail">
                        <?php                  
                    if(!empty($taka))
                    {   
                        $j = 1;
                        for($i=0;$i<count($taka);$i++){
                            ?>
                        <tr>
                            <td><?=$j++?></td>
                            <!-- <input type="hidden" name="taka_id[]" value="<?=@$taka[$i]['id']?>"> -->
                            <input type="hidden" name="sendJobTaka_ID[<?=@$taka[$i]['taka_no']?>]" value="<?=@$taka[$i]['sendTaka_id']?>">

                            <td><input type="text" name="taka_no[<?=@$taka[$i]['taka_no']?>]" value="<?=@$taka[$i]['taka_no']?>" readonly
                                    class="form-control"></td>
                            
                            <td><input type="text" name="weaver_taka[<?=@$taka[$i]['taka_no']?>]" onkeypress="return isNumberKey(event)"
                                    placeholder="Weaver Taka No" readonly value="<?=@$taka[$i]['weaver_taka']?>"
                                    class="form-control weaver_taka"></td>

                            <td><input type="text" name="qty[<?=@$taka[$i]['taka_no']?>]"  class="form-control rec_qty" value="<?=@$taka[$i]['quantity']?>" onkeyup="calc()"></td>

                            <td style="text-align:center;"><input type="checkbox" name="check[]" onclick = "calc_qty(this)" value="<?=@$taka[$i]['taka_no']?>" <?=(@$taka[$i]['is_sendJob']==1) ? 'checked' : ''?> ></td>
                            <td style="text-align:center;"><input type="text" name="type[<?=@$taka[$i]['taka_no']?>]" readonly value="<?=@$taka[$i]['type']?>" class="form-control"></td>

                        </tr>

                        <?php
                        }
                    }
                    ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <td colspan="2"> </td>
                            <td><input class="form-control" id="total_unit" value="0"readonly></td>
                            <td><input class="form-control" id="total_qty" value="0"readonly></td>
                            <td></td>
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

// var taka_tp=$('.' + tr).closest("tr").find('input[name="taka_tp[]"]').val();
// if(taka_tp != ''){
//     $('#taka_tp').val(taka_tp);
//     calc();
// }

function afterload(){
}

var total = $('#total_qty').val();
var pcs = $('#total_unit').val();

function calc_qty(obj){

   total = parseFloat(total);
   pcs = parseFloat(pcs);
    
   if($(obj).is(':checked')){
       total +=  parseFloat($(obj).closest("tr").find('input[name="qty['+$(obj).val()+']"]').val());
       pcs += 1;
   }else{
       total -= parseFloat($(obj).closest("tr").find('input[name="qty['+$(obj).val()+']"]').val());
       pcs -= 1;
   }
   $('#total_qty').val(total)
   $('#total_unit').val(pcs)
}


// function calc(){
    
//     var qty = $('input[name="quantity[]"]').map(function() {
//         return parseFloat(this.value);
//     }).get();
    
//     var main_taka =0;
//     var rec_qty = $('input[name="rec_qty[]"]').map(function() {
//         if(this.value != ''){
//             main_taka += 1; 
//         }
//         return this.value;
//     }).get();
//    var total_cut = 0;

    
//     var count_taka=0;
//     var grand_qty=0;

//     if(qty != '' || qty != 'NaN' || qty != 'undefined' || !isNaN(qty)){
//         for (j = 0; j < qty.length; j++){
//             var taka_tp = rec_qty[j].split("+");
//             var total_recQty = 0;                
//             if(taka_tp.length > 0){
//                 for (i = 0; i < taka_tp.length; i++){
//                     total_recQty += parseFloat(taka_tp[i]);
//                 }            
//             }
//             count_taka += taka_tp.length - 1;
//             cut = qty[j] -  total_recQty;
            
//             if(isNaN(total_recQty)){
//                 total_recQty = '0';
//             }
//             grand_qty += parseFloat(total_recQty);

//             if(isNaN(cut)){
//                 cut = '0';
//             }
//             $('input[name="taka_cut[]"]').eq(j).val(cut);
//         }
//         if(count_taka == 0){
//             var taka_tp = main_taka    
//         }else{
//             var taka_tp = main_taka +'+'+ count_taka
//         }
//         var cut = $('input[name="taka_cut[]"]').map(function() {
//             if(this.value != ''){
//                 total_cut += parseFloat(this.value); 
//             }
//             return this.value;
//         }).get();


//         $('#taka_tp').val(taka_tp);
//         $('#total_qty').val(grand_qty);
//         $('#total_cut').val(total_cut);
//         $('#main_taka').val(main_taka);
        
//     }
// }


$('.ajax-form-taka').on('submit', function(e) {
    
    var total_qty =  $('#total_qty').val();
    $('.' + tr).closest("tr").find('input[name="recJOB_mtr[]"]').val(total_qty);

    var total_unit =  $('#total_unit').val();
    $('.' + tr).closest("tr").find('input[name="recJOB_taka[]"]').val(total_unit);

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
                $('.' + tr).closest("tr").find('input[name="recJob_ids[]"]').val(response.recJob_ids);
               
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