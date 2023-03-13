<?= $this->extend(THEME . 'form') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-12">
        <form action="<?= url('Testing/add_shortcut_key') ?>" class="ajax-form-submit" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Key Character: <span class="tx-danger">*</span></label>
                <input class="form-control" name="key_char" value="<?= @$shortcut_key['key_char'] ?>" placeholder="Enter Key Charcter" required="" type="text" onkeyup="get_ascii(this.value)">
                <input name="id" value="<?= @$shortcut_key['id'] ?>" type="hidden">
            </div>
            <div class="form-group">
                <label class="form-label">Key Code: <span class="tx-danger">*</span></label>
                <input class="form-control" name="key_code" id="key_code" value="<?= @$shortcut_key['key_code'] ?>" placeholder="Enter Key Code" required="" type="text" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Voucher Type: <span class="tx-danger">*</span></label>
                <select class="form-control select2" name="voucher_type" required>
                    <option value="">None</option>
                    <option value="sales_challan">Sales Challan</option>
                    <option value="sales_invoice">Sales Invoice</option>
                    <option value="sales_return">Sales Return</option>
                    <option value="sales_general">Sales General</option>
                    <option value="purchase_challan">Purchase Challan</option>
                    <option value="purchase_invoice">Purchase Invoice</option>
                    <option value="purchase_return">Purchase Return</option>
                    <option value="purchase_general">Purchase General</option>
                </select>
            </div>


            <div class="form-group">
                <div class="tx-danger error-msg"></div>
                <div class="tx-success form_proccessing"></div>
            </div>
            <div class="row pt-3">
                <div class="col-sm-6">
                    <p class="text-left">
                        <button class="btn btn-space btn-primary" id="save_data" type="submit">Submit</button>
                        <button class="btn btn-space btn-secondary" data-dismiss="modal">Cancel</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Page Header -->


<script>
    $('.ajax-form-submit').on('submit', function(e) {
        $('#save_data').prop('disabled', true);
        $('.error-msg').html('');
        $('.form_proccessing').html('Please wail...');
        e.preventDefault();
        var aurl = $(this).attr('action');
        $.ajax({
            type: "POST",
            url: aurl,
            data: $(this).serialize(),
            success: function(response) {
                if (response.st == 'success') {
                    $('#fm_model').modal('toggle');
                    swal("success!", "Your update successfully!", "success");
                    datatable_load('');
                    $('#save_data').prop('disabled', false);
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
        $('.select2').select2({
            minimumResultsForSearch: Infinity,
            placeholder: 'Choose one',
            width: '100%'
        });
    }

    function get_ascii(char) {

        const result = char.charCodeAt(0);
        //alert(pk);
        $("#key_code").val(result);
    }
</script>
<?= $this->endSection() ?>