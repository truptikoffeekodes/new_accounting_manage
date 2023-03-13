<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px!important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-taka" method="post" action="<?=url('Milling/insert_SendJobTaka')?>">
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
                            <th style="text-align:center;">Checkbox<input type="checkbox"  id="checkAll"></th>
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
                            <input type="hidden" name="taka_id[]" value="<?=@$taka[$i]['id']?>">
                            
                            <input type="hidden" name="sendJobTaka_ID[<?=@$taka[$i]['taka_no']?>]" value="<?=@$taka[$i]['sendJobTaka_ID']?>">

                            <td><input type="text" name="taka_no[<?=@$taka[$i]['taka_no']?>]" value="<?=@$taka[$i]['taka_no']?>" readonly
                                    class="form-control"></td>
                            
                            <td><input type="text" name="weaver_taka[<?=@$taka[$i]['taka_no']?>]" onkeypress="return isNumberKey(event)"
                                    placeholder="Weaver Taka No" readonly value="<?=@$taka[$i]['weaver_taka']?>"
                                    class="form-control weaver_taka"></td>

                            <td><input type="text" name="qty[<?=@$taka[$i]['taka_no']?>]"  class="form-control rec_qty" value="<?=@$taka[$i]['received_qty']?>" onkeyup="calc()"></td>

                            <td style="text-align:center;"><input type="checkbox" class="checkItem" name="check[]" onclick = "calc_qty(this)" value="<?=@$taka[$i]['taka_no']?>" <?=(@$taka[$i]['is_sendJob']==1) ? 'checked' : ''?> ></td>
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
                            <td><input class="form-control" id="total_unit" value="<?=@$total_taka?>"readonly></td>
                            <td><input class="form-control" id="total_qty" value="<?=@$total_qty?>"readonly></td>
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


$('#checkAll').click(function () {    
    $(':checkbox.checkItem').prop('checked', this.checked); 

    var total = 0;
    var pcs = 0; 

    $("input[name='check[]']:checked").each(function(){
        var val = parseFloat($(this).closest("tr").find('input[name="qty['+$(this).val()+']"]').val());
        pcs +=1;
        total += val;
    });
    $('#total_qty').val(total)
    $('#total_unit').val(pcs)
});


function calc_qty(obj){
    var total = 0;
    var pcs = 0; 

    $("input[name='check[]']:checked").each(function(){
        var val = parseFloat($(this).closest("tr").find('input[name="qty['+$(this).val()+']"]').val());
        pcs +=1;
        total += val;
    });

    $('#total_qty').val(total)
    $('#total_unit').val(pcs)
}



$('.ajax-form-taka').on('submit', function(e) {
    
    var total_qty =  $('#total_qty').val();
    $('.' + tr).closest("tr").find('input[name="total_qty[]"]').val(total_qty);

    var total_unit =  $('#total_unit').val();
    $('.' + tr).closest("tr").find('input[name="total_taka[]"]').val(total_unit);

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
                $('.' + tr).closest("tr").find('input[name="sendJob_ids[]"]').val(response.sendJob_ids);
               
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