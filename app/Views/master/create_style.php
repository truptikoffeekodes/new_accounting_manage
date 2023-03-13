<?= $this->extend(THEME . 'form') ?>

<?= $this->section('content') ?>
<div class="row">

    <div class="col-lg-12">
        <form action="<?= url('') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="code" value="" placeholder="Enter Code" required="" type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Description: <span class="tx-danger">*</span></label>
                <input class="form-control" name="description" value="" placeholder="Enter Description" required=""
                    type="text">
            </div>
            <div class="form-group">
                <label class="form-label">Sub Style Of: <span class="tx-danger">*</span></label>
                <div class="input-group">
                    <input class="form-control" type="text" name="sub_style" value="" id="sub_style" required=""
                        autocomplete="off">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <a href="<?= url('') ?>"><i style="font-size:20px;" class="fe fe-plus-circle"></i></a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="category" id="category" value="">
                <div class="dz-error-message tx-danger category"></div>
            </div>
            <div class="form-group">
                <label class="form-label">Status: <span class="tx-danger">*</span></label>
                <select class="form-control" data-select2-id="13" tabindex="-1" aria-hidden="true">
                    <option label="Select status" data-select2-id="15">
                    </option>
                    <option value="Firefox" data-select2-id="30">
                        Active
                    </option>
                    <option value="Chrome" data-select2-id="31">
                        InActive
                    </option>

                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes: <span class="tx-danger">*</span></label>
                <input class="form-control" name="notes" value="" placeholder="Enter Notes" required="" type="text">
            </div>
            <div class="col-lg-6 form-group">
                <input type="submit" class="btn ripple btn-primary" value="Submit">
            </div>

        </form>

    </div>
</div>
<!-- End Page Header -->
<?= $this->endSection() ?>
<?= $this->Section('Scripts') ?>
<script>
function afterload() {
    $('#fm_model').on('shown.bs.modal', function() {
        $('.fc-datepicker').datepicker({
            format: "dd/mm/yyyy",
            startDate: "01-01-2015",
            endDate: "01-01-2020",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#fm_model modal-body'
        });
    });
}
</script>
<?= $this->endSection() ?>