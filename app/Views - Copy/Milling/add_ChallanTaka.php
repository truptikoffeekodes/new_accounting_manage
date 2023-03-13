<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">
        <form class="ajax-form-taka" method="post" action="<?=url('Milling/insert_Challantaka')?>">
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
                            <th>Accumulate</th>
                            <th>Cut</th>
                        </tr>
                    </thead>
                    <tbody class="tbody" id="taka_detail">

                        <?php
                   
                    if(!empty($grey_taka))
                    {   $j = 1;
                        for($i=0;$i<count($grey_taka);$i++){
                            ?>

                        <tr>
                            <td><?=$j++?></td>
                            <input type="hidden" name="taka_id[]" value="<?=$grey_taka[$i]['id']?>">
                            <td><input type="text" name="taka_no[]" value="<?=$grey_taka[$i]['taka_no']?>" readonly
                                    class="form-control"></td>
                            <td><input type="text" name="weaver_taka[]" onkeypress="return isNumberKey(event)"
                                    placeholder="Weaver Taka No" value="<?=$grey_taka[$i]['weaver_taka']?>"
                                    class="form-control weaver_taka"></td>
                            <td><input type="text" name="taka_qty[]" onkeypress="return isNumberKey(event)"
                                    onkeyup="calc_accumulate(this)" placeholder="Enter Quantity"
                                    value="<?=$grey_taka[$i]['quantity']?>" class="form-control quantity"></td>
                            <td><input type="text" name="accumulate[]" readonly onkeypress="return isNumberKey(event)"
                                    class="form-control accumulate" value="<?=$grey_taka[$i]['accumulate']?>"></td>
                            <td><input type="text" name="taka_cut[]" onkeypress="return isNumberKey(event)"
                                    onkeyup="calc_cut()" placeholder="Enter Cut" value="<?=$grey_taka[$i]['cut']?>"
                                    class="form-control cut"></td>
                        </tr>
                        <?php
                        }
                        
                    }
                    ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <td colspan="3"> TOTAL</td>
                            <td><input class="form-control" id="total_qty" readonly></td>
                            <td></td>
                            <td><input class="form-control" id="total_cut" readonly></td>
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
<?php 
if(empty($grey_taka)){ ?>

function afterload() {
    var taka = $('.' + tr).closest("tr").find('input[name="taka[]"]').val();
    var last_taka = '<?=$last_taka?>';
    for (i = 1; i <= parseInt(taka); i++) {

        var table_data = "<tr>";
        table_data += '<td>' + i + '</td>';
        table_data += '<input type="hidden" name="taka_id[]" value="">';
        table_data += '<td><input type="text" name="taka_no[]" value="' + last_taka +
            '" readonly class="form-control"></td>';
        table_data +=
            '<td><input type="text" name="weaver_taka[]" onkeypress="return isNumberKey(event)" placeholder="Weaver Taka No" value="" class="form-control weaver_taka"></td>';
        table_data +=
            '<td><input type="text" name="taka_qty[]" onkeypress="return isNumberKey(event)" onkeyup = "calc_accumulate(this)" placeholder="Enter Quantity" value="" class="form-control quantity"></td>';
        table_data +=
            '<td><input type="text" name="accumulate[]" readonly onkeypress="return isNumberKey(event)" class="form-control accumulate"></td>';
        table_data +=
            '<td><input type="text" name="taka_cut[]" onkeypress="return isNumberKey(event)" onkeyup = "calc_cut()" placeholder="Enter Cut" value="" class="form-control cut"></td>';
        table_data += '</tr>';
        $("#taka_detail").append(table_data);
        last_taka++;
    }

    $('.weaver_taka').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.weaver_taka').focus();
            e.preventDefault();
        }
    });

    $('.quantity').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.quantity').focus();
            e.preventDefault();
        }
    });

    $('.cut').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.cut').focus();
            e.preventDefault();
        }
    });

    $('input[name="taka_qty[]"]').keyup(function(e) {
        var total = 0;
        var taka_qty = $('input[name="taka_qty[]"]').map(function() {
            if (this.value == '') {
                return 0;
            }
            return parseFloat(this.value);
            total = total + taka_qty;
        }).get();
    });

    
}

<?php 
}else{?>

function afterload() {

    $('.weaver_taka').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.weaver_taka').focus();
            e.preventDefault();
        }
    });

    $('.quantity').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.quantity').focus();
            e.preventDefault();
        }
    });

    $('.cut').keypress(function(e) {
        if (e.which == 13) {
            $(this).closest('tr').next().find('input.cut').focus();
            e.preventDefault();
        }
    });

    var total_qty = 0;
    var total_cut = 0;

    var taka_qty = $('input[name="taka_qty[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        total_qty += parseFloat(this.value);
        return parseFloat(this.value);
    }).get();
    $('#total_qty').val(total_qty);
    var taka_qty = $('input[name="taka_cut[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        total_cut += parseFloat(this.value);
        return parseFloat(this.value);
    }).get();
    $('#total_cut').val(total_cut);

}
<?php }   ?>

function calc_cut() {
    var cut = $('input[name="taka_cut[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        return parseFloat(this.value);
    }).get();
    var total_cut = 0;
    $.each(cut, function(index, value) {
        if (isNaN(value)) {
            value = 0;
        }

        total_cut = total_cut + value;
    });

    $('#total_cut').val(total_cut);
    $('.' + tr).closest("tr").find('input[name="cut[]"]').val(total_cut);

}

function calc_accumulate(obj) {

    var qty = $(obj).val();
    var prev_accum = $(obj).closest('tr').prev().find('input.accumulate').val();
    // console.log('prev_accum' + prev_accum)
    if (prev_accum == 'undefined' || prev_accum == 'NaN' || isNaN(prev_accum)) {
        prev_accum = 0;
    }

    var accum = 0;
    console.log(accum)
    //$(obj).closest('tr').find('input.accumulate').val(accum);

    var total = 0;
    var taka_qty = $('input[name="taka_qty[]"]').map(function() {
        if (this.value == '') {
            return 0;
        }
        accum = accum + parseFloat(this.value);
        $(this).closest('tr').find('input.accumulate').val(accum);
        return parseFloat(this.value);
    }).get();

    $.each(taka_qty, function(index, value) {

        if (isNaN(value)) {
            value = 0;
        }
        total = total + value;
    });

    $('#total_qty').val(total);
    $('.' + tr).closest("tr").find('input[name="meter[]"]').val(total);
}

$('.ajax-form-taka').on('submit', function(e) {
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

                $('.' + tr).closest("tr").find('input[name="takaTb_id[]"]').val(response.takaTB_id);
                calc_accumulate();
                calc_cut();
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