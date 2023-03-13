<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.doc_lb {
    font-weight: bold;
}

.doc_name {
    text-align: center;
}
</style>
<div class="container-fluid">

    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Account</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Account</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Row -->
    <div class="row">
       
        <div class="col-lg-12 col-md-12">
            <div class="card custom-card main-content-body-profile">
                <nav class="nav main-nav-line">
                    <a class="nav-link active" data-toggle="tab" href="#tab1over">General</a>
                    <a class="nav-link" data-toggle="tab" href="#tab2rev">Other Info</a>
                </nav>
                <div class="card-body tab-content h-100">
                    <div class="tab-pane active" id="tab1over">
                        <div class="main-content-label tx-13 mg-b-20">
                            Ledger Information
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <?php 	
                                    if(!empty($account['created_at'] && $account['created_at'] != '0000-00-00')){
                                        $dt = date_create($account['created_at']);
                                        $create_date =  date_format($dt,'d-m-Y');
                                    }else{
                                        $create_date= '';
                                    }
                                ?>
                                <tbody class="col-lg-12 col-xl-6 p-0">

                                    <tr>
                                        <td><b>Ledger Name:</b></td>
                                        <td><?=$account['name']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GL Group:</b></td>
                                        <td><?=$account['gl_grp']?> </td>
                                    </tr>
                                    <tr>
                                        <td><b>Party Group:</b></td>
                                        <td><?=$account['party_group']?></td>
                                    </tr>

                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Code:</b></td>
                                        <td><?=$account['code']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Owner:</b></td>
                                        <td><?=$account['owner']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Whatspp No:</b></td>
                                        <td><?=$account['whatspp']?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="main-content-label tx-13 mg-b-20">
                            TAX Detail
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <tbody class="col-lg-12 col-xl-6 p-0">

                                    <tr>
                                        <td><b>Income Tax PAN:</b></td>
                                        <td><?=$account['tax_pan']?></td>
                                    </tr>

                                    <tr>
                                        <td><b>HSN:</b></td>
                                        <td><?=$account['hsn']?></td>
                                    </tr>

                                    <tr>
                                        <td><b>Alter GST Detail:</b></td>
                                        <td><?=$account['alt_gst']?></td>
                                    </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>GST Type:</b></td>
                                        <td><?=$account['gst_type']?></td>
                                    </tr>
                                    <?php if($account['gst_type'] == 'Composition' || $account['gst_type'] == 'Regular'){ ?>

                                    <tr>
                                        <td><b>GST No:</b></td>
                                        <td><?=$account['gst']?></td>
                                    </tr>
                                    <?php } ?>
                                   
                                </tbody>
                            </table>
                        </div>
                        <?php if($account['taxability'] != 'N/A' && $account['taxability'] != ''){ ?>
                        <hr>
                        <div class="main-content-label tx-13 mg-b-20">
                            Taxability
                        </div>
                        <hr>         
                        <div class="table-responsive">
                            <table class="table row table-borderless">
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Is Reverse Charge Applicable:</b></td>
                                        <td><?=$account['rev_charge']==0 ? 'NO' : 'YES'?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Integrated Tax:</b></td>
                                        <td><?=$account['igst']?>%</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>State Tax:</b></td>
                                        <td><?=$account['sgst']?>%</td>
                                    </tr>
                                    
                                    
                                    
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Is ineligible For input Credit:</b></td>
                                        <td><?=$account['ineligible']==0 ? 'NO' : 'YES'?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Cess:</b></td>
                                        <td><?=$account['cess']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Central Tax:</b></td>
                                        <td><?=$account['cgst']?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php }?>
                        
                        <?php if($account['tds_check'] != 0){ ?>
                            <hr>
                            <div class="main-content-label tx-13 mg-b-20">
                                TDS Section
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table class="table row table-borderless">
                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><b>TDS Section:</b></td>
                                            <td><?=$account['tds']?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TDS Rate:</b></td>
                                            <td><?=$account['tds_rate']?></td>
                                        </tr>
                                        
                                        <tr>
                                            <td><b>TDS Limit:</b></td>
                                            <td><?=$account['tds_limit']?></td>
                                        </tr>                                    
                                    </tbody>

                                    <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><b>TDS Cess:</b></td>
                                            <td><?=$account['tds_cess']?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TDS HCess:</b></td>
                                            <td><?=$account['tds_hcess']?></td>
                                        </tr>
                                        <tr>
                                            <td><b>TDS Surcharge:</b></td>
                                            <td><?=$account['tds_surcharge']?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="tab-pane" id="tab2rev">
                        <div class="main-content-label tx-13 mg-b-20">
                            Other Info
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Gst Address:</b></td>
                                        <td><?=$account['gst_add']?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>Country:</b></td>
                                        <td><?=$account['country_name']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>City:</b></td>
                                        <td><?=$account['city_name']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Area:</b></td>
                                        <td><?=$account['area']?></td>
                                    </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Office Address:</b></td>
                                        <td><?=$account['address']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>State:</b></td>
                                        <td><?=$account['state_name']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Pin Code:</b></td>
                                        <td><?=$account['pin']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Reffer :</b></td>
                                        <td><?=$account['reffered_name']?></td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                       
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

<?= $this->endSection() ?>