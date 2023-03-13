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
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action = "<?=url('Gst/Hsn_summary')?>">
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
                                    <b id="start_date"><?=user_date($start_date)?></b> to
                                    <b id="end_date"><?=user_date($end_date,2)?></b>

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
                    <table class="table table mg-b-0">
                        <thead>
                            <tr>
                                <th>HSN</th>
                                <th>UQC</th>
                                <th>Total Quantity</th>
                                <th>Total Value</th>
                                <th>Rate</th>
                                <th>Taxable Value</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State/UT Tax Amount</th>
                                <th>Cess Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $row) {

                                $total_qty = (@$total_qty ? $total_qty :0) + $row['qty']; 
                                $total_val = (@$total_val ? $total_val :0) + $row['val']; 
                                $total_txval = (@$total_txval ? $total_txval :0) + $row['txval']; 
                                $total_iamt = (@$total_iamt ? $total_iamt :0) + $row['iamt']; 
                                $total_samt = (@$total_samt ? $total_samt :0) + $row['samt']; 
                                $total_camt = (@$total_camt ? $total_camt :0) + $row['camt']; 
                            ?>
                            <tr>
                                
                                <td><a href="<?=url('gst/hsn_detail/'.$row['hsn_sc'].'/'.$row['rate'].'?from='.$start_date.'&to='.$end_date)?>"><?=$row['hsn_sc']?></a></td>
                                <td><?= @$row['uqc']?></td>
                                <td><?=@$row['qty']?></td>
                                <td><?=number_format(@$row['val'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['rate'])?></td>
                                <td><?=number_format(@$row['txval'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['iamt'], 2, '.', '')?></td>
                                <td><?=number_format(@$row['camt'], 2, '.', '')?>
                                </td>
                                <td><?=number_format(@$row['samt'], 2, '.', '')?>
                                </td>
                                <td>
                                </td>
                            </tr>
                           <?php
                            }
                            ?>
                            <tr>
                                <th colspan = "3"> Total</th>
                                <th><?=number_format($total_val,2)?></th>
                                <th></th>
                                <th><?=number_format($total_txval,2)?></th>
                                <th><?=number_format($total_iamt,2)?></th>
                                <th><?=number_format($total_camt,2)?></th>
                                <th><?=number_format($total_samt,2)?></th>
                                <th></th>
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