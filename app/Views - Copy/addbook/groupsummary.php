<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Account Book</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= url('') ?>">Group Summary</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=@$title;?></li>
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
    <div class="responsive-background">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="advanced-search">
                <?php
            //  $request = \Config\Services::request();
            //  $uri = $request->uri;
            //  $c = $uri->getSegment(3);
            //  //print_r($c);exit;
        ?>
                <form method="post" action="<?=url('Addbook/Groupsummary')?>">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="row">
                               
                               
                                <div class="col-lg-12 form-group">
                                        <label class="form-label">Gl Group: <span class="tx-danger"></span></label>
                                        <div class="input-group">
                                            <select class="form-control select2" id="" onchange=""
                                                name="glgroup_id">
                                                <option value="">None</option>
                                                <?php
                                                if(!empty($gl_group))
                                                    foreach($gl_group as $row)
                                                    {
                                                ?>
                                                
                                                <option value="<?=@$row['id']?>">
                                                    <?=@$row['name']?>
                                                </option>
                                                <?php } ?>
                                            </select>
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
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">
                   

                    <div class="responsive-background">
                        <div>
                            <div class="advanced-search">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                                            <table class="table mg-b-0">
                                                                <thead>
                                                                    <tr>
                                                                    
                                                                        <th>Gl Group Name</th>
                                                                        <th>Credit</th>
                                                                        <th>Debit</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        foreach($glgroup as $key=>$value)
                                                                        {
                                                                            $debit_total=@$value['salesinvoice_value'] + @$value['salesinvoice_generaltotal'] + @$value['purchasreturn_total'] + @$value['purchasereturn_generaltotal'] + @$value['payment_total'] + @$value['cashdebit_total'] + @$value['bankdebit_total'] + @$value['journaldebit_total'];
                                                                                
                                                                            $credit_total=@$value['purchaseinvoice_total'] + @$value['purchaseinvoive_generaltotal'] + @$value['salesreturn_total'] + @$value['salesreturn_generaltotal'] + @$value['receipt_total'] + @$value['cashcredit_total'] + @$value['bankcredit_total'] + @$value['journalcredit_total'];
                                                                               
                                                            
                                                                    ?>
                                                                    <tr>
                                                                        <td><?=@$key;?></td>
                                                                        <td><?=@$credit_total;?></td>
                                                                        <td><?=@$debit_total;?></td>
                                                                       
                                                                    </tr>
                                                                    <?php 
                                                                        }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');

    $('.select2').select2({
        minimumResultsForSearch: Infinity,
        placeholder: 'Choose one',
        width: '100%'
    });

    $('#bills').on('select2:select', function(e) {
        var data = e.params.data;

        $('#bill_tb').val(data.table);
    });
});
</script>

<?= $this->endSection() ?>