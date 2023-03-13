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
                    <table class="table table-striped table-hover" id="table_list_data2" data-id="party_inv_list" data-module="Testing" data-filter_data="<?=@$jv_id?>" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Jv Id</th>
                                <th>Account</th>
                                <th>Invoice No</th>
                                <th>Invoice Type</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>

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

        datatable_load('');
        $('.dateMask').mask('99-99-9999');
        $('.select2').select2({
            //minimumResultsForSearch: Infinity,
            placeholder: 'Choose one',
            width: '100%'
        });
        $("#party_account").select2({

            width: '100%',
            placeholder: 'Type Party Account',
            ajax: {
                url: PATH + "Master/Getdata/search_account",
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
        $("#party_account_new").select2({

            width: '100%',
            placeholder: 'Type Party Account',
            ajax: {
                url: PATH + "Master/Getdata/search_account",
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
                    // console.log(response);
                    return {
                        results: response

                    };
                },
                cache: true
            }
        });
    });

    function datatable_load(filter_val) {

        $("#table_list_data2").DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "order": [
                [0, "desc"]
            ],
            "dom": "<'row be-datatable-header'<'col-sm-2'l><'col-sm-6 text-left'B><'col-sm-4 text-right'f>>" +
                "<'row be-datatable-body'<'col-sm-12'tr>>" +
                "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons": [
                'copy', 'csv', 'excel', 'pdf'
            ],
            "ajax": {
                "type": "POST",
                "url": PATH + "/" + $("#table_list_data2").data('module') + "/Getdata/" + $("#table_list_data2").data('id') + '?filter_data=' + $("#table_list_data2").data('filter_data')
            },
            "lengthMenu": [
                [10, 25, 50, 100, 200, 300, -1],
                [10, 25, 50, 100, 200, 300, "All"]
            ],
            "footerCallback": function(row, data, start, end, display) {

            }
        });
    }
</script>
<?= $this->endSection() ?>