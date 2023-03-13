<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Gl Group</h2>
            <ol class="breadcrumb">

                <li class="breadcrumb-item active" aria-current="page"><?= @$title; ?></li>
            </ol>
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

                <form method="post" action="<?= url('Addbook/closing_bal_report') ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            FROM:
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" id="dateMask" name="from" placeholder="YYYY-MM-DD" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-lg-0">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            TO:
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" id="" name="to" placeholder="YYYY-MM-DD" type="text">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card main-content-body-profile">
                <div class="card-header card-header-divider">
                    <div class="card-body tab-content h-100">
                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table-fw-widget">
                                    <tr>
                                        <td>
                                           
                                            </br>
                                            <?php
                                            $from = date_create(@$start_date);
                                            $to = date_create(@$end_date);

                                            ?>
                                            <b><?= date_format(@$from, "d/m/Y"); ?></b> to
                                            <b><?= date_format(@$to, "d/m/Y"); ?></b>

                                        </td>
                                    </tr>
                                    <tr colspan="4">
                                    </tr>
                                </table>
                            </div>
                            <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id="" data-module="" data-filter_data=''>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Closing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $closing = 0;
                                    if (!empty($gl_summary)) {
                                        foreach ($gl_summary as $key=>$value) {
                                            if(@$value['total'] != 0)
                                            {
                                    ?>
                                            <tr>
                                                <td><?= @$value['account_id']; ?></td>
                                                <td><a href="<?=url('Addbook/closing_bal_account_report?account_id='.@$value['account_id'].'&type='.@$value['type'].'&from='.@$start_date.'&to='.@$end_date)?>"><?=@$key?></a></td>
                                                <td><?= number_format(@$value['total'],2);?></td>           
                                            </tr>
                                    <?php
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
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table_list_data').DataTable();
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

        $("#account").select2({
            width: '100%',
            placeholder: 'Type Account',
            ajax: {
                url: PATH + "Master/Getdata/search_sun_debtor",
                type: "post",
                allowClear: true,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    });
</script>

<?= $this->endSection() ?>