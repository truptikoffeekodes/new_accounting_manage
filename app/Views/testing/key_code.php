<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<h3>Press a key.</h3>
<div id='key-info'></div>
<input type="text" name="key_code" onkeypress="showKeyInfo(this)">
<?= $this->endsection() ?>
<?= $this->section('scripts') ?>
<script type="text/javascript">
     
//$(document).ready(function() {
    $(document).keyup(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which
        if (keyCode == 65) {
           // alert("jherke");
            window.location=PATH+"Company";
        }
    });

</script>
<?= $this->endSection() ?>