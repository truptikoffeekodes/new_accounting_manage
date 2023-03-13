<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Other</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="<?=url('Testing/hsn_core_xls_export?from='.@$from.'&to='.@$to.'&type='.$type)?>"
            class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
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
            <form method="post" id="date_submit">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Type :</label>
                                    <div class="input-group">
                                       
                                        <select class="form-control select2" name="type" required>
                                            <option value="sales_invoice">Sales Invoice</option>
                                            <option value="sales_return">Sales Return</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="from" placeholder="YYYY-MM-DD"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control fc-datepicker" name="to" placeholder="YYYY-MM-DD"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </br>
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
                                    <span style="size:20px;"><b>Testing</b></span>
                                    <br>
                                    <b id="start_date"><?=user_date(@$invoice_data['start_date']);?></b> to
                                    <b id="end_date"><?=user_date(@$invoice_data['end_date']);?></b>

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
                                <th>Date</th>
                                <th>Voucher Type</th>
                                <th>Custome Inv No</th>
                                <th>Account Name</th>
                                <th>Taxability</th>
                                <th>Type</th>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Uom</th>
                                <th>QTY</th>
                                <th>Rate</th>
                                <th>Igst</th>
                                <th>Igst Amount</th>
                                <th>cgst Amount</th>
                                <th>sgst Amount</th>
                                <th>Item Discount</th>
                                <th>HSN</th>
                                <th>Taxes</th>
                                <th>Gst No</th>
                                <th>Discount Type</th>
                                <th>Discount</th>                                
                                

                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($invoice_data))
                            {
                                $data = $invoice_data['sales'];
                                    foreach($data as $row)
                                    {
                                ?>
                                <tr>
                                    <td><?=$row['parent_id'];?></td>
                                    <td><?=$row['date'];?></td>
                                    <td><?=$row['vch_type'];?></td>
                                    <td><?=$row['cinv_no'];?></td>
                                    <td><?=$row['account_name'];?></td>
                                    <td><?=$row['taxability'];?></td>
                                    <td><?=$row['type'];?></td>
                                    <td><?=$row['item_id'];?></td>
                                    <td><?=$row['name'];?></td>
                                    <td><?=$row['uom'];?></td>
                                    <td><?=$row['qty'];?></td>
                                    <td><?=$row['rate'];?></td>
                                    <td><?=$row['igst'];?></td>
                                    <td><?=$row['igst_amt'];?></td>
                                    <td><?=$row['cgst_amt'];?></td>
                                    <td><?=$row['sgst_amt'];?></td>
                                    <td><?=$row['item_disc'];?></td>
                                    <td><?=$row['hsn'];?></td>
                                    <td><?=$row['taxes'];?></td>
                                    <td><?=$row['gst'];?></td>
                                    <td><?=$row['disc_type'];?></td>
                                    <td><?=$row['discount'];?></td> 
                                </tr>
                                <?php
                                }
                            }
                            ?>
                           
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
       $('#table_list_data').DataTable();
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