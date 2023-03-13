<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>
<style>
.doc_lb {
    font-weight: bold;
}
.doc_name{
    text-align:center;
}
</style>
<div class="container-fluid">

    <div class="page-header">
        <div>
            <h2 class="main-content-title tx-24 mg-b-5">Profile</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card custom-card">
                <div class="card-body text-center">
                    <div class="main-profile-overview widget-user-image text-center">
                        <div class="main-img-user"><img alt="avatar" src="<?= $profile['logo'] ? url($profile['logo']) : url('assets/img/users/avtar.png')?>"></div>
                    </div>
                    <div class="item-user pro-user">
                        <h4 class="pro-user-username text-dark mt-2 mb-0"><?=$profile['name']?></h4>
                        <p class="pro-user-desc text-muted mb-1"><?=$profile['code']?></p>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-header custom-card-header">
                    <div>
                        <h6 class="card-title mb-0">Contact Information</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="main-profile-contact-list main-profile-work-list">
                        <div class="media">
                            <div class="media-logo bg-light text-dark">
                                <i class="fe fe-smartphone"></i>
                            </div>
                            <div class="media-body">
                                <span>Mobile</span>
                                <div>
                                    <?=$profile['whatsap']?>
                                </div>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-logo bg-light text-dark">
                                <i class="fe fe-mail"></i>
                            </div>
                            <div class="media-body">
                                <span>Email</span>
                                <div>
                                    <?=$profile['email']?>
                                </div>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-logo bg-light text-dark">
                                <i class="fe fe-map-pin"></i>
                            </div>
                            <div class="media-body">
                                <span>Office Address</span>
                                <div>
                                    <?=$profile['address']?>
                                </div>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-logo bg-light text-dark">
                                <i class="fe fe-map-pin"></i>
                            </div>
                            <div class="media-body">
                                <span>GST Address</span>
                                <div>
                                    <?=$profile['gst_address']?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-body text-center">
                    <div class="main-profile-overview widget-user-image text-center">
                        <div class="main-img-user"><img alt="sign_capture"  src="<?= $profile['sign_capture'] ? url($profile['sign_capture']) : url('assets/img/users/sign.png')?>"></div>
                    </div>
                    <div class="item-user pro-user">
                        <h4 class="pro-user-username text-dark mt-2 mb-0">Singnature</h4>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card custom-card main-content-body-profile">
                <nav class="nav main-nav-line">
                    <a class="nav-link active" data-toggle="tab" href="#tab1over">General</a>
                    <!-- <a class="nav-link" data-toggle="tab" href="#tab2rev">Other</a> -->
                </nav>
                <div class="card-body tab-content h-100">
                    <div class="tab-pane active" id="tab1over">
                        <div class="main-content-label tx-13 mg-b-20">
                            Company Information
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <?php 	
                                    if(!empty($profile['financial_form'] && $profile['financial_form'] != '0000-00-00')){
                                        $dt = date_create($profile['financial_form']);
                                        $fin_from =  date_format($dt,'Y');
                                    }else{
                                        $fin_from = '';
                                    }

                                    if(!empty($profile['financial_to'] && $profile['financial_to'] != '0000-00-00')){
                                        $to_dt = date_create($profile['financial_to']);
                                        $fin_to =  date_format($to_dt,'Y');
                                    }else{
                                        $fin_to='';
                                    }
                                ?>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    
                                    <tr>
                                        <td><b>Trade Name:</b></td>
                                        <td><?=$profile['name']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Fin. Year From:</b></td>
                                        <td><?=$fin_from?> - <?=$fin_to?> </td>
                                    </tr>
                                    <tr>
                                        <td><b>Legal Person:</b></td>
                                        <td><?=$profile['contact_person']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Company Firm:</b></td>
                                        <td><?=$profile['form_company']?></td>
                                    </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Code:</b></td>
                                        <td><?=$profile['code']?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td><b>Fin. Year From:</b></td>
                                        <td><?=$fin_to?></td>
                                    </tr> -->
                                   
                                    <tr>
                                        <td><b>Contact No:</b></td>
                                        <td><?=$profile['whatsap']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Business Type:</b></td>
                                        <td><?=$profile['business_type']?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="main-content-label tx-13 mg-b-20">
                            TAX Detail
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <?php 	
                                    $gst_date =  user_date($profile['gst_date']);
                                    $localtax_date =  user_date($profile['localtax_date']);
                                    $centraltax_date =  user_date($profile['centraltax_date']);
                                    $cst_date =  user_date($profile['cst_date']);
                                ?>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Income Tax PAN:</b></td>
                                        <td><?=$profile['incomtax_pan']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Gst No:</b></td>
                                        <td><?=$profile['gst_no']?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>GST Type:</b></td>
                                        <td><?=$profile['gst_type']?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>Local Tax No:</b></td>
                                        <td><?=$profile['localtax_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Central Tax No:</b></td>
                                        <td><?=$profile['centraltax_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>CST No:</b></td>
                                        <td><?=$profile['cst_no']?></td>
                                    </tr>
                                    
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Import Export Code(IEC):</b></td>
                                        <td><?=$profile['impo_expo']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GST Date:</b></td>
                                        <td><?=$gst_date?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GST Period:</b></td>
                                        <td><?=$profile['gst_period']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Local Tax Date:</b></td>
                                        <td><?=$localtax_date?></td>
                                    </tr>
                                   
                                    <tr>
                                        <td><b>Central Tax Date:</b></td>
                                        <td><?=$centraltax_date?></td>
                                    </tr>

                                    <tr>
                                        <td><b>CST Date:</b></td>
                                        <td><?=$cst_date?></td>
                                    </tr>
                                       
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    <!-- <div class="tab-pane" id="tab2rev">
                        <div class="main-content-label tx-13 mg-b-20">
                            TAX Detail
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <?php 	
                                    $gst_date =  user_date($profile['gst_date']);
                                    $localtax_date =  user_date($profile['localtax_date']);
                                    $centraltax_date =  user_date($profile['centraltax_date']);
                                    $cst_date =  user_date($profile['cst_date']);
                                ?>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    
                                    <tr>
                                        <td><b>Gst No:</b></td>
                                        <td><?=$profile['gst_no']?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>GST Type:</b></td>
                                        <td><?=$profile['gst_type']?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>Local Tax No:</b></td>
                                        <td><?=$profile['localtax_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Central Tax No:</b></td>
                                        <td><?=$profile['centraltax_no']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>CST No:</b></td>
                                        <td><?=$profile['cst_no']?></td>
                                    </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>GST Date:</b></td>
                                        <td><?=$gst_date?></td>
                                    </tr>
                                    <tr>
                                        <td><b>GST Period:</b></td>
                                        <td><?=$profile['gst_period']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Local Tax Date:</b></td>
                                        <td><?=$localtax_date?></td>
                                    </tr>
                                   
                                    <tr>
                                        <td><b>Central Tax Date:</b></td>
                                        <td><?=$centraltax_date?></td>
                                    </tr>

                                    <tr>
                                        <td><b>CST Date:</b></td>
                                        <td><?=$cst_date?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                    <tr>
                                        <td><b>Income Tax PAN:</b></td>
                                        <td><?=$profile['incomtax_pan']?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Registration Certificate:</b></td>
                                        <td><?=$profile['reg_certi']?></td>
                                    </tr>
                                    
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                  
                                    
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function verify_reject(data_edit, type) {

    var data_val = $(data_edit).data('val');

    var ot_title = $(data_edit).attr('title');
    var pkno = $(data_edit).data('pk');
    var uid = $(data_edit).data('uid');
    swal.fire({
        title: "Are you sure ",
        text: type + " Document!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, " + type + " it!",
        cancelButtonText: "No, cancel plx!",
        //closeOnConfirm: false,
        //closeOnCancel: false
    }).then((result) => {
        // function(isConfirm) {
        if (result.value) {
            _data = $.param({
                pk: pkno
            }) + '&' + $.param({
                val: data_val
            }) + '&' + $.param({
                uid: uid
            }) + '&' + $.param({
                type: type
            });

            if (data_val != undefined && data_val != '') {
                $.post("<?= url('User/action/Update') ?>", _data, function(data) {
                    if (data.st == 'success') {
                        swal.fire(type + "!", "Document Successfully " + type, "success");
                        location.reload();
                    }
                });
            }

        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
        }
        // })}
    });
}
</script>
<?= $this->endSection() ?>