<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Balancesheet</a></li>
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
            <form method="get" action="<?=url('Balancesheet/get_currentassets_account_data')?>">
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
                                        <input class="form-control fc-datepicker" name="from" placeholder="YYYY-MM-DD" type="text">
                                
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
                                        <input class="form-control fc-datepicker" name="to" placeholder="YYYY-MM-DD" type="text">
                                
                                            <input type="hidden" name="id" value="<?=@$id?>">
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
                                <span style="size:20px;"><b><?=@$ac_name?></b></span>
                                </br>
                                  
                                <b><?=user_date($from)?></b> to
                                <b><?=user_date($to); ?></b>


                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped mg-b-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Voucher Name</th>
                                <th>Total Taxable</th>
                               
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Opening Bal</td>
                                <td><?=number_format(@$opening['total'],2)?></td>
                            </tr> 
                            <tr>
                                <th scope="row">2</th>
                                <td><a href="<?=url('Balancesheet/currentassets_banktrans_monthly_PerWise?from='.$from.'&to='.$to.'&id='.$id)?>">Perticular Bank/Cash Transaction</a></td>
                                <td><?=number_format(@$per_bank_trans['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">3</th>
                                <td><a href="<?=url('Balancesheet/currentassets_jv_monthly?from='.$from.'&to='.$to.'&id='.$id)?>">Journal Voucher</a></td>
                                <td><?=number_format(@$jv_currentassets['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">4</th>
                                <td><a href="<?=url('Balancesheet/currentassets_banktrans_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Account Bank/Cash Transaction</a></td>
                                <td><?=number_format(@$ac_bank_trans['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">5</th>
                                <td><a href="<?=url('Balancesheet/currentassets_salesinvoice_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Sales Invoice</a></td>
                                <td><?=number_format(@$sales_invoice['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">6</th>
                                <td><a href="<?=url('Balancesheet/currentassets_salesreturn_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Sales Return</a></td>
                                <td><?=number_format(@$sales_return['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">7</th>
                                <td><a href="<?=url('Balancesheet/currentassets_gnrl_sales_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">General Sales</a></td>
                                <td><?=number_format(@$general_sales['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">8</th>
                                <td><a href="<?=url('Balancesheet/currentassets_gnrl_salesreturn_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">General Sales Return</a></td>
                                <td><?=number_format(@$general_sales_return['total'],2)?></td>
                            </tr>

                            <tr>
                                <th scope="row">9</th>
                                <td><a href="<?=url('Balancesheet/currentassets_contra_monthly_PerWise?from='.$from.'&to='.$to.'&id='.$id)?>">Perticular Contra Transaction</a></td>
                                <td><?=number_format(@$per_contra_trans['total'],2)?></td>
                            </tr>
                            <tr>
                                <th scope="row">10</th>
                                <td><a href="<?=url('Balancesheet/currentassets_contra_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Account Contra Transaction</a></td>
                                <td><?=number_format(@$ac_contra_trans['total'],2)?></td>
                            </tr>
                            
                        </tbody>
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

$('#abc').click(function() {
    abc = $('#abc').val();

    if (abc == 1) {
        var data = 0;
        $('#abc').val('0');
    } else {
        var data = 1;
        $('#abc').val('1');
    }
    var url = PATH;
    $.ajax({
        url: PATH + '/company/update_company',
        type: 'POST',
        data: {
            'id': data
        },
        success: function(response) {
            if (response.st == 'success') {
                swal("success!", "Your update successfully..!!", "success");
                window.location = PATH + '/Balancesheet';
            } else {
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            alert('Error');
        }
    });

});
</script>
<?= $this->endSection() ?>