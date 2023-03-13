<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>

<!-- End Page Header -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-header card-header-divider">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-fw-widget">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name </th>
                                    <th>Type </th>
                                    <th>Total Amount</th>
                                    <th>Total Meter</th>
                                    <th>AVG Rate</th> 
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($stock)){ 
                                    foreach($stock as $row){ ?> 
                                    <tr>
                                        <td><?=$row['item_id']?></td>
                                        <th><?=$row['item_name']?> </th>
                                        <td><?=$row['type']?> </td>
                                        <td><?=$row['total_taxable']?></td>
                                        <td><?=$row['total_meter']?></td>
                                        <th><?=$row['avg_price']?></th> 
                                    </tr>
                                    <?php } ?>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                    <th>ID</th>
                                    <th>Name </th>
                                    <th>Type</th>
                                    <th>Total Amount</th>
                                    <th>Total Meter</th>
                                    <th>AVG Rate</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
$(document).ready(function() {
   
   
});



</script>

<?= $this->endSection() ?>