<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Transaction</a></li>
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
            <form method="post" id="date_submit" class="">
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
                                        <input class="form-control dateMask" id="dateMask" name="from"
                                            placeholder="DD-MM-YYYY" type="text">
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
                                        <input class="form-control dateMask" id="dateMask" name="to"
                                            placeholder="DD-MM-YYYY" type="text">
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
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b>Bank Reconciliation</b></span>
                                    <br>
                                    <?php
                                                 $from = date_create($start_date) ;                                         
                                                 $to = date_create($end_date);                              
                                                 
                                            ?>
                                    <b><?=date_format($from,"d/m/Y"); ?></b> to
                                    <b><?=date_format($to,"d/m/Y"); ?></b>

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
                <form method="post" action="<?=url('bank/update_recons')?>" class="ajax-form-submit">
                    <div class="table-responsive">
                        <table class="table table mg-b-0">
                            <thead>
                                <tr>
                                    <th>ID.</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Payment Mode</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Reconciliation Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($bank)){
                                    // echo '<pre>';print_r($sales_return);
                                    foreach($bank as $row) {
                                    $date=date_create($row['date']);
                                    $fdate=date_format($date,"d-m-Y");
                                    $re_date=date_create($row['recons_date']);
                                    $recon_date = date_format($re_date,"d-m-Y");
                                ?>
                                <tr>
                                    <td><?=@$row['id'];?></td>
                                    <td><?=@$fdate;?></td>
                                    <td><?=@$row['account_name'];?></td>
                                    <td><?=@$row['mode'];?></td>
                                    <?php 
                                        if($row['mode']=='Receipt')
                                        {
                                    ?>
                                    <td><?=@$row['amount'];?></td>
                                    <td></td>
                                    <?php
                                    }else{
                                    ?>
                                    <td></td>
                                    <td><?=@$row['amount'];?></td>
                                    <?php
                                    }
                                    ?>
                                    <td>
                                        <input type="text" class="form-control dateMask" name="recon_date[]"
                                            placeholder="Enter Date"
                                            value="<?=(@$row['recons_date'] != '0000-00-00') ? $recon_date : '' ;?>">
                                        <input type="hidden" name="id[]" value="<?=@$row['id']?>">
                                    </td>
                                </tr>
                                <?php }  } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="table-responsive col-md-6 mt-25">
                        <table class="table table-hover table-bordered table-fw-widget">
                            <tr>
                                <th>DIFF</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?=$total['bankcredit_total']?></td>
                                <td><?=$total['bankdebit_total']?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row mt-3 mr-3" style="float:right;">
                        <input class="btn btn-space btn-primary btn-product-submit" id="save_data" type="submit"
                            value="Submit">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endsection() ?>

<?= $this->section('scripts') ?>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css"
    href="<?=ASSETS?>/plugins/x-editable/jqueryui-editable/css/jqueryui-editable.css">
<script type="text/javascript" src="<?=ASSETS?>/plugins/x-editable/jqueryui-editable/js/jqueryui-editable.min.js">
</script>
<script type="text/javascript">
$.fn.editable.defaults.mode = 'inline';

$('.ajax-form-submit').on('submit', function(e) {
    $('#save_data').prop('disabled', true);
    $('.error-msg').html('');
    $('.form_proccessing').html('Please wait...');
    e.preventDefault();
    var aurl = $(this).attr('action');
    $.ajax({
        type: "POST",
        url: aurl,
        data: $(this).serialize(),
        success: function(response) {
            if (response.st == 'success') {
                swal("success!", "Your Data update successfully!", "success");
                window.location = "<?=url('Bank/reconciliation')?>"
            } else {
                $('.form_proccessing').html('');
                $('#save_data').prop('disabled', false);
                $('.error-msg').html(response.msg);
            }
        },
        error: function() {
            $('#save_data').prop('disabled', false);
            alert('Error');
        }
    });
    return false;
});
$(document).ready(function() {
    $('.dateMask').mask('99-99-9999');

    $('.date').editable({
        type: 'text',
        method: 'Post',
        name: 'name',
        url: PATH + 'Master/editable_update',
        title: 'Enter Update',
    }).on('shown', function(e, editable) {
        editable.input.$input.mask('99-99-9999');

    });
});
</script>
<?= $this->endsection() ?>