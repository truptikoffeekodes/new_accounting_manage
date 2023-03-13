<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.modal-dialog {
    max-width: 750px;
    margin: 1.75rem auto;
}
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Transaction </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="ml-auto pd-r-100">
        <h2 class="mb-1 font-weight-bold"><span>Mill Return Sr No :</span>
            <?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?></h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <form action="<?= url('Milling/Add_returnMill') ?>" class="ajax-form-submit" method="POST"
                        id="challanform">
                        <div class="row">
                          
                            <input class="form-control col-md-9" type="hidden" name="id"
                                value="<?= @$challan['id'] ? $challan['id'] : $id; ?>" placeholder="Enter id">

                            <input class="form-control col-md-9" type="hidden" name="srno" id="srno" readonly
                                value="<?= @$challan['sr_no'] ? $challan['sr_no'] : $current_id; ?>" required>


                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Mill Challan No: <span
                                        class="tx-danger">*</span></label>
                                <select class="form-control col-md-9" id="get_invoice" name='mill_challan'>
                                    <?php if(@$challan['mill_challan']) { ?>
                                    <option value="<?=@$challan['mill_challan']?>">
                                        <?=@$challan['mill_challan_name']?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="row col-md-6 form-group">
                                <!-- <label class="form-label col-md-3">Purchase Type: <span class="tx-danger">*</span></label>
                                <input class="form-control col-md-9 purchase_type" type="text" name="purchase_type" id="purchase_type" readonly
                                    value="<?= @$challan['purchase_type']?>"
                                    placeholder="Purchase Type" required> -->
                            </div>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Mill GR No :<span class="tx-danger">*</span></label>
                                <input class="form-control col-md-9 weaver_challan" placeholder="Weaver Challan No"
                                    required onkeypress="return isNumberKey(event)" type="text"
                                    value="<?=@$challan['weaver_challan']?>" name="weaver_challan">
                            </div>

                            <?php 
                                if(!empty($challan)){
                                    $invoice_date = user_date($challan['date']);
                                }
                                $today = user_date(date('Y-m-d'));
                            ?>

                            <div class="row col-md-6 form-group">
                                <label class="form-label col-md-3">Date: <span class="tx-danger">*</span></label>
                                <input class="form-control col-md-9 dateMask" required placeholder="DD-MM-YYYY"
                                    type="text" id="date" name="date"
                                    value="<?= @$challan['date'] ? $invoice_date : $today; ?>">
                            </div>

                            <div class="col-lg-5 form-group">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Transport Mode: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control transport_mode" id="transport_mode"
                                            name="trasport_mode">
                                            <option <?= ( @$challan['transport_mode'] == 'ROAD' ? 'selected' : '' ) ?>
                                                value="ROAD">ROAD</option>
                                            <option <?= ( @$challan['transport_mode'] == 'AIR' ? 'selected' : '' ) ?>
                                                value="AIR">AIR</option>
                                            <option <?= ( @$challan['transport_mode'] == 'RAIL' ? 'selected' : '' ) ?>
                                                value="RAIL">RAIL</option>
                                            <option <?= ( @$challan['transport_mode'] == 'SHIP' ? 'selected' : '' ) ?>
                                                value="SHIP">SHIP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Issue Party: <span class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control account" id="account" name='account'>
                                            <?php if(@$challan['party_name']) { ?>
                                            <option value="<?=@$challan['party_name']?>">
                                                <?=@$challan['account_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                        <!-- <input type="hidden" name="id" value="<?= @$challan['id']; ?>"> -->
                                        <input type="hidden" name="tds_per" id="tds_per"
                                            value="<?= @$challan['tds_per']; ?>">
                                        <input type="hidden" name="tds_limit" id="tds_limit"
                                            value="<?= @$challan['tds_limit']; ?>">
                                        <input type="hidden" name="acc_state" id="acc_state"
                                            value="<?= @$challan['acc_state']; ?>">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Delivery Ac: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control" id="delivery" name='delivery_ac'>
                                            <option value=""> Not One</option>
                                            <?php if(@$challan['delivery_ac']) { ?>
                                            <option selected value="<?=@$challan['delivery_ac']?>">
                                                <?=@$challan['delivery_ac_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-4 form-group">
                                        <label class="form-label">Delivery Address: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea class="form-control delivery" name="delivery_code" value=""
                                            placeholder="Delivery Address"
                                            type="text"><?= @$challan['delivery_code']; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 form-group">
                                <div class="row">
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR No.: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control lrno" name="lrno" value="<?= @$challan['lr_no']; ?>"
                                            placeholder="LR No." type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">LR Date.: </label>
                                    </div>
                                    <?php 
                                        $lr_date = user_date(@$challan['lr_date']);
                                    ?>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control dateMask lr_date" placeholder="MM/DD/YYYY"
                                            type="text" id="lr_date" name="lr_date" value="<?= @$lr_date; ?>">
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Weight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control weight" name="weight"
                                            value="<?= @$challan['weight']; ?>" placeholder="0.00"
                                            placeholder="Enter Weight" type="text">
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label class="form-label">Freight: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input class="form-control freight" name="freight"
                                            value="<?= @$challan['freight']; ?>" placeholder="00" type="text">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label class="form-label"> Warehouse: </label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="form-control warehouse" id="warehouse" name='warehouse'>
                                            <?php if(@$challan['warehouse_name']) { ?>
                                            <option value="<?=@$challan['warehouse']?>">
                                                <?=@$challan['warehouse_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Broker: </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="broker" name='broker'>
                                            <?php if(@$challan['broker']) { ?>
                                            <option value="<?=@$challan['broker']?>">
                                                <?=@$challan['broker_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>


                                    <div class="col-md-2 form-group">
                                        <label class="form-label"> Transport </label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <select class="form-control broker" id="transport" name='transport'>
                                            <?php if(@$challan['transport']) { ?>
                                            <option value="<?=@$challan['transport']?>">
                                                <?=@$challan['transport_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- <a target="_blank"   title="Add Item:<?=@$current_id?>" onclick="add_item(this)"  data-val="<?=@$current_id?>" data-pk="<?=@$current_id?>" tabindex="-1" class="btn btn-primary">Add Item</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0" id="product">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Hsn</th>
                                            <th>Type</th>
                                            <th>Grey Rate</th>
                                            <th>Gst</th>
                                            <th>TAKA</th>
                                            <th>MTR</th>
                                            <th>Ret Taka</th>
                                            <th>Ret Meter</th>
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">

                                        <?php 
                                        $total=0;
                                        if(isset($item))
                                        {  
                                            // echo '<pre>'print_r($items);exit;
                                            foreach($item as $row){

                                                $sub_total=$row['price'] * $row['pcs'] ;
                                                $total += $sub_total;
                                                //print_r($total);exit;
                                                //$uom=explode(',',$row['item_uom']);
                                                ?>
                                        <tr class="<?=$row['pid']?>">

                                            <td>#</td>
                                            <input type="hidden" name="pid[]" value="<?=$row['pid']?>">
                                            <input type="hidden" name="ret_takaTb_ids[]" value="">
                                            <input type="hidden" name="need_toDelete[]" value="">
                                            <input type="hidden" name="millTakaTb_ids[]">

                                            <td><?=$row['name']?>
                                            </td>
                                            <td><?=$row['hsn']?>
                                            </td>

                                            <td>
                                                <select id="type" name="type[]">
                                                    <?=$row['uom_opt']?>
                                                </select>
                                            </td>


                                            <td><input class="form-control input-sm" value="<?=$row['price']?>"
                                                    name="price[]" onkeypress="return isDesimalNumberKey(event)"
                                                    required="" type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['igst']?>"
                                                    name="igst[]" onkeypress="return isDesimalNumberKey(event)"
                                                    required="" type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['pcs']?>"
                                                    name="taka[]" readonly onkeypress="return isDesimalNumberKey(event)"
                                                    required="" type="text"></td>

                                            <td>
                                                <input class="form-control input-sm" type="text" name="meter[]"
                                                    value="<?=@$row['meter']?>" id="meter" readonly
                                                    onchange="cxalculate()"
                                                    onkeypress="return isDesimalNumberKey(event)">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$row['ret_taka']?>"
                                                    name="ret_taka[]" readonly
                                                    onkeypress="return isDesimalNumberKey(event)" required=""
                                                    type="text"><a data-toggle="modal" type="button" id="add_taka"
                                                    href="<?=url("Milling/Add_ReturnMillTaka/").$challan['mill_challan'].'/'.$row['pid'].'/'.$challan['id']?>"
                                                    data-target="#fm_model" data-title="Edit Taka" class="modal-lg"><i
                                                        class="far fa-edit"></i></a></td>
                                            <td>
                                                <input class="form-control input-sm" type="text" name="ret_meter[]"
                                                    value="<?=@$row['ret_meter']?>" id="meter" readonly
                                                    onchange="cxalculate()"
                                                    onkeypress="return isDesimalNumberKey(event)">
                                            </td>

                                            <td><input class="form-control input-sm" value="<?=$sub_total?>"
                                                    name="subtotal[]" onkeypress="return isDesimalNumberKey(event)"
                                                    required="" type="text"></td>

                                            <td><input class="form-control input-sm" value="<?=$row['remark']?>"
                                                    name="remark[]" type="text"></td>
                                        </tr>
                                        <?php 
                                            } 
                                        }?>
                                    </tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>

                                        <td class="amount_total"></td>
                                        <td class="amount_total"></td>
                                        <td class="IGST_total"></td>
                                        <td class="CGST_total"></td>
                                        <td class="SGST_total"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="total"><?=@$total; ?></td>
                                        <td></td>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="row mt-3">
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mt-3">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mg-b-0">
                                            <thead>
                                                <tr>
                                                    <th>(-)Discount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control"  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="discount" type="text"
                                                                value="<?= @$challan['discount']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="disc_type"
                                                                     >
                                                                    <option
                                                                        <?=(@$challan['disc_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?=(@$challan['disc_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="discount_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>(-)Less Amount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control"  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amtx" type="text"
                                                                value="<?= @$challan['amtx']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="amtx_type"
                                                                     >
                                                                    <option
                                                                        <?= ( @$challan['amtx_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['amtx_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amtx_amount wd-90"></th>
                                                </tr>

                                                <tr>
                                                    <th>(+)Add Amount</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control"  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="amty" type="text"
                                                                value="<?= @$challan['amty']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="amty_type"
                                                                     >
                                                                    <option
                                                                        <?= ( @$challan['amty_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['amty_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="amty_amount wd-90"></th>
                                                </tr>
                                                <?php 
                                                    $taxes = json_decode(@$challan['taxes']);
                                                   
                                                ?>
                                                <tr>
                                                    <th>Select Tax</th>
                                                    <th colspan="2" class="wd-300">
                                                        <div class="input-group-sm">
                                                            <select class="select2" id="tax" name="taxes[]"
                                                                  multiple>
                                                                <?php foreach($tax as $row) { 
                                                                        if($row['name'] == 'igst' && session('state') != @$challan['acc_state']) {
                                                                ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'cgst'  && session('state') == @$challan['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'sgst'  && session('state') == @$challan['acc_state']){ ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else if($row['name'] == 'tds' || $row['name'] == 'cess' ) { ?>

                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>

                                                                <?php }else{ if(!@$challan)  { ?>
                                                                <option value="<?=$row['name'] ?>"
                                                                    <?php if(!empty($taxes)) { echo  (in_array($row['name'], $taxes)) ? 'selected' : '' ; } ?>>
                                                                    <?=$row['name']; ?></option>
                                                                <?php } } } ?>

                                                            </select>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <tr id="igst"
                                                    style="display:<?php if(!empty($taxes)) {  echo  (in_array("igst", $taxes)) ? 'table-row;' : 'none;' ; }else{ echo 'none;'; }  ?>">
                                                    <th>(+)IGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control igst" readonly
                                                                 
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_igst" type="text"
                                                                value="<?= @$challan['tot_igst']; ?>">
                                                        </div>
                                                    </th>
                                                    <th class="igst_amount wd-90"></th>
                                                </tr>

                                                <tr id="sgst"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("sgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                    <th>(+)SGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control sgst" readonly
                                                                 
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_sgst" type="text"
                                                                value="<?= @$challan['tot_sgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="sgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="cgst"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("cgst", $taxes) ? 'table-row;' : 'none;'; } else{ echo 'none;'; } ?>">
                                                    <th>(+)CGST</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control cgst" readonly
                                                                 
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tot_cgst" type="text"
                                                                value="<?= @$challan['tot_cgst']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="cgst_amount wd-90"></th>
                                                </tr>

                                                <tr id="tds"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("tds", $taxes) ? 'table-row;' : 'none;'; }else{ echo 'none;'; } ?>">
                                                    <th>(+)TDS</th>
                                                    <th class="wd-300">
                                                        <div class="input-group-sm">
                                                            <input class="form-control" readonly  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="tds_amt" type="text"
                                                                value="<?= @$challan['tds_amt']; ?>">

                                                        </div>
                                                    </th>
                                                    <th class="tds_amount wd-90"></th>
                                                </tr>

                                                <tr id="cess"
                                                    style="display:<?php if(!empty($taxes)) { echo in_array("cess", $taxes) ? 'table-row;' : 'none;'; }else{echo 'none;';} ?> ">
                                                    <th>(+)Cess</th>
                                                    <th class="wd-300">
                                                        <div class="input-group">
                                                            <input class="form-control"  
                                                                onkeypress="return isDesimalNumberKey(event)"
                                                                name="cess" type="text"
                                                                value="<?= @$challan['cess']; ?>">
                                                            <div class="input-group-prepend">
                                                                <select class="select2" name="cess_type"
                                                                     >
                                                                    <option
                                                                        <?= ( @$challan['cess_type'] == 'Fixed' ? 'selected' : '' ) ?>
                                                                        value="Fixed">Fixed Amount</option>
                                                                    <option
                                                                        <?= ( @$challan['cess_type'] == '%' ? 'selected' : '' ) ?>
                                                                        value="%">Per(%) Amount</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <th class="cess_amount wd-90"></th>
                                                </tr>
                                                <tr>
                                                    <td>Net Amount</td>
                                                    <td colspan="2"><input class="form-control input-sm"
                                                        name="net_amount" type="text" readonly></td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                </div>
                <div class="form-group">
                    <div class="tx-danger error-msg"></div>
                    <div class="tx-success form_proccessing"></div>
                </div>
                <div class=" mt-3">
                    <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                        value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}


$('.ajax-form-submit').on('submit', function(e) {
    var meter = $('input[name="meter[]"]').map(function() {
        return parseFloat(this.value);
    }).get();

    var test = false;
    $.each(meter, function(index, value) {
        if (value == 'NaN' || isNaN(value) || value == 'undefined') {
            console.log('value  ' + value);
            $('.error-msg').html('Please Add Meter..!!');
            test = true;
            return false;
        }
    });

    if (test) {
        return false;
    }
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    //$('.form_proccessing').html('Please wail...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                swal("success!", "Your update successfully!", "success");
                $('#save_data').prop('disabled', false);
                window.location = "<?=url('Milling/return_mill')?>";
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


$(document).ready(function() {

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#transport_mode').select2({
        width: '65%'
    });

    var pids = $('input[name="pid[]"]').map(function() {
        return parseInt(this.value); // $(this).val()
    }).get();


    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    $('.dateMask').mask('99-99-9999');

    $("#account").select2({
        width: '66.5%',
        placeholder: 'Type Account Name',
        ajax: {
            url: PATH + "Master/Getdata/search_account_grey",
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

    $('#account').on('select2:select', function(e) {
        var data = e.params.data;
        $('#acc_state').val(data.state);
    });



    $("#broker").select2({
        width: '100%',
        placeholder: 'Type Broker Account',
        ajax: {
            url: PATH + "Master/Getdata/search_broker",
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



    $('#broker').on('select2:select', function(e) {
        var data = e.params.data;

        $('#fix_brokrage').val(data.brokrage);
        // $('#brok_name').text(data.text);
        // $('.broker-error').text('');
    });



    $("#transport").select2({
        width: '100%',
        placeholder: 'Type Transport',
        ajax: {
            url: PATH + "Master/Getdata/search_transport",
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

    $("#warehouse").select2({
        width: '100%',
        placeholder: 'Type Warehouse Account',
        ajax: {
            url: PATH + "Master/Getdata/search_warehouse",
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

    $("#city").select2({
        width: '100%',
        placeholder: 'Type City',
        ajax: {
            url: PATH + "Master/Getdata/search_city",
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

    $("#vehicle").select2({
        width: '65%',
        placeholder: 'Type Vehicle',
        ajax: {
            url: PATH + "Master/Getdata/search_vehicle",
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

    $("#mill_ac").select2({
        width: '100%',
        placeholder: 'Type Mill Ac Name',
        ajax: {
            url: PATH + "Master/Getdata/search_sun_credit",
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

    $("#delivery").select2({
        width: 'resolve',
        placeholder: {
            id: '', // the value of the option
            text: 'None Selected'
        },
        allowClear: true,
        ajax: {
            url: PATH + "Master/Getdata/search_accountSundry_cred_debt",
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

    $('#delivery').on('select2:select', function(e) {
        var data = e.params.data;
        console.log(data)
        $('textarea[name=delivery_code]').html(data.address);
    });




    $("#get_invoice").select2({
        width: 'resolve',
        placeholder: 'Search by Weaver Invoice No and Name.',
        ajax: {
            url: PATH + "Milling/Getdata/search_MillChallanForReturn",
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

    $('#get_invoice').on('select2:select', function(e) {

        $(".tbody").empty();

        var suggesion = e.params.data;
        console.log(suggesion)
        var item = suggesion.item;

        var acc = '<option selected value="' + suggesion.challan.mill_ac + '">' + suggesion.challan
            .account_name + '</option>';

        var trans = '<option selected value="' + suggesion.challan.transport + '">' + suggesion.challan
            .transport_name + '</option>';

        



        var brok = '<option selected value="' + suggesion.challan.broker + '">' + suggesion.challan
            .broker_name + '</option>';

        var warehouse = '<option selected value="' + suggesion.challan.warehouse + '">' + suggesion
            .challan
            .warehouse_name + '</option>';

        

        var tran_mode = '<option selected value="' + suggesion.challan.transport_mode + '">' + suggesion
            .challan.transport_mode + '</option>';

        $('.account').append(acc);
        $('.broker').append(brok);
        $('.warehouse').append(warehouse);
        
        $('#transport').append(trans);

      
        $('.trans_mode').append(tran_mode);


        $('#acc_state').val(suggesion.challan.acc_state);
        $('.delivery').val(suggesion.challan.delivery_code);
        $('.lrno').val(suggesion.challan.lr_no);
        $('.lr_date').val(suggesion.challan.lr_date);
        $('.weight').val(suggesion.challan.weight);
        $('.freight').val(suggesion.challan.freight);
        $('.purchase_type').val(suggesion.challan.purchase_type);


        for (i = 0; i < item.length; i++) {

            var inp = '<input type="hidden" name="pid[]" value="' + item[i].pid + '">';
            var tds = '<tr class="' + item[i].pid + '">';
            tds += '<input type="hidden" name="ret_takaTb_ids[]" value="">';
            tds += '<input type="hidden" name="millTakaTb_ids[]" value="">';
            tds += '<input type="hidden" name="need_toDelete[]" value="">';

            tds += '<td>#</td>';

            tds += '<td>' + item[i].name + inp + '</td>';
            tds += '<td>' + item[i].hsn + '</td>';
            tds += '<td><select name="type[]">' + item[i].uom_opt + '</select></td>';

            tds += '<td><input id="rate" class="form-control input-sm" value="' + item[i].price +
                '" name="price[]"   readonly onkeypress="return isDesimalNumberKey(event)" value="0" required="" type="text"></td>';

            tds += '<td><input class="form-control input-sm" value="' + item[i].igst +
                '" name="igst[]"   onkeypress="return isDesimalNumberKey(event)" value="0" readonly required="" type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" id="taka" value="' + item[i].pcs +
                '" name="taka[]"   onkeypress="return isDesimalNumberKey(event)" required="" readonly type="text"></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value="' + item[i].meter +
                '"   onkeypress="return isDesimalNumberKey(event)" id="meter" name="meter[]" required readonly></td>';

            tds +=
                '<td><input class="form-control input-sm" id="taka" value="" name="ret_taka[]"   onkeypress="return isDesimalNumberKey(event)" required=""  type="text"><a data-toggle="modal" type="button" id="add_taka" href="<?=url('Milling/Add_ReturnMillTaka/')?>' +
                suggesion.challan.id + '/' + item[i].pid +
                '" data-target="#fm_model" data-title="Add Taka" class=""><i class="far fa-edit"></i></a></td>';

            tds +=
                '<td><input class="form-control input-sm" type="text" value=""  onkeypress="return isDesimalNumberKey(event)"  name="ret_meter[]" required></td>';

            tds +=
                '<td><input class="form-control input-sm" id="subt" name="subtotal[]"   value="0" required="" type="text" readonly></td>';

            tds +=
                '<td><input class="form-control input-sm"  name="remark[]"  value=""  type="text" ></td>';
            tds += '</tr>';

            $('.tbody').append(tds);
            $('#code').val('');

        }


    });

});




function subtotal(type) {
    var pcs = $("#pcs").val();
    var mtr = $("#mtr").val();
    var cut = $("#cut").val();
    var rate = $("#rate").val();

    if (type == 'pcs') {
        stotal = pcs * rate;
        //amount=
    } else if (type == 'mtr') {
        stotal = mtr * rate;
    } else {
        subtotal = cut * rate;
    }
    $("#subt").val(stotal);

}
</script>
<?= $this->endSection() ?>