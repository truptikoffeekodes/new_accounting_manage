<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.table thead {
    border: 1px solid #e1e6f1;
}

.table thead tr th {
    text-align: center;
    border: 1px solid #e1e6f1;
}
</style>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GST</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" id="date_submit" enctype="multipart/form-data">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="from"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="to"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Upload JSON :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="json_file" value=""
                                                class="custom-file-input"><label class="custom-file-label"
                                                for="customFile">Choose file</label>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--End Navbar -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b>GSTIN</b></span>
                                    <br>
                                    <b><?=@$gstr2['start_date']?></b> to
                                    <b><?=@$gstr2['end_date']?></b>
                                   <div>
                                    <?php       
                                        $from =date_create($gstr2['start_date']) ;                                         
                                        $to = date_create($gstr2['end_date']);
                                        
                                        // print_r($gstr2['end_date']);exit;

                                    ?>
                                    <b id="start"><?=date_format(@$from,"Y-m-d"); ?></b> to
                                    <b id="end"><?=date_format($to,"Y-m-d");?></b>
                                   </div>
                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php 
                    $total = @$gstr2['purchase'] + @$gstr2['purchase_return'] ;
                ?>

                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                    <div class="card">

                        <div class="card-header" id="headingOne" role="tab">
                            <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                href="#collapseOne" class="collapsed">Total Voucher<label
                                    style="float:right;"><?=$total?></label>
                            </a>
                        </div>

                        <div aria-labelledby="headingOne" class="collapse" id="collapseOne" role="tabpanel" style="">
                            <div class="card-body">
                                <ul class="list-group">
                                    <div id="headOne" role="tab">
                                        <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                            href="#collaOne" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Included in Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['purchase_return']?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headOne" class="collapse" id="collaOne" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Invoices Ready For Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['purchase_return']?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Invoices in Mismatch in Information
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['sale_return']?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="headTwo" role="tab">
                                        <a aria-controls="collaTwo" aria-expanded="false" data-toggle="collapse"
                                            href="#collaTwo" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Uncertain Transactions (Correction Needed)
                                                <span
                                                    class="badge badge-primary badge-pill"><?=(@$gstr2['non_hsn']['data'] ? count($gstr2['non_hsn']['data']) : 0) + (@$gstr2['hsn']['data'] ? count($gstr2['hsn']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <!-- <div aria-labelledby="headTwo" class="collapse" id="collaTwo" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Incuded in HSN/SAC Summary<span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['hsn']['data'] ? count($gstr2['hsn']['data']) : 0 ?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Incomplete Information in HSN/SAC Summary <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['non_hsn']['data'] ? count($gstr2['non_hsn']['data']) : 0 ?></span>
                                            </li>
                                        </ul>
                                    </div> -->

                                    <div id="headThree" role="tab">
                                        <a aria-controls="collaThree" aria-expanded="false" data-toggle="collapse"
                                            href="#collaThree" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Not relevant in this Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=(@$gstr2['relevant_non']['data'] ? count(@$gstr2['relevant_non']['data']) : 0 ) + (@$gstr2['relevant_gst']['data'] ? count(@$gstr2['relevant_gst']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headThree" class="collapse" id="collaThree" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Incomplete Information in HSN/SAC Summary (Correction Needed)
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr2['relevant_non']['data'] ? count($gstr2['relevant_non']['data']) : 0 ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <!-- <div>
                    <h6 class="card-title mb-1">GSTIN</h6>
                    <p class="text-muted card-sub-title"><?=session('gstin')?></p>
                </div> -->

                <div class="table-responsive">
                    <table class="table table mg-b-0">
                        <thead>
                            <tr>
                                <th rowspan="2">Particular</th>
                                <th rowspan="2">Voucher Count</th>
                                <th rowspan="2">Taxable Amount</th>
                                <th colspan="4">Tax Amount</th>
                                <th colspan="4">Input Tax Credit</th>
                                <th rowspan="2">Reconsilation Status</th>
                            </tr>
                            <tr>

                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Tax Amount</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Tax Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <p></p>
                            <tr>
                                <td colspan="12" style="border-bottom:hidden;"><b>To be Reconsiled With the GST
                                        Portal</b></td>
                            </tr>
                            <tr>

                                <td><a  id="b2b" >B2B Invoices - 3,4A</a></td>
                                <td><?=@$gstr2['b2b']['data'] ? count(@$gstr2['b2b']['data']) : 0?></td>
                                <td><?=@$gstr2['b2b']['taxable_amount']?></td>
                                <td><?=@$gstr2['b2b']['igst']?></td>
                                <td><?=@$gstr2['b2b']['cgst']?></td>
                                <td><?=@$gstr2['b2b']['sgst']?></td>
                                <td><?=@$gstr2['b2b']['cess']?></td>
                                <td><?=@$gstr2['b2b']['igst']?></td>
                                <td><?=@$gstr2['b2b']['cgst']?></td>
                                <td><?=@$gstr2['b2b']['sgst']?></td>
                                <td><?=@$gstr2['b2b']['cess']?></td>
                            </tr>
                            <?php
                                $cr_drRTax = @$gstr2['cr_drReg']['igst'] + $gstr2['cr_drReg']['cess'] + $gstr2['cr_drReg']['sgst'] + $gstr2['cr_drReg']['cgst'];
                                $cr_drRInv = @$gstr2['cr_drReg']['igst'] + @$gstr2['cr_drReg']['taxable_amount'] + @$gstr2['cr_drReg']['cgst'] + @$gstr2['cr_drReg']['sgst'] + @$gstr2['cr_drReg']['cess'];
                            ?>
                            <tr>

                                <td>Credit /Debit Notes Regular - 6C</td>
                                <td><?=@$gstr2['cr_drReg']['data'] ? count(@$gstr2['cr_drReg']['data']) : 0 ?></td>
                                <td><?=@$gstr2['cr_drReg']['taxable_amount'] ? '(-)'.@$gstr2['cr_drReg']['taxable_amount'] : 0?>
                                </td>
                                <td><?=@$gstr2['cr_drReg']['igst'] ? '(-)'.@$gstr2['cr_drReg']['igst'] : 0 ?></td>
                                <td><?=@$gstr2['cr_drReg']['cgst'] ? '(-)'.@$gstr2['cr_drReg']['cgst'] : 0?></td>
                                <td><?=@$gstr2['cr_drReg']['sgst'] ? '(-)'.@$gstr2['cr_drReg']['sgst'] : 0 ?></td>
                                <td><?=@$gstr2['cr_drReg']['cess'] ? '(-)'.@$gstr2['cr_drReg']['cess'] : 0?></td>
                                <td><?=@$gstr2['cr_drReg']['igst'] ? '(-)'.@$gstr2['cr_drReg']['igst'] : 0 ?></td>
                                <td><?=@$gstr2['cr_drReg']['cgst'] ? '(-)'.@$gstr2['cr_drReg']['cgst'] : 0?></td>
                                <td><?=@$gstr2['cr_drReg']['sgst'] ? '(-)'.@$gstr2['cr_drReg']['sgst'] : 0 ?></td>
                                <td><?=@$gstr2['cr_drReg']['cess'] ? '(-)'.@$gstr2['cr_drReg']['cess'] : 0?></td>
                            </tr>
                            <tr>
                                <td colspan="12" style="border:hidden;"><b>To be Uploded On the GST Portal</b></td>
                            </tr>
                            <tr>

                                <td>B2C(Small) Invoices -7</td>
                                <td><?=@$gstr1['advance_tax']['total_voucher']?></td>

                            </tr>

                            <tr>
                                <td>Credit/Debit Notes(Registered) -9B</td>

                            </tr>
                            <tr>
                                <td>Credit/Debit Notes(Unregistered) -9B</td>
                            </tr>

                            <tr>
                                <td>Expost Invoices -6A</td>
                            </tr>
                            <tr>
                                <td>Tax Liability(Advanced Received) -11A(1),11A(2)</td>
                            </tr>

                            <tr>
                                <td>Adjustment Of Advances-11B(1),11B(2)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Nil Rated Invoices -8A,8B,8C,8D</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endsection() ?>
<?= $this->section('scripts') ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
$('#b2b').click(function(){

var start = $('#start').text();
var end = $('#end').text();

    $.ajax
    ({ 
        url: PATH + 'Gst/b2binvoice',
        data: {"from": start,"to": end},
        type: 'post',
        success: function(result)
        {

        }
    });
});
</script>
<?= $this->endSection() ?>