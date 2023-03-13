<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>

<div class="row">
<div class="col-lg-12">
    <div class="card custom-card">
        <div class="card-header card-header-divider">
            <div class="card-body">
                <form action="<?= url('') ?>" class="ajax-form-submit" method="POST">
                   <div class="row">
                        <div class="col-lg-6 form-group">
                            <div class="row">
                                <div class="col-lg-2 form-group">
                                    <label class="form-label">Daybook: <span class="tx-danger">*</span></label>
                                </div>
                                <div class="col-lg-10 form-group">
                                    <div class="input-group">	
                                    <input class="form-control" type="text" name="daybook" value="" id="daybook_ac_code" required="" autocomplete="off">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <a href="<?= url('') ?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <div class="row">
                                <div class="col-lg-2 form-group">
                                         <label class="form-label">Account : <span class="tx-danger">*</span></label>
                                </div>
                                 <div class="col-lg-4 form-group">
                                    <select class="form-control" data-select2-id="13" tabindex="-1" aria-hidden="true">	
                                        <option value="all" data-select2-id="0">
                                            All
                                        </option>
                                        <option value="in" data-select2-id="1">
                                            In
                                        </option>
                                        <option value="not_in" data-select2-id="1">
                                            Not In
                                        </option>	
                                        <option value="not_in" data-select2-id="1">
                                            =
                                        </option>						
                                    </select>
                                 </div>   
                                 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-6 form-group">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">Date From : <span class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8 form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text" id="dp1600079515302">
                                         </div>
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="form-label">To : <span class="tx-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-8 form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fe fe-calendar lh--9 op-6"></i>
                                                </div>
                                            </div>
                                            <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY" type="text" id="dp1600079515302">
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <div class="row">
                                <div class="col-lg-2 form-group">
                                         <label class="form-label">Status : <span class="tx-danger">*</span></label>
                                </div>
                                 <div class="col-lg-4 form-group">
                                 <select class="form-control" data-select2-id="13" tabindex="-1" aria-hidden="true">	
                                    <option value="all" data-select2-id="0">
                                        All
                                    </option>
                                    <option value="pending" data-select2-id="1">
                                        Pending
                                    </option>
                                    <option value="clear" data-select2-id="1">
                                        Clear
                                    </option>						
                                </select>
                                 </div>   
                                 <div class="col-lg-2 form-group">
                                 <label class="form-label">Lot: <span class="tx-danger">*</span></label>
                                </div>
                                 <div class="col-lg-4 form-group">
                                 <select class="form-control" data-select2-id="13" tabindex="-1" aria-hidden="true">	
                                    <option value="both" data-select2-id="0">
                                        Both
                                    </option>
                                    <option value="bank" data-select2-id="1">
                                        Bank
                                    </option>
                                    <option value="not_bank" data-select2-id="1">
                                       Not Bank
                                    </option>						
                                </select>
                                 </div>   
                            </div>
                        </div>
                        
                    </div>
                        
                   </div>
                   <div class="row col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mg-b-0" id="product">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Challan</th>
                                                <th>Party Name</th>
                                                <th>Quality</th>
                                                <th>PCS</th>
                                                <th>Meters</th>
                                                <th>Status</th>
                                                <th>LotNo</th>  
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">

                                        </tbody>
                                        <tfoot>
                                            <td colspan="5" class="text-right">Total</td>
                                            <td id="total"></td>
                                        </tfoot>
                                    </table>
                                </div>
                    </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <input type="submit" class="btn ripple btn-primary" value="Submit">
                    </div>
                    </div>
               
               
                </form> 
            </div> 
        </div>
    </div>
</div>
</div>
<!-- End Page Header -->
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$( document ).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
});
</script>
<?= $this->endSection() ?>
