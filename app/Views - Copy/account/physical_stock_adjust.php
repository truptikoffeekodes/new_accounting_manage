<?= $this->extend(THEME . 'sub_templets') ?>

<?= $this->section('content') ?>

<div class="row">
<div class="col-lg-12">
    <div class="card custom-card">
        <div class="card-header card-header-divider">
            <div class="card-body">
                <form action="<?= url('') ?>" class="ajax-form-submit" method="POST">
                   <div class="row">
                        <div class="col-lg-4 form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <label class="form-label">Document No: <span class="tx-danger">*</span></label>
                                </div>
                                <div class="col-lg-9 form-group">
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
                        <div class="col-lg-4 form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <label class="form-label">Date: <span class="tx-danger">*</span></label>
                                </div>
                                <div class="col-lg-9 form-group">
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
                        <div class="col-lg-4 form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <label class="form-label">Item Group: <span class="tx-danger">*</span></label>
                                </div>
                                <div class="col-lg-9 form-group">
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
                   <div class="row col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered mg-b-0" id="product">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Class</th>
                                                <th>Book PCS</th>
                                                <th>Book CUT</th>
                                                <th>Book MTS</th>
                                                <th>Physical PCS</th>
                                                <th>Physical CUT</th>
                                                <th>Physical MTS</th>
                                                <th>PCS</th>
                                                <th>CUT</th>
                                                <th>MTS</th>
                                                <th>Adj. per</th>
                                                <th>Notes</th>
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
