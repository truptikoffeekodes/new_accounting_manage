<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Transaction </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-2 form-group">
                            <h4><label class="form-label"> No: <b><?= @$invoice['id']?></b> </label></h4>
                        </div>
                        <div class="col-lg-2 form-group">
                            <h4><label class="form-label"> Date: <b><?= @$invoice['invoice_date']?></b></label>
                            </h4>
                        </div>
                        <div class="col-lg-2 form-group">
                            
                        </div>
                        <div class="col-lg-2 form-group">
                            
                        </div>
                        <div class="col-lg-2 form-group">
                            
                        </div>


                        <div class="col-md-4 form-group">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="form-label"> Party Account: </label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label
                                        class="form-label "><b><?=@$invoice['party_account']; ?><?=@$p_return['gst_no']; ?></b>
                                    </label>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="form-label">Supl. Invoice: </label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <label class="form-label "><b><?=@$invoice['supp_inv']; ?></b>
                                    </label>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered mg-b-0" id="product">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Particular</th>
                                        <th>Amount</th>
                                        <th>IGST</th>
                                        <th>CGST</th>
                                        <th>SGST</th>
                                        <th>Total Amount</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    <?php 
                                        if(isset($acc))
                                        {
                                            $total=0.0;
                                            $i=0;
                                            foreach($acc as $row){
                                                $i++;
                                                $sub_total=$row['amount'];
                                                $total += $sub_total;
                                              //  $uom=explode(',',$row['item_uom']);
                                    ?>
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td><?=$row['account_name'] ?>(<?=$row['code'] ?>)</td>

                                        <td><?=$row['amount']?></td>

                                        <td><?=$row['igst']?></td>

                                        <td><?=$row['cgst']?></td>

                                        <td><?=$row['sgst']?></td>

                                        <td><?= $sub_total ?></td>
                                        <td><?=$row['remark']?></td>
                                    </tr>
                                    <?php } }?>
                                </tbody>
                                <tfoot>
                                    <td colspan="2" class="text-right">Total</td>

                                    <td class="amount_total"></td>
                                    <td class="IGST_total"></td>
                                    <td class="CGST_total"></td>
                                    <td class="SGST_total"></td>
                                    <td class="total"><?= @$total ?></td>
                                    <td></td>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="row mt-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered mg-b-0" id="selling_case">

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mt-4">
                                <div class="table-responsive">
                                    <table class="table table-bordered mg-b-0">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <h6>(-)Discount</h6>
                                                </th>
                                                <th>
                                                    <div class="input-group">
                                                        <?= @$invoice['discount'] == '' ? '0' : @$invoice['discount'] ; ?>
                                                    </div>
                                                </th>
                                                <th><?=@$invoice['disc_type']?></th>
                                            </tr>

                                            <tr>
                                                <th>
                                                    <h6>(-)Less Amount</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['amtx'] == '' ? '0' : @$invoice['amtx'] ; ?>
                                                </th>
                                                <th><?=@$invoice['amtx_type']?></th>
                                            </tr>

                                            <tr>
                                                <th>
                                                    <h6>(+)Add Amount</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['amty'] == '' ? '0' : @$invoice['amty'] ; ?>
                                                </th>
                                                <th><?=@$invoice['amtx_type']?></th>
                                            </tr>

                                            <tr id="igst"
                                                style="display:<?php if(!empty($taxes)) {  echo  (in_array("igst", $taxes)) ? 'table-row;' : 'none;' ; }else{ echo 'none;'; }  ?>">
                                                <th>(+)IGST</th>
                                                <th>
                                                    <?= @$invoice['tot_igst'] == '' ? '0' : @$invoice['tot_igst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="sgst"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("sgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                <th>
                                                    <h6>(+)SGST</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['tot_sgst'] == '' ? '0' : @$invoice['tot_igst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="cgst"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("cgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                <th>
                                                    <h6>(+)CGST</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['tot_cgst'] == '' ? '0' : @$invoice['tot_cgst'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="tds"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("tds", $taxes) ? 'table-row;' : 'none;'; }else{ echo 'none;'; } ?>">
                                                <th>
                                                    <h6>(+)TDS</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['tds_amt'] == '' ? '0' : @$invoice['tds_amt'] ; ?>
                                                </th>
                                                <th></th>
                                            </tr>

                                            <tr id="cess"
                                                style="display:<?php if(!empty($taxes)) { echo in_array("cess", $taxes) ? 'table-row;' : 'none;'; }else{echo 'none;';} ?> ">
                                                <th>
                                                    <h6>(+)Cess</h6>
                                                </th>
                                                <th>
                                                    <?= @$invoice['cess'] == '' ? '0' : @$invoice['cess'] ; ?>
                                                </th>
                                                <th><?=@$invoice['cess_type']?></th>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>Net Amount </h4>
                                                </td>
                                                <td colspan="2">
                                                    <h5><?=@$invoice['net_amount']?></h5>
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>