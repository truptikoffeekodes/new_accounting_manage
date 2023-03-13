<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Reporting</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reporting</li>
        </ol>
    </div>
    <div class="d-flex">

        <div class="">
            <a href="#" class="btn ripple btn-secondary navresponsive-toggler mb-0" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
                aria-label="Toggle navigation">
                <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
            </a>
        </div>
    </div>
</div>

<?php
//$jan=get_modnth();
?>
<div class="responsive-background">
    <div class="navbar-collapse collapse show" id="navbarSupportedContent" style="">
        <div class="advanced-search">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <form action="<?=url('reporting/view_bill')?>" class="ajax-form-submit" method="post"
                        enctype="multipart/form-data">
                        <div class="row">
                        
                            <div class="col-lg-3 form-group">
                                <label class="form-label">Type: <span class="tx-danger"></span></label>
                                <div class="input-group">
                                    <select class="form-control select2" id="type" onchange="search_invoice()"
                                        name="type" required>
                                        <option value="">None</option>
                                        <option <?=(@$view_bill['type'] == "sales" ? 'selected' : '')?> value="sales">
                                            Sales</option>
                                        <option <?=(@$view_bill['type'] == "purchase" ? 'selected' : '')?>
                                            value="purchase">Purchase</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 form-group">
                                <label class="form-label">Transaction Type: <span class="tx-danger"></span></label>
                                <div class="input-group">
                                    <select class="form-control select2" id="transaction" onchange="search_invoice()"
                                        name="transaction_type">
                                        <option value="">None</option>
                                        <option <?=(@$view_bill['transaction_type'] == "item_wise" ? 'selected' : '')?>
                                            value="item_wise">Item Wise</option>
                                        <option <?=(@$view_bill['transaction_type'] == "general" ? 'selected' : '')?>
                                            value="general">General</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6" id="bill_div"
                                style="display:<?php !empty($view_bill['bill_no']) ? 'block;' : 'none;'?>">
                                <div class="col-lg-12 form-group">
                                    <label class="form-label">Bill No: <span class="tx-danger"></span></label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="bills" name="bill_no">
                                            <?php if (@$view_bill['bill_no']) {?>
                                            <option selected value="<?=@$view_bill['bill']?>">
                                                <?=@$view_bill['bill_no']?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit">Apply</button>
                            <a href="#" class="btn btn-secondary" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="true" aria-label="Toggle navigation">Reset</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="responsive-background">
    <div class="navbar-collapse collapse show" id="navbarSupportedContent" style="">
        <div class="advanced-search">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table mg-b-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Voucher Type</th>
                                    <th>Date</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(!empty($sales_invoice)) {
                                // echo '<pre>';print_r($sales_invoice);
                                foreach ($sales_invoice as $row) {
                                    ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>
                                    <td>Sales Invoice</td>

                                    <td><?=@$row['date'];?></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <td></td>
                                </tr>
                                <?php
                                }
                            }
                            if (!empty($sales_return)) {
                                // echo '<pre>';print_r($sales_return);
                                foreach ($sales_return as $row) {
                                    ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>
                                    <td>Sales Return</td>

                                    <td><?=@$row['date'];?></td>
                                    <td></td>
                                    <td><?=@$row['net_amount'];?></td>
                                </tr>
                                <?php
                                }
                            } if (!empty($sales_general)) {
                                foreach ($sales_general as $row) { ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>

                                    <?php if ($row['v_type'] == 'general') { ?>
                                    <td>Sales General Invoice</td>
                                    <td><?=@$row['date'];?></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <td></td>
                                    
                                    <?php } else { ?>
                                    
                                    <td>Sales General Return</td>
                                    <td><?=@$row['date'];?></td>
                                    <td></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                }
                            } if(!empty($purchase_invoice)) {
                                // echo '<pre>';print_r($sales_return);
                                foreach ($purchase_invoice as $row) {
                                    ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>
                                    <td>Purchase Invoice</td>
                                    <td><?=@$row['date'];?></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <td></td>
                                </tr>
                                <?php
                                }
                            }
                            if (!empty($purchase_return)) {
                                // echo '<pre>';print_r($sales_return);
                                foreach ($purchase_return as $row) {
                                    ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>
                                    <td>Purchase Return</td>

                                    <td><?=@$row['date'];?></td>
                                    <td></td>
                                    <td><?=@$row['net_amount'];?></td>
                                </tr>
                                <?php
                                }
                            }
                            if (!empty($purchase_general)) { 
                                foreach ($purchase_general as $row) { ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$row['account_name'];?></td>

                                    <?php if (@$row['v_type'] == 'general') { ?>
                                    <td>Purchase General Invoice</td>
                                    <td><?=@$row['date'];?></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <td></td>
                                    <?php } else { ?>
                                    <td>Purchase General Return</td>
                                    <td><?=@$row['date'];?></td>
                                    <td></td>
                                    <td><?=@$row['net_amount'];?></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                }
                            } ?>
                                <tr>
                                    <table class="table mg-b-0" border="1">
                                        <thead>
                                            <tr>
                                                <th>Diffrence</th>
                                                <th>Credit Total</th>
                                                <th>Debit Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php
$credit_total = @$total['salesinvoice_total']+@$total['sgeneralinvoive_total']+@$total['purchaseinvoice_total']+@$total['pgeneralinvoive_total'];
$debit_total = @$total['salesreturn_total']+@$total['sgeneralreturn_total']+@$total['purchasreturn_total']+@$total['pgeneralreturn_total'];
$diffrence = @$credit_total-@$debit_total;
?>
                                                <td><b><?=@$diffrence;?></b></td>
                                                <td><b><?=@$credit_total;?></b></td>
                                                <td><b><?=@$debit_total;?></b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function afterload() {}
</script>

<?=$this->endSection()?>

<?=$this->section('scripts')?>
<script>
// $('.ajax-form-submit').on('submit', function(e) {
//     $('#save_data').prop('disabled', true);
//     $('.error-msg').html('');
//     $('.form_proccessing').html('Please wait...');
//     e.preventDefault();
//     var aurl = $(this).attr('action');
//     $.ajax({
//         type: "POST",
//         url: aurl,
//         data: $(this).serialize(),
//         success: function(response) {
//             if (response.st == 'success') {
//                 $('#fm_model').modal('toggle');
//                 swal("success!", "Your update successfully!", "success");
//                 datatable_load('');
//                 $('#save_data').prop('disabled', false);
//             } else {
//                 $('.form_proccessing').html('');
//                 $('#save_data').prop('disabled', false);
//                 $('.error-msg').html(response.msg);
//             }
//         },
//         error: function() {
//             $('#save_data').prop('disabled', false);
//             alert('Error');
//         }
//     });
//     return false;
// });


function search_invoice() {

    var trans_type = $('#type').val();
    var parti = $('#transaction').val();
    console.log(trans_type)
    console.log(parti)
    if (type != 'undefined' && type != '') {
        $("#bills").select2({
            width: '100%',
            placeholder: 'Select Bill',
            ajax: {
                url: PATH + "Reporting/Getdata/search_bill",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term,
                        trans_type: trans_type,
                        particular: parti
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

}
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#bills').on('select2:select', function(e) {
        var data = e.params.data;

        $('#bill_tb').val(data.table);
    });


    // $("#bill").select2({
    //     width: '80%',
    //     placeholder: 'Type Bank',
    //     ajax: {
    //         url: PATH + "Master/Getdata/search_bank",
    //         type: "post",
    //         allowClear: true,
    //         dataType: 'json',
    //         delay: 250,
    //         data: function(params) {
    //             return {
    //                 searchTerm: params.term // search term
    //             };
    //         },
    //         processResults: function(response) {
    //             return {
    //                 results: response
    //             };
    //         },
    //         cache: true
    //     }
    // });

    // $('#transaction').on('select2:select', function(e) {
    //     var trans = $('#transaction').val();
    //     var type_id = $('#type').val();
    //     var bill_div = document.getElementById("bill_div");


    //     if (type_id != undefined && type_id != '') {
    //         $.post(PATH + "/bank/getdata/search_bill", _data, function(data) {
    //              console.log(data.data);
    //             if (data.st == 'success') {
    //                 $("#bills").select2({
    //                     data: data.data
    //                 });
    //             }
    //         });
    //     }
    // });

    // $('#bills').on('select2:select', function(e) {
    //     var data = e.params.data;

    //     $('#bill_tb').val(data.table);
    // });

});
</script>

<?=$this->endSection()?>