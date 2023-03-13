<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

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
            <form method="post" id="date_submit">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                                    <b id="start_date"><?=$gstr1['start_date']?></b> to
                                    <b id="end_date"><?=$gstr1['end_date']?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php 
                    $total = @$gstr1['sales'] + @$gstr1['sale_return'] +(@$gstr1['non_hsn']['data'] ? count($gstr1['non_hsn']['data']) : 0) + (@$gstr1['hsn']['data'] ? count($gstr1['hsn']['data']) : 0 ) + (@$gstr1['relevant_non']['data'] ? count(@$gstr1['relevant_non']['data']) : 0 ) + (@$gstr1['relevant_gst']['data'] ? count(@$gstr1['relevant_gst']['data']) : 0 );
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
                                                    class="badge badge-primary badge-pill"><?=@$gstr1['sales'] + @$gstr1['sale_return']?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headOne" class="collapse" id="collaOne" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;" onclick="goto_url('sales')" >Sale</a>
                                                <span class="badge badge-primary badge-pill"><?=$gstr1['sales']?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;" onclick="goto_url('creditnote')" >  Sale Return </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=$gstr1['sale_return']?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="headTwo" role="tab">
                                        <a aria-controls="collaTwo" aria-expanded="false" data-toggle="collapse"
                                            href="#collaTwo" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Included HSN/SAC Summary
                                                <span class="badge badge-primary badge-pill"><?=(@$gstr1['non_hsn']['data'] ? count($gstr1['non_hsn']['data']) : 0) + (@$gstr1['hsn']['data'] ? count($gstr1['hsn']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headTwo" class="collapse" id="collaTwo" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;">Incuded in HSN/SAC Summary</a><span
                                                    class="badge badge-primary badge-pill"><?=@$gstr1['hsn']['data'] ? count($gstr1['hsn']['data']) : 0 ?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;"> Incomplete Information in HSN/SAC Summary </a><span
                                                    class="badge badge-primary badge-pill"><?=@$gstr1['non_hsn']['data'] ? count($gstr1['non_hsn']['data']) : 0 ?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="headThree" role="tab">
                                        <a aria-controls="collaThree" aria-expanded="false" data-toggle="collapse"
                                            href="#collaThree" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Not relevant in this Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=(@$gstr1['relevant_non']['data'] ? count(@$gstr1['relevant_non']['data']) : 0 ) + (@$gstr1['relevant_gst']['data'] ? count(@$gstr1['relevant_gst']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headThree" class="collapse" id="collaThree" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;">Non Gst Transaction </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr1['relevant_non']['data'] ? count($gstr1['relevant_non']['data']) : 0 ?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;"> Transaction Of Other GST Return </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr1['relevant_gst']['data'] ? count(@$gstr1['relevant_gst']['data']) : 0 ?></span>
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
                
                <div class="table-responsive">
                    <table class="table table mg-b-0">
                        <thead>
                            <tr>
                                <th>SI NO.</th>
                                <th>Particular</th>
                                <th>Voucher Count</th>
                                <th>Taxable Amount</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Amount</th>
                                <th>Tax Amount</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>1</th>
                                <td>B2B Invoices -4A,4B,4C,6B,6C</td>
                                <td><?=count(@$gstr1['b2b']['data'])?></td>
                                <td><?=@$gstr1['b2b']['taxable_amount']?></td>
                                <td><?=@$gstr1['b2b']['igst']?></td>
                                <td><?=@$gstr1['b2b']['cgst']?></td>
                                <td><?=@$gstr1['b2b']['sgst']?></td>
                                <td><?=@$gstr1['b2b']['cess']?></td>
                                <td><?=@$gstr1['b2b']['igst'] + $gstr1['b2b']['cess'] + $gstr1['b2b']['sgst'] + $gstr1['b2b']['cgst']?>
                                </td>
                                <td><?=@$gstr1['b2b']['igst'] + @$gstr1['b2b']['taxable_amount'] + @$gstr1['b2b']['cgst'] + @$gstr1['b2b']['sgst'] + @$gstr1['b2b']['cess']?>
                                </td>
                            </tr>
                            <tr>
                                <th>2</th>
                                <td>B2C(Large) Invoices -5A,5B</td>
                                <td><?=@$gstr1['b2cLarge']['data'] ? count(@$gstr1['b2cLarge']['data']) : '0 '?></td>
                                <td><?=@$gstr1['b2cLarge']['taxable_amount']?></td>
                                <td><?=@$gstr1['b2cLarge']['igst']?></td>
                                <td><?=@$gstr1['b2cLarge']['cgst']?></td>
                                <td><?=@$gstr1['b2cLarge']['sgst']?></td>
                                <td><?=@$gstr1['b2cLarge']['cess']?></td>
                                <td><?=@$gstr1['b2cLarge']['igst'] + $gstr1['b2cLarge']['cess'] + $gstr1['b2cLarge']['sgst'] + $gstr1['b2cLarge']['cgst']?>
                                </td>
                                <td><?=@$gstr1['b2cLarge']['igst'] + @$gstr1['b2cLarge']['taxable_amount'] + @$gstr1['b2cLarge']['cgst'] + @$gstr1['b2cLarge']['sgst'] + @$gstr1['b2cLarge']['cess']?>
                                </td>
                            </tr>
                            <tr>
                                <th>3</th>
                                <td>B2C(Small) Invoices -7</td>
                                <td><?=@$gstr1['b2cSmall']['data'] ? count(@$gstr1['b2cSmall']['data']) : 0 ?></td>
                                <td><?=@$gstr1['b2cSmall']['taxable_amount']?></td>
                                <td><?=@$gstr1['b2cSmall']['igst']?></td>
                                <td><?=@$gstr1['b2cSmall']['cgst']?></td>
                                <td><?=@$gstr1['b2cSmall']['sgst']?></td>
                                <td><?=@$gstr1['b2cSmall']['cess']?></td>
                                <td><?=@$gstr1['b2cSmall']['igst'] + $gstr1['b2cSmall']['cess'] + $gstr1['b2cSmall']['sgst'] + $gstr1['b2cSmall']['cgst']?>
                                </td>
                                <td><?=@$gstr1['b2cSmall']['igst'] + @$gstr1['b2cSmall']['taxable_amount'] + @$gstr1['b2cSmall']['cgst'] + @$gstr1['b2cSmall']['sgst'] + @$gstr1['b2cLarge']['cess']?>
                                </td>
                            </tr>
                            <?php
                                $cr_drRTax = @$gstr1['cr_drReg']['igst'] + $gstr1['cr_drReg']['cess'] + $gstr1['cr_drReg']['sgst'] + $gstr1['cr_drReg']['cgst'];
                                $cr_drRInv = @$gstr1['cr_drReg']['igst'] + @$gstr1['cr_drReg']['taxable_amount'] + @$gstr1['cr_drReg']['cgst'] + @$gstr1['cr_drReg']['sgst'] + @$gstr1['cr_drReg']['cess'];
                            ?>
                            <tr>
                                <th>4</th>
                                <td>Credit/Debit Notes(Registered) -9B</td>
                                <td><?=@$gstr1['cr_drReg']['data'] ? count(@$gstr1['cr_drReg']['data']) : 0 ?></td>
                                <td><?=@$gstr1['cr_drReg']['taxable_amount'] ? '(-)'.@$gstr1['cr_drReg']['taxable_amount'] : 0?>
                                </td>
                                <td><?=@$gstr1['cr_drReg']['igst'] ? '(-)'.@$gstr1['cr_drReg']['igst'] : 0 ?></td>
                                <td><?=@$gstr1['cr_drReg']['cgst'] ? '(-)'.@$gstr1['cr_drReg']['cgst'] : 0?></td>
                                <td><?=@$gstr1['cr_drReg']['sgst'] ? '(-)'.@$gstr1['cr_drReg']['sgst'] : 0 ?></td>
                                <td><?=@$gstr1['cr_drReg']['cess'] ? '(-)'.@$gstr1['cr_drReg']['cess'] : 0?></td>
                                <td><?=@$cr_drRTax ? '(-)'.@$cr_drRTax : 0 ?></td>
                                <td><?=@$cr_drRInv ? '(-)'.@$cr_drRInv : 0 ?></td>
                            </tr>
                            <tr>
                                <th>5</th>
                                <td>Credit/Debit Notes(Unregistered) -9B</td>
                                <td><?=@$gstr1['cr_drUnReg']['data'] ? count(@$gstr1['cr_drUnReg']['data']) : '0'?></td>
                                <td><?=@$gstr1['cr_drUnReg']['taxable_amount']?></td>
                                <td><?=@$gstr1['cr_drUnReg']['igst']?></td>
                                <td><?=@$gstr1['cr_drUnReg']['cgst']?></td>
                                <td><?=@$gstr1['cr_drUnReg']['sgst']?></td>
                                <td><?=@$gstr1['cr_drUnReg']['cess']?></td>
                                <td><?=@$gstr1['cr_drUnReg']['igst'] + $gstr1['cr_drUnReg']['cess'] + $gstr1['cr_drUnReg']['sgst'] + $gstr1['cr_drUnReg']['cgst']?>
                                </td>
                                <td><?=@$gstr1['cr_drUnReg']['igst'] + @$gstr1['cr_drUnReg']['taxable_amount'] + @$gstr1['cr_drUnReg']['cgst'] + @$gstr1['cr_drUnReg']['sgst'] + @$gstr1['cr_drUnReg']['cess']?>
                                </td>
                            </tr>
                            <tr>
                                <th>6</th>
                                <td>Expost Invoices -6A</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>7</th>
                                <td>Tax Liability(Advanced Received) -11A(1),11A(2)</td>
                                <td><?=@$gstr1['advance_tax']['total_voucher'] ? $gstr1['advance_tax']['total_voucher'] : 0  ?></td>
                                <td><?=@$gstr1['advance_tax']['total_taxable']?></td>
                                <td><?=@$gstr1['advance_tax']['igst'] ? @$gstr1['advance_tax']['igst'] : 0 ?></td>
                                <td><?=@$gstr1['advance_tax']['cgst'] ? @$gstr1['advance_tax']['cgst'] : 0 ?></td>
                                <td><?=@$gstr1['advance_tax']['sgst'] ? @$gstr1['advance_tax']['sgst'] : 0?></td>
                                <td></td>
                                <td><?=@$gstr1['advance_tax']['igst'] + $gstr1['advance_tax']['sgst'] + $gstr1['advance_tax']['cgst']?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>8</th>
                                <td>Adjustment Of Advances-11B(1),11B(2)</td>
                                <td>(-)<?=@$gstr1['advance_adjust']['total_voucher'] ? $gstr1['advance_adjust']['total_voucher'] : 0  ?></td>
                                <td></td>
                                <td>(-)<?=@$gstr1['advance_adjust']['igst'] ? @$gstr1['advance_adjust']['igst'] : 0 ?></td>
                                <td>(-)<?=@$gstr1['advance_adjust']['cgst'] ? @$gstr1['advance_adjust']['cgst'] : 0 ?></td>
                                <td>(-)<?=@$gstr1['advance_adjust']['sgst'] ? @$gstr1['advance_adjust']['sgst'] : 0?></td>
                                <td></td>
                                <td>(-)<?=@$gstr1['advance_adjust']['igst'] + $gstr1['advance_adjust']['sgst'] + $gstr1['advance_adjust']['cgst']?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>8</th>
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

function goto_url(type){

    var start  = $('#start_date').text();
    var from = start.split("/");
    var f = new Date(from[2], from[1], from[0]);
    var start_date = f.getFullYear() + "-" + f.getMonth() + "-" + f.getDate();
    
 
    var end  = $('#end_date').text();
    var to = end.split("/");
    
    var t = new Date(to[2],to[1],to[0]);
  
    var end_date = t.getFullYear() + "-" + t.getMonth() + "-" + t.getDate();
    var url = "<?=url('Addbook/View_filter/')?>";
    
    window.location =  url +'?type='+ type + '&from=' + start_date + '&to=' + end_date; 
}
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>