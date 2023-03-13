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
        <a href="<?=url('gst/gstr1_nill_xls_export?from='.$start_date.'&to='.$end_date.'&type='.@$type)?>"
            class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
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
                                    <b id="start_date"><?=$start_date?></b> to
                                    <b id="end_date"><?=$end_date?></b>

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
                    <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id=""
                        data-module="" data-filter_data=''>
                        <thead>
                            <tr>
                                <th>SI NO.</th>
                                <th>Particular</th>
                              
                                <th>Taxable Amount</th>
                                <th>Invoice Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($type == 'inter_reg'){
                                    $result = $inter_reg['data'];
                                }elseif($type == 'intera_reg'){
                                    $result = $intera_reg['data'];
                                }elseif($type == 'inter_unreg'){
                                    $result = $inter_unreg['data'];
                                }else{
                                    $result = $intera_unreg['data'];
                                }
                                $total_taxable =0;
                                $total_amount =0;
                                $total_count = count($result);
                                foreach($result as $row){
                                    if(isset($row['return_no']) || @$row['v_type'] == 'return'){
                                        $total_taxable -=$row['taxable']; 
                                        $total_amount -=$row['net_amount'];
                                    }else{
                                        $total_taxable +=$row['taxable']; 
                                        $total_amount +=$row['net_amount'];
                                    }
                            ?>
                            <tr>
                                <th><?=@$row['invoice_no'] ? $row['invoice_no'] : $row['return_no'] ?></th>
                                <?php if($row['type'] == 'sales'){
                                        if(isset($row['return_no'])){
                                    ?>
                                        <td><a href="<?=url('sales/add_salesreturn/'.$row['id'])?>"><?=@$row['name']?></a></td>
                                    <?php }else{ ?>
                                        <td><a href="<?=url('sales/add_salesinvoice/'.$row['id'])?>"><?=@$row['name']?></a></td>

                                    <?php } ?>
                                <?php }else{?>
                                <td><a href="<?=url('sales/add_ACinvoice/'.$row['v_type'].'/'.$row['id'])?>"><?=@$row['name']?></a></td>
                                <?php } ?>
                                <td><?=isset($row['return_no']) || @$row['v_type'] == 'return' ? '-' :''?><?=number_format(@$row['taxable'],2)?></td>
                                <td><?=isset($row['return_no']) || @$row['v_type'] == 'return' ? '-' :''?><?=number_format(@$row['net_amount'],2)?></td>
                            </tr>
                            <?php } ?>
                          
                        </tbody>
                        <tfooter>
                                <th>Total</th>
                                <th><?=@$total_count?></th>
                                
                                <th><?=number_format(@$total_taxable,2)?></th>
                                <th><?=number_format(@$total_amount,2)?></th>
                               
                        </tfooter>
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
    $('#table_list_data').DataTable();
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
});
</script>
<?= $this->endSection() ?>