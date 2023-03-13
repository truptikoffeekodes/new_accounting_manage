<?php
$session = \Config\Services::session();
$msg = $session->getFlashdata('msg');

if (!empty($msg)) {  ?>
    <?php if ($msg['st'] == 'success') { ?>
        <div class="alert alert-success" role="alert">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><?= $msg['st']; ?>!</strong> <?= $msg['txt']; ?>
        </div>
    <?php }else{ ?>
    <div class="alert alert-danger mg-b-0" role="alert">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong><?= $msg['st']; ?>!</strong> <?= $msg['txt']; ?>
    </div>
    <?php } ?>
<?php } ?>