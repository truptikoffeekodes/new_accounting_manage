<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?= $title ?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Other</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>


</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card main-content-body-profile">
            <div class="card-header card-header-divider">
                <div class="table-responsive">
                    <table class="table main-table-reference mt-0 mb-0 text-center my_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Invoice No</th>
                                <th>Party Account</th>
                                <th>State Name</th>
                                <th>Invoice Type</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach($invoice_list as $row)
                            {
                                $total +=@$row['amount'];
                            ?>
                            <tr>
                                <td><?=@$row['id'];?></td>
                                <td><?=@$row['invoice_no'];?></td>
                                <td><?=@$row['ac_name'];?></td>
                                <td><?=@$row['state_name'];?></td>
                                <td><?=@$row['type'];?></td>
                                <td><?=user_date(@$row['invoice_date']);?></td>
                                <td><?=@$row['amount'];?></td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        <tfooter>
                            <tr>
                                <th colspan="6">Total</th>
                                <th>
                                    <?= number_format($total, 2) ?>
                                </th>

                            </tr>
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
    $("#table_list_data2").DataTable();
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });

    //datatable_load('');
    $('.dateMask').mask('99-99-9999');
    $('.select2').select2({
        //minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });
    $(".my_table").DataTable({
        "order": [
            [2, "asc"]
        ],
    });

});
</script>
<?= $this->endSection() ?>