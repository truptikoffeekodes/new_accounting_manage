<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.error {
    color: red;
}
</style>
<!--colorpicker css-->
<link href="<?=ASSETS?>/plugins/spectrum-colorpicker/spectrum.css" rel="stylesheet">
<!-- page header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5">Account</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Account</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<!-- End page header -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            <div class="card-body">
                <form action="<?=url('account/add_account')?>" id="accountform" method="post">
                    <div id="wizard1">
                        <hr>
                        <h3>General</h3>
                        <section>
                            <hr>
                            <div class="row">

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Under GL Group:</b> <span class="tx-danger">*</span>
                                        <a data-toggle="modal" href="<?=url('master/add_glgrp')?>"
                                            data-target="#fm_model" data-title="Add GL Group ">
                                            <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                    class="fa fa-plus"></i></i></a>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="glgrp_ac" name='glgrp' required>
                                            <?php if(@$account_view['gl_grp']) { ?>
                                            <option value="<?=@$account_view['gl_group']?>">
                                                <?=@$account_view['gl_grp']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 form-group" id="name_hide"
                                    style="display:<?=@$account_view['gl_grp'] == 'Duties and taxes' ? 'none;' : 'block;' ?>">
                                    <label class="form-label"><b>Name </b><span class="tx-danger">*</span></label>
                                    <input class="form-control" name="name" id="name"
                                        value="<?=@$account_view['name'];?>" onkeyup="code_generate(this.value)"
                                        placeholder="Enter Name" required type="text">
                                </div>

                                <div class="col-lg-4 form-group" id="name_show"
                                    style="display:<?=@$account_view['gl_grp'] == 'Duties and taxes' ? 'block;' : 'none;' ?>">
                                    <label class="form-label"><b>Name:</b></label>
                                    <select class="form-control select2" name="taxes_name" id="taxes_name" >
                                        <option value="">None</option>
                                        <option <?=(@$account_view['name'] == "igst" ? 'selected' : '')?> value="igst">
                                            IGST</option>
                                        <option <?=(@$account_view['name'] == "sgst" ? 'selected' : '')?> value="sgst">
                                            SGST</option>
                                        <option <?=(@$account_view['name'] == "cgst" ? 'selected' : '')?> value="cgst">
                                            CGST</option>
                                        <option <?=(@$account_view['name'] == "cess" ? 'selected' : '')?> value="cess">
                                            CESS</option>
                                        <option <?=(@$account_view['name'] == "tds" ? 'selected' : '')?> value="tds">
                                            TDS</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Owner Name </b></label>
                                    <input class="form-control" name="own_name" value="<?=@$account_view['owner'];?>"
                                        placeholder="Enter Name" type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Code:</b><span class="tx-danger">*</span></label>
                                    <input class="form-control" name="code" id="code"
                                        value="<?=@$account_view['code'];?>" placeholder="Enter Code" type="text">
                                    <input name="id" value="<?=@$account_view['id'];?>" type="hidden">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Party Group:</b></label>
                                    <div class="input-group">
                                        <select class="form-control" id="party" name='party'>
                                            <?php if(@$account_view['party_grp']) { ?>
                                            <option value="<?=@$account_view['party_group']?>">
                                                <?=@$account_view['party_grp']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Print Name:</b></label>
                                    <input class="form-control" name="Pname" id="Pname"
                                        value="<?=@$account_view['print_name'];?>" placeholder="Enter Print Name"
                                        type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Email:</b></label>
                                    <input class="form-control" name="email" id="email"
                                        value="<?=@$account_view['email'];?>" placeholder="Enter Email" type="email">
                                </div>
                                
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Opening Bal (on <?=user_date(session('financial_form'))?> ):</b></label>
                                    <input class="form-control" name="opening_bal"   onkeypress="return isDesimalNumberKey(event)"
                                        value="<?=@$account_view['opening_bal'];?>" placeholder="Enter Opening Balance" type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Opening Bal. Amt:</b></label>
                                    <select class="form-control select2" id="openingBal_type" name="opening_type">
                                        <option <?=(@$account_view['opening_type'] == "Credit" ? 'selected' : '')?>
                                            value="Credit">Credit</option>
                                        <option <?=(@$account_view['opening_type'] == "Debit" ? 'selected' : '')?>
                                            value="Debit">Debit</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 form-group" id="brokrage">
                                    <label class="form-label"><b>Brokrage(%):</b></label>
                                    <input class="form-control" name="brokrage" value="<?=@$account_view['brokrage'];?>"
                                        placeholder="Enter Brokrage" type="text">
                                </div>

                                <div class="col-lg-4 form-group" id="due_day_div">
                                    <label class="form-label"><b>Default due Days:</b></label>
                                    <input class="form-control" name="due" value="<?=@$account_view['default_due_days'];?>"
                                        placeholder="Eneter Default Due days" type="text">
                                </div>

                                <div class="col-lg-4 form-group" id="due_day_div">
                                    <label class="form-label"><b>Interest Rate(%):</b></label>
                                    <input class="form-control" name="intrate"
                                        value="<?=@$account_view['intrest_rate'];?>" placeholder="Enter Interest Rate (%)"
                                        type="text">
                                </div>

                            </div>

                            <div id="gl_hide">
                                <hr>
                                <div class="row">

                                    <div class="col-lg-9 form-group">
                                        <label class="form-label"><b>GST Address:</b></label>
                                        <input class="form-control" name="gst_add" id="add"
                                            value="<?=@$account_view['gst_add'];?>" placeholder="Enter GST Address"
                                            type="text">
                                    </div>

                                    <div class="col-lg-9 form-group">
                                        <label class="form-label"><b>Address:</b></label>
                                        <input class="form-control" name="add" id="add"
                                            value="<?=@$account_view['address'];?>" placeholder="Enter Address"
                                            type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Country:</b></label>
                                        <select class="form-control" id="country" name='country'>
                                            <option value="101" selected>India </option>
                                            <?php if(@$account_view['country_name']) { ?>
                                            <option value="<?=@$account_view['country'] ?>">
                                                <?=@$account_view['country_name'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>State:</b></label>
                                        <select class="form-control" id="state" name='state'>
                                            <option value="12" selected>Gujarat</option>
                                            <?php if(@$account_view['state_name']) { ?>
                                            <option value="<?=@$account_view['state']?>">
                                                <?=@$account_view['state_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>City:</b></label>
                                        <select class="form-control" id="city" name="city">
                                            <?php if(@$account_view['city_name']) { ?>
                                            <option value="<?=@$account_view['city']?>"><?=@$account_view['city_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>PIN:</b></label>
                                        <input class="form-control" name="pin" id="pin"
                                            value="<?=@$account_view['pin'];?>" placeholder="Enter PIN" type="text"
                                            onkeypress="return isDesimalNumberKey(event)">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Area:</b> </label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="area_name"
                                                placeholder="Enter Area" value="<?=@$account_view['area'];?>"
                                                onchange="validate_autocomplete(this,'area')">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="form-label"><b>Mobile:</b></label>
                                        <input class="form-control" name="mob" value="<?=@$account_view['mobile'];?>"
                                            placeholder="Enter Mobile" type="text"
                                            onkeypress="return isDesimalNumberKey(event)">
                                    </div>

                                    <div class="col-lg-6 form-group">
                                        <label class="form-label"><b>whatsapp:</b></label>
                                        <input class="form-control" name="whatspp"
                                            value="<?=@$account_view['whatspp'];?>" placeholder="Enter Whatsapp No."
                                            type="text" onkeypress="return isDesimalNumberKey(event)">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Reffered:</b> </label>
                                        <div class="input-group">
                                            <select class="form-control" id="ref_name" name='refrred_id'>
                                                <?php if(@$account_view['reffered_name']) { ?>
                                                <option value="<?=@$account_view['refrred']?>">
                                                    <?=@$account_view['reffered_name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 form-group" id="trans_div">
                                        <label class="form-label"><b>Transport:</b>
                                            <a data-toggle="modal" href="<?=url('Master/add_transport')?>"
                                                data-target="#fm_model" data-title="Add Transport">
                                                <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                        class="fa fa-plus"></i></i></a>
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control" id="trans_name" name='transport_id'>
                                                <?php if(@$account_view['trancode']) { ?>
                                                <option value="<?=@$account_view['transport']?>">
                                                    <?=@$account_view['trancode']?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>
                        </section>
                        
                        <h3 id = "h3_tax_div">Tax/Transaction</h3>
                        
                        <section id="tax_div">
                            <hr>
                            <div class="row">

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Income Tax PAN:</b></label>
                                    <input class="form-control" name="taxpan" value="<?=@$account_view['tax_pan'];?>"
                                        placeholder="Enter Income Tax PAN" type="text">
                                </div>

                                <!-- <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>TAN NO:</b></label>
                                    <input class="form-control" name="tanno" value="<?=@$account_view['tan_no'];?>"
                                        placeholder="Enter TAN NO" type="text">
                                </div> -->

                                <div class="col-lg-4 form-group" id="hsn_div">
                                    <label class="form-label"><b>HSN No:</b></label>
                                    <input class="form-control" name="hsn" value="<?=@$account_view['hsn'];?>"
                                        placeholder="Enter HSN No" type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>SET ALT GST DETAIL:</b></label>
                                    <select class="form-control select2" name="alt_gst">

                                        <option <?=(@$account_view['alt_gst'] == "N/A" ? 'selected' : '')?> value="N/A">
                                            N/A</option>
                                        <option <?=(@$account_view['alt_gst'] == "DEEMED EXPORT" ? 'selected' : '')?>
                                            value="DEEMED EXPORT">DEEMED EXPORT</option>
                                        <option <?=(@$account_view['alt_gst'] == "UN BODY" ? 'selected' : '')?>
                                            value="UN BODY">UN BODY</option>
                                        <option <?=(@$account_view['alt_gst'] == "SEZ" ? 'selected' : '')?> value="SEZ">
                                            SEZ</option>
                                    </select>
                                </div>

                                <?php  
                                if(!empty($account_view)){
                                    if($account_view['gl_group'] == 16 || $account_view['gl_group'] == 27 || $account_view['gl_group'] == 29 || $account_view['gl_group'] == 30 || $account_view['gl_group'] == 31){
                                        $gst_type_display = 'none;';
                                    }else{
                                        $gst_type_display = 'block;';
                                    }
                                }else{
                                    $gst_type_display = 'block;';
                                }
                                ?>
                                
                                <div class="col-lg-4 form-group" id="gst_type_div"
                                    style="display:<?=@$gst_type_display?>">
                                    <label class="form-label"><b>GST Type:</b></label>
                                    <select class="form-control select2" id="gst_type" name="gst_type">

                                        <option <?=(@$account_view['gst_type'] == "Unregister" ? 'selected' : '')?>
                                            value="Unregister">Unregister</option>
                                        <option <?=(@$account_view['gst_type'] == "Regular" ? 'selected' : '')?>
                                            value="Regular">Regular</option>
                                        <option <?=(@$account_view['gst_type'] == "Composition" ? 'selected' : '')?>
                                            value="Composition">Composition</option>
                                        <option <?=(@$account_view['gst_type'] == "Unknown" ? 'selected' : '')?>
                                            value="Unknown">Unknown</option>
                                        <option <?=(@$account_view['gst_type'] == "Consumer" ? 'selected' : '')?>
                                            value="Consumer">Consumer</option>
                                        <option <?=(@$account_view['gst_type'] == "Other" ? 'selected' : '')?>
                                            value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 form-group" id="gstno_div"
                                    style="display:<?=@$account_view['gst_type'] == 'Regular' ? 'block;' : 'none;';?>">
                                    <label class="form-label"><b>GST No:</b></label>
                                    <input class="form-control" name="gst" value="<?=@$account_view['gst'];?>"
                                        placeholder="Enter GST No" type="text">
                                </div>

                                <?php  
                                    if(!empty($account_view)){
                                        if($account_view['gl_group'] == 16 || $account_view['gl_group'] == 27 || $account_view['gl_group'] == 29 || $account_view['gl_group'] == 30 || $account_view['gl_group'] == 31 || $account_view['gst_type'] =='Regular'){
                                            $display = 'block;';
                                        }else{
                                            $display = 'none;';
                                        }
                                    }
                                ?>

                                <div class="col-lg-4 form-group taxability" id="taxability_div"
                                    style="display:<?=@$display?>">
                                    <label class="form-label"><b>Taxability:</b></label>
                                    <select class="form-control select2" id="taxability" name="taxability">

                                        <option <?=(@$account_view['taxability'] == "N/A" ? 'selected' : 'selecetd')?>
                                            value="N/A">N/A</option>
                                        <option <?=(@$account_view['taxability'] == "Taxable" ? 'selected' : '')?>
                                            value="Taxable">Taxable</option>
                                    </select>
                                </div>

                                <!--<div class="col-lg-6 form-group">
                                    <label class="form-label"><b>GST Date:</b></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div><input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                            type="text" name="gst_date" value="<?=@$account_view['gst_date'];?>">
                                    </div>
                                </div>-->

                            </div>
                            
                            <div id="gst_div" style="<?=@$account_view['taxability'] == 'Taxable' ? 'display:block;' : 'display:none;' ?>">
                                <div class="row">
                                    <hr>
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Is Reverse Charge Applicable:</b></label>
                                        <select class="form-control select2" name="rev_charge" required>
                                            <option <?=(@$account_view['rev_charge'] == "0" ? 'selected' : 'selected')?>
                                                value="0">
                                                No</option>
                                            <option <?=(@$account_view['rev_charge'] == "1" ? 'selected' : '')?>
                                                value="1">
                                                Yes</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Is ineligible For input Credit:</b></label>
                                        <select class="form-control select2" name="ineligible" required>
                                            <option <?=(@$account_view['ineligible'] == "0" ? 'selected' : 'selected')?>
                                                value="0">
                                                No</option>
                                            <option <?=(@$account_view['ineligible'] == "1" ? 'selected' : '')?>
                                                value="1">
                                                Yes</option>

                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Cess:</b></label>
                                        <input class="form-control" name="cess"
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?=@$account_view['cess'];?>" placeholder="Enter Cess %" type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Integrated Tax:</b></label>
                                        <input class="form-control" name="igst" onkeyup="calc_gst(this.value)"
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?=@$account_view['igst'];?>" placeholder="Enter IGST %" type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>Central Tax:</b></label>
                                        <input class="form-control" name="cgst"
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?=@$account_view['cgst'];?>" placeholder="Enter CGST %" type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>State Tax:</b></label>
                                        <input class="form-control" name="sgst"
                                            onkeypress="return isDesimalNumberKey(event)"
                                            value="<?=@$account_view['sgst'];?>" placeholder="Enter SGST %" type="text">
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 form-group">
                                    <label class="custom-switch">
                                        <input type="checkbox" name="check_tds" onclick="tds_show()" id="check_tds"
                                            class="custom-switch-input"
                                            <?=@$account_view['tds_check'] == 1 ? 'checked' : '' ?>>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">TDS Detail</span>
                                    </label>
                                </div>
                            </div>

                            <div id="tds"
                                style="<?=@$account_view['tds_check'] == 1 ? 'display:block;' : 'display:none;' ?>">
                                <div class="row">

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS Section:</b></label>
                                        <select class="form-control" id="tds_search" name='tds'>
                                            <?php if(@$account_view['tds']) { ?>
                                            <option value="<?=@$account_view['tds']?>">
                                                <?=@$account_view['tds_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS Rate:</b></label>
                                        <input class="form-control tds_rate" name="tds_rate"
                                            value="<?=@$account_view['tds_rate'];?>" placeholder="Enter TDS Rate"
                                            type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS Limit:</b></label>
                                        <input class="form-control tds_limit" name="tds_limit"
                                            value="<?=@$account_view['tds_limit'];?>" placeholder="Enter TDS Limit"
                                            type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS Cess:</b></label>
                                        <input class="form-control" name="tds_cess"
                                            value="<?=@$account_view['tds_cess'];?>" placeholder="Enter TDS Cess"
                                            type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS HCess:</b></label>
                                        <input class="form-control" name="tds_hcess"
                                            value="<?=@$account_view['tds_hcess'];?>" placeholder="Enter TDS HCess"
                                            type="text">
                                    </div>

                                    <div class="col-lg-4 form-group">
                                        <label class="form-label"><b>TDS Surcharge:</b></label>
                                        <input class="form-control" name="tds_surch"
                                            value="<?=@$account_view['tds_surcharge'];?>"
                                            placeholder="Enter TDS Surcharge" type="text">
                                    </div>

                                </div>
                            </div>
                            <hr>
                        </section>

                        <h3 id="h3_bank">Bank Details</h3>
                        <section id="bank_div">
                            <hr>    
                            <div class="row ">
                                
                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Bank:</b>
                                        <!-- <a data-toggle="modal" href="<?=url('master/add_bank')?>"
                                            data-target="#fm_model" data-title="Add Contact Details ">
                                            <i class="btn btn-secondary btn-sm mb-1" style="float:right"><i
                                                    class="fa fa-plus"></i></i></a> -->
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control" id="bank" name='bank'>
                                            <?php if(@$account_view['bank_name']) { ?>
                                            <option value="<?=@$account_view['bank']?>">
                                                <?=@$account_view['bank_name']?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <input type="hidden" name="bank_id" id="bank_id"
                                        value="<?=@$account_view['bank'];?>">
                                    <div class="dz-error-message tx-danger bank_id"></div>
                                </div>

                                <div class="col-lg-4 form-group" id="bank_holder">
                                    <label class="form-label"><b>Bank Holder Name:</b></label>
                                    <input class="form-control" name="bank_holder" value="<?=@$account_view['bank_holder'];?>"
                                        placeholder="Enter Bank Holder Name" type="text">
                                </div>
                                
                                <div class="col-lg-4 form-group" id="ac_type">
                                    <label class="form-label"><b>Bank A/C Type:</b></label>
                                    <select class="form-control" name='ac_type'>
                                            
                                            <option value="Current" <?=@$account_view['ac_type'] =='Current' ? 'selected' :'' ?>>
                                                Current
                                            </option>
                                            <option value="Saving" <?=@$account_view['ac_type'] =='Saving' ? 'selected' :'' ?>>
                                                Saving
                                            </option>
                                    </select>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Bank Branch:</b></label>
                                    <input class="form-control" name="bankbranch"
                                        value="<?=@$account_view['bank_branch'];?>" placeholder="Enter Bank Branch"
                                        type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Bank A/C No:</b></label>
                                    <input class="form-control" name="bankac" value="<?=@$account_view['bank_ac_no'];?>"
                                        placeholder="Enter Bank A/C NO" type="text">
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="form-label"><b>Bank IFSC:</b></label>
                                    <input class="form-control" name="bankifsc"
                                        value="<?=@$account_view['bank_ifsc'];?>" placeholder="Enter Bank IFSC"
                                        type="text">
                                </div>

                            </div>
                        </section>

                    </div>
                    <div class="row pt-3">
                        <div class="col-sm-12">
                            <p class="text-right">
                                <button class="btn btn-space btn-primary" id="save_data" type="submit">Submit</button>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Row -->

<?=$this->endSection()?>

<?=$this->section('scripts')?>
<!-- Specturm-colorpicker js-->

<script>
<?php

if($id !='')
{    ?>

hide_show();
<?php }else{ ?>
    
<?php } ?>

function afterload() {}



if ($.isFunction($.fn.datatable_load)) {
    datatable_load('');
}
var form_loading = true;

function hide_show() {
    
    var gl_id= $('#glgrp_ac').val();

    
    $.ajax({
    url: '<?=url('account/get_gl_parent')?>',
    type: "post",
    data: { 
        gl_id: gl_id, 
        
    },
    success: function(data) {
        var glDiv = document.getElementById("gl_hide");
        var brokrage = document.getElementById("brokrage");

        var Name = document.getElementById("name_show");
        var HideName = document.getElementById("name_hide");
        var gstno_div = document.getElementById("gstno_div");
        var taxability_div = document.getElementById("taxability_div");
        var gst_type_div = document.getElementById("gst_type_div");
        var hsn_div = document.getElementById("hsn_div");
        
        var bank_div = document.getElementById("bank_div");
        var h3_bank = document.getElementById("h3_bank");

        var tax_div = document.getElementById("tax_div");
        var h3_tax = document.getElementById("h3_tax_div");
        
        var bank_holder = document.getElementById("bank_holder");
        var ac_type = document.getElementById("ac_type");

        var text = data.text;
        var main_id = data.main_id;
        var tx_bn_hide = data.tx_bn_hide;
        var bank_id = data.bank_id;


        if (main_id == '16' || main_id == '27' || main_id == '29' || main_id == '30' || main_id =='31') {
            gst_type_div.style.display = "none";
            taxability_div.style.display = "block";
            gstno_div.style.display = "none";
            hsn_div.style.display = "block";
            bank_div.style.display = "none";
            h3_bank.style.display = "none";
        } else {
            gst_type_div.style.display = "block";
            taxability_div.style.display = "none";
            // gstno_div.style.display = "block";
            hsn_div.style.display = "none";
            bank_div.style.display = "block";
            h3_bank.style.display = "block";
        }

        if (tx_bn_hide == '21' || tx_bn_hide == '24' || tx_bn_hide == '28' || tx_bn_hide == '17') {
           
            bank_div.style.display = "none";
            h3_bank.style.display = "none";
            tax_div.style.display = "none";
            h3_tax_div.style.display = "none";
        } else {
         
            bank_div.style.display = "block";
            h3_bank.style.display = "block";
            tax_div.style.display = "block";
            h3_tax_div.style.display = "block";
        }

        if(bank_id == '22'){
            bank_holder.style.display = "block";
            ac_type.style.display = "block";
        }else{
            bank_holder.style.display = "none";
            ac_type.style.display = "none";
        }


        if (text == 'Broker') {
            brokrage.style.display = "block";
        } else {
            brokrage.style.display = "none";
        }

        if (text == 'Expenses' || text == 'Incomes' || data.parent_id == data.expense_id || data
            .parent_id == data.income_id) {
            glDiv.style.display = "none";
        } else {
            glDiv.style.display = "block";
        }

        if (text == 'Duties and taxes') {
            HideName.style.display = "none";
            Name.style.display = "block";
            $('#name').attr('disabled');
        } else {
            Name.style.display = "none";
            HideName.style.display = "block";
            $('#taxes_name').attr('disabled');
        }
    },
    error: function(xhr) {
        
        console.log(xhr)
    }
    });
}

function validate_autocomplete(obj, val) {
    if ($('#' + val).val() == '') {
        $('.' + val).html('Option Select from dropdown list')
    } else {
        $('.' + val).html('')
    }
}

function tds_show() {
    //   var checkBox = document.getElementById("check_tds");
    //   var text = document.getElementById("tds");
    $("#tds").toggle();
    //   if (checkBox.checked == true){
    //     text.style.display = "block";
    //     checkbox.val('0');
    //   } else {
    //      text.style.display = "none";
    //      checkbox.val('1');
    //   }
}

function calc_gst(igst) {
    var gst = igst / 2;
    $('input[name="sgst"]').val(gst);
    $('input[name="cgst"]').val(gst);
}


$('#accountform').on('submit', function(e) {
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
                window.location = "<?=url('/Account')?>";
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
    // var form = $("#accountform");

    // form.validate({
    //     ignore: "",
    //     validateHiddenInputs: true,
    //     errorPlacement: function errorPlacement(error, element) {
    //         error.insertAfter(element);
    //         error.insertAfter(element.parent('.input-group'));
    //     },
    //     rules: {},
    //     messages: {}
    // });
    // var finishButton = $('.wizard').find('a[href="#finish"]');

    // $('#wizard1').steps({
    //     headerTag: 'h3',
    //     bodyTag: 'section',
    //     autoFocus: true,
    //     titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
    //     onStepChanging: function(event, currentIndex, newIndex) {
    //         form.validate().settings.ignore = ":disabled,:hidden";
    //         return form.valid();
    //     },
    //     onFinishing: function(event, currentIndex) {
    //         if (form_loading) {
    //             return true;
    //         } else {
    //             return false;
    //         }
    //     },
    //     onFinished: function(event, currentIndex) {
    //         var data = form.serialize();
    //         finishButton.html("<i class='sl sl-icon-reload'></i> Please wait...");
    //         //form_loading = false;
    //         $('.description_error').html('');
    //         var aurl = $('#accountform').attr('action');
    //         $.post(aurl, data, function(response) {
    //             if (response.st == 'success') {
    //                 window.location = "<?=url('Account')?>"
    //             } else {
    //                 finishButton.html("Create Account");
    //                 form_loading = true;
    //                 $('.description_error').html(response.msg);
    //             }
    //         }).fail(function(response) {
    //             finishButton.html("Create Account");
    //             form_loading = true;
    //             alert('Error');
    //         });
    //     }
    // });

    $('#taxability').on('select2:select', function(e) {
        var data = e.params.data;
        var gstDiv = document.getElementById("gst_div");
        if (data.id == 'N/A') {
            gstDiv.style.display = "none";
        } else {
            gstDiv.style.display = "block";
        }
    });

    $('#gst_type').on('select2:select', function(e) {

        var gstDiv = document.getElementById("gst_div");
        var data = e.params.data;
        var taxability = document.getElementById("taxability_div");
        var gst_no = document.getElementById("gstno_div");
        var taxable = $('#taxability').find(":selected").text();

        if (data.id == 'Regular' || data.id == 'Composition') {
            if (taxable == 'Taxable') {
                gstDiv.style.display = "block";
            }
            taxability.style.display = "block";
            gst_no.style.display = "block";
        } else {
            taxability.style.display = "none";
            gst_no.style.display = "none";
            gstDiv.style.display = "none";
        }
    });

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    
    $('.select2').select2({
        width: '100%',
        placeholder: "Select Option"
    });

    // $('#taxability').on('change', function() {
    //     var gstDiv = document.getElementById("gst_div");
    //     if (this.value == 'NILL') {
    //         gstDiv.style.display = "block";
    //     } else {
    //         gstDiv.style.display = "none";
    //     }
    // });

    $("#glgrp_ac").select2({
        width: '100%',
        placeholder: 'Type GL',
        ajax: {
            url: PATH + "Master/Getdata/parent_glgrp",
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


    $('#glgrp_ac').on('select2:select', function(e) {
        var data = e.params.data;
        
        var glDiv = document.getElementById("gl_hide");
        var brokrage = document.getElementById("brokrage");

        var Name = document.getElementById("name_show");
        var HideName = document.getElementById("name_hide");
        var gstno_div = document.getElementById("gstno_div");
        var taxability_div = document.getElementById("taxability_div");
        var gst_type_div = document.getElementById("gst_type_div");
        var hsn_div = document.getElementById("hsn_div");
        
        var bank_div = document.getElementById("bank_div");
        var h3_bank = document.getElementById("h3_bank");

        var tax_div = document.getElementById("tax_div");
        var h3_tax = document.getElementById("h3_tax_div");
        
        var bank_holder = document.getElementById("bank_holder");
        var ac_type = document.getElementById("ac_type");
        
        var trans_div = document.getElementById("trans_div");

        var text = data.text;
        var main_id = data.main_id;
        var tx_bn_hide = data.tx_bn_hide;
        var bank_id = data.bank_id;
        var opening_balDr = data.opening_balDr;
        var opening_balCr = data.opening_balCr;
        var creditor_debtor = data.creditor_debtor;
        
        
        if (creditor_debtor == '13' || creditor_debtor == '19'){
            trans_div.style.display = "block";
        }else{
            trans_div.style.display = "none";
        }
        
        if (main_id == '16' || main_id == '27' || main_id == '29' || main_id == '30' || main_id =='31') {
            gst_type_div.style.display = "none";
            taxability_div.style.display = "block";
            gstno_div.style.display = "none";
            hsn_div.style.display = "block";
            bank_div.style.display = "none";
            h3_bank.style.display = "none";
        } else {
            gst_type_div.style.display = "block";
            taxability_div.style.display = "none";
            // gstno_div.style.display = "block";
            hsn_div.style.display = "none";
            bank_div.style.display = "block";
            h3_bank.style.display = "block";
        }

        if (tx_bn_hide == '21' || tx_bn_hide == '24' || tx_bn_hide == '28' || tx_bn_hide == '17') {
           
            bank_div.style.display = "none";
            h3_bank.style.display = "none";
            tax_div.style.display = "none";
            h3_tax_div.style.display = "none";
        } else {
         
            bank_div.style.display = "block";
            h3_bank.style.display = "block";
            tax_div.style.display = "block";
            h3_tax_div.style.display = "block";
        }

        if(bank_id == '22'){
            bank_holder.style.display = "block";
            ac_type.style.display = "block";
        }else{
            bank_holder.style.display = "none";
            ac_type.style.display = "none";
        }
        
        if(opening_balDr == '1' || opening_balDr == '3'){
            console.log('je');
            $('select[name="opening_type"]').val('Debit').trigger('change');
        }else{
            console.log('th');
            $('select[name="opening_type"]').val('Credit').trigger('change');
        }

        if(opening_balCr == '2' || opening_balCr == '4'){
            console.log('ni');
            $('select[name="opening_type"]').val('Credit').trigger('change');
        }else{
            console.log('jenith');
            $('select[name="opening_type"]').val('Debit').trigger('change');
        }


        if (text == 'Broker') {
            brokrage.style.display = "block";
        } else {
            brokrage.style.display = "none";
        }

        if (text == 'Expenses' || text == 'Incomes' || data.parent_id == data.expense_id || data
            .parent_id == data.income_id) {
            glDiv.style.display = "none";
        } else {
            glDiv.style.display = "block";
        }

        if (text == 'Duties and taxes') {
            HideName.style.display = "none";
            Name.style.display = "block";
            $('#name').attr('disabled');
        } else {
            Name.style.display = "none";
            HideName.style.display = "block";
            $('#taxes_name').attr('disabled');
        }
    });

    $("#country").select2({
        width: '100%',
        placeholder: 'Type Country Name',
        ajax: {
            url: PATH + "Master/Getdata/search_country",
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



    $("#party").select2({
        width: '100%',
        placeholder: 'Type Party',
        ajax: {
            url: PATH + "Master/Getdata/search_party",
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



    $("#broker_name").select2({
        width: '100%',
        placeholder: 'Type Broker Name',
        ajax: {
            url: PATH + "Master/Getdata/search_account",
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


    $("#ref_name").select2({
        width: '100%',
        placeholder: 'Type Reffered Account',
        ajax: {
            url: PATH + "Master/Getdata/search_party",
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

    $("#trans_name").select2({
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

    $("#state").select2({
        width: '100%',
        placeholder: 'Type State',
        ajax: {
            url: PATH + "Master/Getdata/search_state",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term,
                    country: $('#country').val()
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
                    searchTerm: params.term, // search term
                    state: $('#state').val()
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

    $("#tds_search").select2({
        width: '100%',
        placeholder: 'Type TDS',
        ajax: {
            url: PATH + "Master/Getdata/search_tds",
            type: "post",
            allowClear: true,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    searchTerm: params.term, // search term
                    state: $('#state').val()
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

    $('#tds_search').on('select2:select', function(e) {
        var data = e.params.data;
        var threshold = data.threshold;
        var indi = data.indi;
        var other = data.other;

        $('.tds_rate').val(indi);
        $('.tds_limit').val(threshold);


    });

    $("#bank").select2({
        width: '100%',
        placeholder: 'Select Bank',
        ajax: {
            url: PATH + "Master/Getdata/search_bank",
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


});

function pageload() {

}


</script>

<?=$this->endSection()?>