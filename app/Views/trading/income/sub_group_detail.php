<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div>
        <div class="col-lg-12">
            <h2 class="main-content-title tx-24 mg-b-5"><?=$title?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?=@$type == 'pl' ? 'Profit/Loss' :'Trading' ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
            </ol>
        </div>
    </div>

    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="get" action="<?= url('Profitloss/get_income_sub_grp') ?>">

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
                                        <input type="hidden" name="id" value="<?= @$ac_id ?>">
                                        <input type="hidden" name="name" value="<?= @$ac_name ?>">
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
<!--Start Navbar -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tr>
                            <td>
                                <span style="size:20px;"><b><?=$title?></b></span>
                                </br>
                                <?php
                                    $from =date_create($date['from']) ;                                         
                                    $to = date_create($date['to']);
                                ?>
                                <b><?=date_format($from,"d/m/Y"); ?></b> to
                                <b><?=date_format($to,"d/m/Y"); ?></b>

                            </td>
                        </tr>
                        <tr colspan="4">
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table main-table-reference mt-0 mb-0 text-center">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                                $total = 0;
                                foreach($trading['inc'] as $key => $value) { ?>
                            <tr>
                                <td><b><?=@$value['name']?></b></td>
                                <td></td>
                                <td><b><?=@$trading['inc_total']?></b><br>
                                  
                                </td>
                            </tr>

                            <?php   
                                if(!empty($value['account'])) {
                                    foreach(@$value['account'] as $ac_key => $ac_value){ ?>
                            <tr>
                                <td><a href="<?=url('Trading/get_income_account_data?from='.$date['from'].'&to='.$date['to'].'&id='.$ac_value['account_id'].'&type='.$type)?>"><?=$ac_key ?></a>
                                </td>
                                <td><?=$ac_value['total'] ?>
                                  
                                </td>
                                <td> </td>
                            </tr>
                            <?php 
                                    }    
                                }
                            ?>

                            <?php 
                                if(!empty($value['sub_categories'])) {
                                    foreach(@$value['sub_categories'] as $sub_key => $sub_value){
                                        $total = 0;
                                        $arr[$sub_key] = $sub_value;
                                        $total = subGrp_total($arr,0);                          
                            ?>
                            <tr>
                                <td><a href="<?=url('Profitloss/get_income_sub_grp?id='.$sub_key.'&name='.$sub_value['name'].'&from='.$date['from'].'&to='.$date['to'].'&type='.$type)?>"><?=$sub_value['name']?></a>
                                </td>
                                <td><?=$total ?>
                                   
                                </td>
                                <td> </td>
                            </tr>
                            <?php 
                                    unset($arr);
                                    }
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
</script>
<?= $this->endSection() ?>