<?=$this->extend(THEME . 'templete')?>

<?=$this->section('content')?>
<style>
.voucher {
    width: 120%;
    table-layout: fixed;
    border-collapse: collapse;
    margin-bottom: 5px;
}

.table-responsive::-webkit-scrollbar {
    width: 3px;
    height: 12px;
    transition: .3s background;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e1e6f1;
}

</style>
<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>

<div class="responsive-background">
    <div class="navbar-collapse collapse show" id="navbarSupportedContent" style="">
        <form method="post" action ="<?=url('Stock/jobRec_voucher_detail/').$item_id?>">
            <div class="advanced-search">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                FROM:
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="from_date" name="from_date"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                TO:
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" name="to_date" id="to_date" placeholder="DD-MM-YYYY"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-fw-widget">
                                <tbody><tr>
                                    <td>
                                        <span style="size:20px;"><b><?=$title?></b></span>
                                    </td>
                                </tr>
                                <tr colspan="4">
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-striped table-hover voucher" id="table_list_data">

                            <thead>
                                <tr>
                                    <td style="border:0px;"></td>
                                    <td style="border:0px;"></td>
                                    <td style="border:0px;"></td>
                                    <td style="border:0px;"></td>
                                    <td colspan="2" style="border:1px solid black;">
                                        <h6>
                                            <center> Inward</center>
                                        </h6>
                                    </td>
                                   
                                </tr>

                                <tr>
                                    <th>SR.NO</th>
                                    <th>Jobwork/Party Name</th>
                                    <th>Date </th>
                                    <th>Item </th>
                                    <th>PCS</th>
                                    <th>QTY(MTR)</th>
                                    <th>Voucher Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $inward_pcs = 0;
                                    $inward_meter = 0;
                                    $outward_pcs = 0;
                                    $outward_meter = 0;

                                    foreach ($stock as $row) {
                                      
                                        $url = 'Milling/Add_rec_jobwork/';
                                      
                                ?>
                                <tr>
                                    <td><?=$row['id']?></td>
                                    <td><a href="<?=url($url . $row['id'])?>"><?=$row['account_name']?></a></td>
                                    <td><?=user_date($row['date'])?></td>
                                    <td><?=$row['item_name'] . '<br>(' . $row['hsn'] . ')'?></td>
                                    <?php
                                        
                                    $inward_pcs += $row['pcs'];
                                    $inward_meter += $row['meter'];
                                    ?>
                                    <td><?=$row['pcs']?></td>
                                    <td><?=$row['meter']?></td>
                                    <td><?=$row['voucher_type']?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                            <tfoot>

                                    <th colspan="4">CLOSING TOTAL</th>
                                    <th><?=$inward_pcs?></th>
                                    <th><?=$inward_meter?></th>
                                    <th></th>
                                </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<?=$this->endSection()?>

<?=$this->section('scripts')?>

<script type="text/javascript">

    $(document).ready(function() {
        datatable_load('');

        $('.dateMask').mask('99-99-9999');
    });

    function datatable_load(filter_val) {

        $("#table_list_data").DataTable();

    }

</script>

<?=$this->endSection()?>