<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
     th{
        white-space: nowrap!important;
    }
    td{
        white-space: nowrap!important;
    }
    /* td:first-child {
        font-weight: 700!important;
    } */

    td:nth-child(2) {
        font-weight: 700!important;
    }
    td:nth-child(5) {
        font-weight: 700!important;
    }
    </style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?= $title ?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Other</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
    <div class="btn btn-list">

        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
        <a href="<?=url('Testing/tds_xls_export?'.'account_id='.$ac_id.'&month='.$month.'&year='.$year)?>"  class="btn ripple btn-primary"><i class="fe fe-external-link"></i> Excel Export</a>
       

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
                                    <label class="">Account :</label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="party_account" onchange="" name="account_id" required>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Month :</label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="month" onchange="" name="month" required>
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-lg-0">
                                    <label class="">Year :</label>
                                    <div class="input-group">
                                        <select class="form-control select2" id="year" onchange="" name="year" required>
                                            <option value="1997">1997</option>
                                            <option value="1998">1998</option>
                                            <option value="1999">1999</option>
                                            <option value="2000">2000</option>
                                            <option value="2001">2001</option>
                                            <option value="2002">2002</option>
                                            <option value="2003">2003</option>
                                            <option value="2004">2004</option>
                                            <option value="2005">2005</option>
                                            <option value="2006">2006</option>
                                            <option value="2007">2007</option>
                                            <option value="2008">2008</option>
                                            <option value="2009">2009</option>
                                            <option value="2010">2010</option>
                                            <option value="2011">2011</option>
                                            <option value="2012">2012</option>
                                            <option value="2013">2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option selected value="2022">2022</option>
                                            <option value="2023">2023</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </br>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">Reset</a>
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
                    <table class="table table-striped table-hover table-fw-widget" id="table_list_data" data-id="" data-module="" data-filter_data='' style="width: 100%;">

                        <thead>
                            <tr>
                                <?php
                                if(!empty($tds))
                                {
                                    foreach ($tds['header_account_name'] as $row) {
                                    ?>
                                        <th><?= $row; ?></th>
                                    <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <th></th>
                                    <?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             if(!empty($tds))
                             {
                                foreach($tds['invoice_list'] as $row)
                                {
                                    ?>
                                <tr>
                                    <?php
                                    foreach($tds['header'] as $key=>$value)
                                    {
                                    ?>
                                        <td><?= @$row[$key];?></td>     
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                }
                            }
                           ?>
                        </tbody>
                        <tfoot>
                            <?php
                            if(!empty($tds))
                            {
                               // echo '<pre>';Print_r($tds['total']);exit;
                                
                            ?>
                            
                                <?php 
                                
                                   //echo '<pre>';Print_r(count($tds['total']));exit;
                                   //$l=4;
                                    for($i=0;$i<count($tds['total']);$i++)
                                    {
                                        
                                       // echo '<pre>';Print_r($tds['total'][$i]);
                                        
                                ?>
                                     <th><b><?=@$tds['total'][$i] ? number_format((float)@$tds['total'][$i],2,".",""):''?></b></th>     
                                <?php
                                //$l++;
                                    }
                                    //exit;
                                ?>
                            <?php
                            }
                            ?>

                            </tfoot>

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
                    // console.log(response);
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