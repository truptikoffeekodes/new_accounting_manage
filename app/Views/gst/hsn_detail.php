<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GSTR1</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
        <a href="<?=url('gst/hsn_summary_detail_xls_export?from='.$start_date.'&to='.$end_date.'&hsn='.$hsn)?>"
                    class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action = "<?=url('Gst/Hsn_detail/'.$hsn)?>">
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
                                        <input class="form-control fc-datepicker" name="from"
                                            placeholder="YYYY-MM-DD" type="text">
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
                                        <input class="form-control fc-datepicker" name="to"
                                            placeholder="YYYY-MM-DD" type="text">
                                        <!-- <input class="form-control" name="hsn"
                                            placeholder="" type="hidden"> -->
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
                                    <span style="size:20px;"><b><?=$title?></b></span>
                                    <br>
                                    <b id="start_date"><?=user_date($start_date)?></b> to
                                    <b id="end_date"><?=user_date($end_date)?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
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
                    <table class="table table-striped table-hover table-fw-widget" id="table_list_data" >
                        <thead>
                            <tr>
                                <th>SR No.</th>
                                <th>Invoice ID</th>
                                <th>Custom Invoice No.</th>
                                <th>Account Name</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Value</th>
                                <th>Taxable Value</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State/UT Tax Amount</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $total_taxable = 0;
                            $total_igst = 0;
                            $total_cgst = 0;
                            $total_sgst = 0;
                            $total_net_amt = 0;
                            $total_qty = 0;
                            
                            $i=1;

                            foreach ($hsn_detail as $row) {
                            
                            ?>
                            <tr>
                                <td><?= @$i?></td>

                                <?php

                                    if(isset($row['v_type']) && $row['v_type'] == 'general')
                                    {
                                    ?>
                                        <td><a href="<?=url('sales/add_ACinvoice/general/'.$row['parent_id'])?>"><?=$row['parent_id']?></a></td>
                                    <?php
                                    }else if(isset($row['v_type']) && $row['v_type'] == 'return'){?>
                                        <td><a href="<?=url('sales/add_ACinvoice/return/'.$row['parent_id'])?>"><?=$row['parent_id']?></a></td>

                                    <?php 
                                    }else if(!isset($row['v_type']) && $row['type'] == 'return')
                                    {
                                    ?>
                                     <td><a href="<?=url('sales/add_salesreturn/'.@$row['parent_id'])?>"><?=@$row['parent_id']?></a></td>
                               
                                    <?php
                                    }else { ?>
                                        <td><a href="<?=url('sales/add_salesinvoice/'.@$row['parent_id'])?>"><?=@$row['parent_id']?></a></td>

                                    <?php }
                                ?>
                                <td><?= @$row['invoice_detail']['custom_inv_no']?></td>
                                <td><?= @$row['invoice_detail']['account_name']?></td>

                                <?php
                                    if((isset($row['v_type']) && $row['v_type'] == 'general') || $row['type'] == 'invoice')
                                    {?>
                                <td><?=@$row['qty']?></td>
                                <td><?=number_format(@$row['rate'])?></td>
                                <td><?=number_format(@$row['rate'] * @$row['qty'])?></td>
                                <td><?=number_format(@$row['total_taxable'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['igst_amt'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['sgst_amt'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['cgst_amt'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['total_amt'], 2, '.', '')?></td>

                                <?php }else{ ?>

                                <td>-<?=@$row['qty']?></td>
                                <td>-<?=number_format(@$row['rate'])?></td>
                                <td>-<?=number_format(@$row['rate'] * @$row['qty'])?></td>
                                <td>-<?=number_format(@$row['total_taxable'], 2, '.', '')?></td>
                                <td>-<?=number_format(@$row['igst_amt'], 2, '.', '')?></td>
                                <td>-<?=number_format(@$row['sgst_amt'], 2, '.', '')?></td>
                                <td>-<?=number_format(@$row['cgst_amt'], 2, '.', '')?></td>
                                <td>-<?=number_format(@$row['total_amt'], 2, '.', '')?></td>

                                <?php  } ?>
                                <td></td>
                            </tr>
                           
                            <?php
                                if((isset($row['v_type']) && $row['v_type'] == 'return') || $row['type'] == 'return')
                                {

                                    $total_taxable -= (float)$row['total_taxable'];
                                    $total_igst -= (float)$row['igst_amt'];
                                    $total_cgst -= (float)$row['cgst_amt'];
                                    $total_sgst -= (float)$row['sgst_amt'];
                                    $total_net_amt -= (float)$row['total_amt'];
                                    $total_qty -= (float)$row['qty'];

                                }else{

                                    $total_taxable += (float)$row['total_taxable'];
                                    $total_igst += (float)$row['igst_amt'];
                                    $total_cgst += (float)$row['cgst_amt'];
                                    $total_sgst += (float)$row['sgst_amt'];
                                    $total_net_amt += (float)$row['total_amt'];
                                    $total_qty += (float)$row['qty'];


                                }

                            $i++;
                            }
                            ?>
                            <th>
                                <th colspan = "4"> TOTAL </th>
                                <th> <?=$total_qty?> </th>
                                <th>  </th>
                                <th><?=number_format($total_taxable,2)?></th>
                                <th><?=number_format($total_igst,2)?></th>
                                <th><?=number_format($total_cgst,2)?></th>
                                <th><?=number_format($total_sgst,2)?></th>
                                <th><?=number_format($total_net_amt,2)?></th>
                            </th>
                            
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

    /
    $("#table_list_data").DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "order": [
                [0, "desc"]
            ],
        "dom": "<'row be-datatable-header'<'col-sm-2'l><'col-sm-6 text-left'B><'col-sm-4 text-right'f>>" +
                "<'row be-datatable-body'<'col-sm-12'tr>>" +
                "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
        "buttons": [
            'copy', 'csv', 'excel', 'pdf'
        ],
        "lengthMenu": [
                [10, 25, 50, 100, 200, 300, -1],
                [10, 25, 50, 100, 200, 300, "All"]
        ]
    });

    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
    
</script>
<?= $this->endSection() ?>