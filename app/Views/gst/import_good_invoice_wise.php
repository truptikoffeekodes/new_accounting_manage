<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trading</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
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
            <form method="get" action="<?=url('Gst/import_goods_invoice_wise')?>">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <!-- <label class="">From :</label> -->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                FROM:
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="from" required
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <!-- <label class="">To :</label> -->
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                TO:
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="to" required
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
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tr>
                            <td>
                                <span style="size:20px;"><b>Purchase Voucher</b></span>
                                </br>
                                <?php
                                    $from =date_create($date['from']) ;                                         
                                    $to = date_create($date['to']);
                                    
                                    
                                ?>
                                <b><?=user_date($date['from']); ?></b> to
                                <b><?=user_date($date['to']); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>
                
                <div class="table-responsive">
                    <table class="table main-table-reference mt-0 mb-0 text-center">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Vch Type</th>
                                <th>Vch No</th>
                                <th>Supplier NO.</th>
                                <th>Taxable</th>
                                <th>GST</th>
                                <th>Narration</th>
                                
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                           $total_taxable = 0;
                           $total_gst = 0;
                            foreach($data as $row){ ?> 
                            <tr>
                                <td><?=user_date($row['invoice_date'])?></td>
                                <td><a href ="<?=url('purchase/add_purchaseinvoice/'.$row['id'])?>"><?=$row['name']?></a></td>
                                <td>Purchase Item</td>
                                <td><?=$row['invoice_no']?></td>
                                <td><?=$row['supply_inv']?></td>
                                <td><?=number_format($row['import_taxable'],2)?></td>
                                <td><?=number_format($row['import_gst'],2)?></td>
                                <td><?=$row['other']?></td>
                            </tr>
                            <?php 
                           $total_taxable += $row['import_taxable'];
                           $total_gst += $row['import_gst'];
                            } ?>
                            <tfooter>
                                <th colspan = "5"><center>TOTAL</center></th>
                                <th><?=$total_taxable?></th>
                                <th><?=$total_gst?></th>
                                <th></th>
                            </tfooter>
                            
                        </tbody>

                        <tfooter>
                            
                        </tfooter>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--End Navbar -->




<?= $this->endSection() ?>
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

</script>
<?= $this->endSection() ?>