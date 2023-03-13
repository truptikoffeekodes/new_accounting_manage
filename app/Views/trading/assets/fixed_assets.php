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
            <form method="get" action="<?=url('Balancesheet/get_fixedassets_account_data')?>">
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
                                        <input class="form-control fc-datepicker" id="" name="from" required
                                            placeholder="YYYY-MM-DD" type="text">
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
                                        <input class="form-control fc-datepicker" id="" name="to" required
                                            placeholder="YYYY-MM-DD" type="text">
                                            <input name="id" value="<?=@$id?>" type="hidden">
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
                                <span style="size:20px;"><b>Fixed Assets Voucher</b></span>
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
                                <td>Opening Balance</td>
                                <td><?=@$opening['total']?></td>   
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td><a href="<?=url('Balancesheet/fixedassets_banktrans_monthly_PerWise?from='.$from.'&to='.$to.'&id='.$id)?>">Bank/Cash Transaction</a></td>
                                <td><?=@$per_bank_trans['total']?></td>
                               
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td><a href="<?=url('Balancesheet/fixedassets_jv_monthly?from='.$from.'&to='.$to.'&id='.$id)?>">Journal Voucher</a></td>
                                <td><?=@$jv_fixedassets['total']?></td>
                               
                            </tr>
                            <tr>
                                <th scope="row">4</th>
                                <td><a href="<?=url('Balancesheet/fixedassets_salesinvoice_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Sales Invoice</a></td>
                                <td><?=@$income_fixedassets['total']?></td>
                            </tr>
                            <tr>
                                <th scope="row">5</th>
                                <td><a href="<?=url('Balancesheet/fixedassets_purchaseinvoice_monthly_AcWise?from='.$from.'&to='.$to.'&id='.$id)?>">Purchase Invoice</a></td>
                                <td><?=@$expence_fixedassets['total']?></td>
                            </tr>
                            
                            <tr>
                                <th scope="row" colspan="2"><b>TOTAL</b></th>
                                <td><b><?=@$expence_fixedassets['total'] + @$income_fixedassets['total'] + @$jv_fixedassets['total'] + @$per_bank_trans['total'] + @$opening['total']?></b></td>
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
                window.location = PATH + '/Trading/dashboard';
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