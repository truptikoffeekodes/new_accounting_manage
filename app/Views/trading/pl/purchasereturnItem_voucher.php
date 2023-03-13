<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?= $title ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trading</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
        <a href="<?=url('Trading/sales_item_xls?from='.$date['from'].'&to='.$date['to'])?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i>Excel Export</a>

    </div>
</div>
<!--Start Navbar -->

<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action="<?= url('Profitloss/purchase_return_voucher_wise') ?>">
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
                                        <input class="form-control fc-datepicker" name="from" required placeholder="YYYY-MM-DD" type="text">
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
                                        <input class="form-control fc-datepicker" name="to" required placeholder="YYYY-MM-DD" type="text">
                                        <input type="hidden" name="id" value="<?= @$id ?>">
                                        <input type="hidden" name="type" value="<?= @$type ?>">
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Reset</a>

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
                                <span style="size:20px;"><b>Sales Voucher</b></span>
                                </br>
                                <?php
                                $from = date_create($date['from']);
                                $to = date_create($date['to']);
                                ?>
                                <b><?= date_format($from, "d/m/Y"); ?></b> to
                                <b><?= date_format($to, "d/m/Y"); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table main-table-reference mt-0 mb-0 text-center my_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 50px;">Date</th>
                               
                                <th>Party Name</th>
                                <th>Vch Type</th>
                                <th>Vch No</th>
                                <th>Debit</th>
                                <th>Closing Bal.</th>
                            </tr>
                        </thead>

                        <tbody>
                        
                            <?php
                           
                            //$closing =$opening_balance;
                            $new = 0;
                            $debit = 0;
                            $credit = 0;
                            //$count = count($sales);
                           // echo '<pre>';Print_r($sales);exit;
                            
                            
                            foreach ($purchase_return as $row) { 
                                //echo '<pre>';Print_r($row);exit;
                                $new += $row['taxable'];
                                ?>
                                <tr>
                                    <td style="width: 50px;"><?= user_date($row['date']) ?></td>
                                   
                                    <td><a href="<?= url('Purchase/add_purchasereturn/' . $row['id']) ?>"><?= $row['party_name'] ?></a></td>
                                    <td>Purchase Return Item</td>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= number_format(@$row['taxable'], 2) ?></td>
                                    <td><?= number_format($new,2) ?></td>
                                </tr>
                            <?php
                               // $debit += @$row['taxable'];
                            } ?>

                        </tbody>
                        <tr>
                                <th>Closing</th>
                                <th colspan="4"></th>
                                <th><?= number_format($new , 2) ?></th>


                               

                            </tr>
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
        $(".my_table").DataTable({
            "order": [
                [3, "asc"]
            ],
        });
    });
</script>
<?= $this->endSection() ?>