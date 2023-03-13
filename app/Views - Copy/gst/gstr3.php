<?= $this->extend(THEME . 'templete') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"> <?=$title?> </h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">GST</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
    <div class="btn btn-list">
        <a href="#" class="btn ripple btn-secondary navresponsive-toggler" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fe fe-filter mr-1"></i> Filter <i class="fas fa-caret-down ml-1"></i>
        </a>
    </div>
</div>
<!--Start Navbar -->
<div class="responsive-background">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="advanced-search">
            <form method="post" id="date_submit">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">From :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="from"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-lg-0">
                                    <label class="">To :</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fe fe-calendar lh--9 op-6"></i>
                                            </div>
                                        </div>
                                        <input class="form-control dateMask" id="dateMask" name="to"
                                            placeholder="DD-MM-YYYY" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="#" id="SearchButtonReset" class="btn btn-secondary" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">Reset</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--End Navbar -->

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-fw-widget">
                        <tbody>
                            <tr>
                                <td>
                                    <span style="size:20px;"><b>GSTIN</b></span>
                                    <br>
                                    <b id="start_date"><?=$gstr3['start_date']?></b> to
                                    <b id="end_date"><?=$gstr3['end_date']?></b>

                                </td>
                            </tr>
                            <tr colspan="4">
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php 
                    $total = @$gstr3['sales'] + @$gstr3['sale_return'] +(@$gstr3['non_hsn']['data'] ? count($gstr3['non_hsn']['data']) : 0) + (@$gstr3['hsn']['data'] ? count($gstr3['hsn']['data']) : 0 ) + (@$gstr3['relevant_non']['data'] ? count(@$gstr3['relevant_non']['data']) : 0 ) + (@$gstr3['relevant_gst']['data'] ? count(@$gstr3['relevant_gst']['data']) : 0 );
                ?> 

                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                    <div class="card">

                        <div class="card-header" id="headingOne" role="tab">
                            <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                href="#collapseOne" class="collapsed">Total Voucher<label
                                    style="float:right;"><?=$total?></label>
                            </a>
                        </div>

                        <div aria-labelledby="headingOne" class="collapse" id="collapseOne" role="tabpanel" style="">
                            <div class="card-body">
                                <ul class="list-group">
                                    <div id="headOne" role="tab">
                                        <a aria-controls="collapseOne" aria-expanded="false" data-toggle="collapse"
                                            href="#collaOne" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Included in Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['sales'] + @$gstr3['sale_return']?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headOne" class="collapse" id="collaOne" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;" onclick="goto_url('sales')" >Sale</a>
                                                <span class="badge badge-primary badge-pill"><?=@$gstr3['sales']?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;" onclick="goto_url('creditnote')" >  Sale Return </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['sale_return']?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="headTwo" role="tab">
                                        <a aria-controls="collaTwo" aria-expanded="false" data-toggle="collapse"
                                            href="#collaTwo" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Included HSN/SAC Summary
                                                <span class="badge badge-primary badge-pill"><?=(@$gstr3['non_hsn']['data'] ? count($gstr3['non_hsn']['data']) : 0) + (@$gstr3['hsn']['data'] ? count($gstr3['hsn']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headTwo" class="collapse" id="collaTwo" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;">Incuded in HSN/SAC Summary</a><span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['hsn']['data'] ? count($gstr3['hsn']['data']) : 0 ?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;"> Incomplete Information in HSN/SAC Summary </a><span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['non_hsn']['data'] ? count($gstr3['non_hsn']['data']) : 0 ?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="headThree" role="tab">
                                        <a aria-controls="collaThree" aria-expanded="false" data-toggle="collapse"
                                            href="#collaThree" class="collapsed">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                Not relevant in this Return
                                                <span
                                                    class="badge badge-primary badge-pill"><?=(@$gstr3['relevant_non']['data'] ? count(@$gstr3['relevant_non']['data']) : 0 ) + (@$gstr3['relevant_gst']['data'] ? count(@$gstr3['relevant_gst']['data']) : 0 )?></span>
                                            </li>
                                        </a>
                                    </div>

                                    <div aria-labelledby="headThree" class="collapse" id="collaThree" role="tabpanel"
                                        style="">
                                        <ul class="list-group">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;">Non Gst Transaction </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['relevant_non']['data'] ? count($gstr3['relevant_non']['data']) : 0 ?></span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="#" style="color:black;"> Transaction Of Other GST Return </a>
                                                <span
                                                    class="badge badge-primary badge-pill"><?=@$gstr3['relevant_gst']['data'] ? count(@$gstr3['relevant_gst']['data']) : 0 ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card">
            <div class="card-body">
                
                <div class="table-responsive">
                    <table class="table table mg-b-0">
                        <thead>
                            <tr>
                                <th>SI NO.</th>
                                <th>Particular</th>
                                <th>Taxable Amount</th>
                                <th>Integrated Tax Amount</th>
                                <th>Central Tax Amount</th>
                                <th>State Tax Amount</th>
                                <th>Cess Amount</th>
                                <th>Tax Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="main1" id="main1" onclick="return main(this)">
                                <th>3.1</th>
                                <td>Outward Supplies and inward supplies liable to Reverse charge</td>
                                <td><?=@$gstr3['outward']['taxable_amount'] ?></td>
                                <td><?=@$gstr3['outward']['igst'] ?></td>
                                <td><?=@$gstr3['outward']['cgst'] ?></td>
                                <td><?=@$gstr3['outward']['sgst'] ?></td>
                                <td><?=@$gstr3['outward']['cess'] ?></td>
                                <td><?=@$gstr3['outward']['cess'] +@$gstr3['outward']['sgst'] + @$gstr3['outward']['cgst'] + @$gstr3['outward']['igst']  ?></td>
                            </tr>
                            
                            <tr class="sub-main1">
                                <th></th>
                                <td>Outward Taxable Supplies (other than zero rated nill rated and exempted)</td>
                                <td><?=@$gstr3['outward']['taxable_amount'] ?></td>
                                <td><?=@$gstr3['outward']['igst'] ?></td>
                                <td><?=@$gstr3['outward']['cgst'] ?></td>
                                <td><?=@$gstr3['outward']['sgst'] ?></td>
                                <td><?=@$gstr3['outward']['cess'] ?></td>
                                <td><?=@$gstr3['outward']['cess'] +@$gstr3['outward']['sgst'] + @$gstr3['outward']['cgst'] + @$gstr3['outward']['igst']  ?></td>
                            </tr>
                            <tr class="sub-main1">
                                <th></th>
                                <td>Outward Taxable Supplies (zero rated)</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-main1">
                                <th></th>
                                <td>Other Outward Supplies (Nill rated exempted)</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-main1">
                                <th></th>
                                <td>Inward Supplies (liable to reverse charge)</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-main1">
                                <th></th>
                                <td>Non-GST outward Supplies (liable to reverse charge)</td>
                                
                                </td>
                            </tr>

                            <tr class="main2" id="main2" onclick="return main(this)">
                                <th>3.2</th>
                                <td>Of the Supplies Shown in 3.1(a) above,detail of inter-state Supplied maid to unregister persons,composition taxable  person and UIN holders</td>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['taxable_amount'] +  @$gstr3['gst_type_wise']['composition']['taxable_amount']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['igst'] +  @$gstr3['gst_type_wise']['composition']['igst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cgst'] +  @$gstr3['gst_type_wise']['composition']['cgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['sgst'] +  @$gstr3['gst_type_wise']['composition']['sgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cess'] +  @$gstr3['gst_type_wise']['composition']['cess']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cess'] + @$gstr3['gst_type_wise']['unregister']['sgst'] + @$gstr3['gst_type_wise']['unregister']['cgst'] + @$gstr3['gst_type_wise']['unregister']['igst'] +  @$gstr3['gst_type_wise']['composition']['cess'] + @$gstr3['gst_type_wise']['composition']['igst'] + @$gstr3['gst_type_wise']['composition']['cgst'] +@$gstr3['gst_type_wise']['composition']['sgst']?></th>
                                
                            </tr>

                            <tr class="sub-main2">
                                <th></th>
                                <td>Supplies made to Unregister Person</td>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['taxable_amount'] ?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['igst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['sgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cess']?></th>
                                <th><?=@$gstr3['gst_type_wise']['unregister']['cess'] + @$gstr3['gst_type_wise']['unregister']['sgst'] + @$gstr3['gst_type_wise']['unregister']['cgst'] + @$gstr3['gst_type_wise']['unregister']['igst'] ?></th>
                                
                                </td>
                            </tr>
                            <tr class="sub-main2">
                                <th></th>
                                <td>Supplies made to Composition Taxable person</td>
                                <th><?=@$gstr3['gst_type_wise']['composition']['taxable_amount'] ?></th>
                                <th><?=@$gstr3['gst_type_wise']['composition']['igst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['composition']['cgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['composition']['sgst']?></th>
                                <th><?=@$gstr3['gst_type_wise']['composition']['cess']?></th>
                                <th><?=@$gstr3['gst_type_wise']['composition']['cess'] + @$gstr3['gst_type_wise']['composition']['sgst'] + @$gstr3['gst_type_wise']['composition']['cgst'] + @$gstr3['gst_type_wise']['composition']['igst'] ?></th>
                                </td>
                            </tr>
                            <tr class="sub-main2">
                                <th></th>
                                <td>Supplies made to UIN holders</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="main3" id="main3" onclick="return main(this)">
                                <th>4</th>
                                <td>Eligible ITC</td>
                                <td></td>
                                <td><?=@$gstr3['eligable_itc']['igst']?></td>
                                <td><?=@$gstr3['eligable_itc']['cgst']?></td>
                                <td><?=@$gstr3['eligable_itc']['Sgst']?></td>
                                
                            </tr>
                            <tr class="sub-main3" id="sub31" onclick="return main(this)" >
                                <th>A</th>
                                <td>ITC Available (Whether in full or part)</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="sub-sub31">
                                <th>(1)</th>
                                <td>Import of goods</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub31">
                                <th>(2)</th>
                                <td>Import of service</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub31">
                                <th>(3)</th>
                                <td>Inward supplies liable to reserve charge(other than 1 & 2 above)</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub31">
                                <th>(4)</th>
                                <td>Inward supplies from ISD</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub31">
                                <th>(5)</th>
                                <td>ALL other ITC</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="sub-main3" id="sub32" onclick="return main(this)">
                                <th>(B)</th>
                                <td>ITC Reversed</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="sub-sub32">
                                <th>(1)</th>
                                <td>As per rules 42 & 43 of CGST Rules</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub32">
                                <th>(2)</th>
                                <td>Otheres</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="sub-main3">
                                <th>(C)</th>
                                <td>Net ITC Available (A) - (B)</td>
                                
                            </tr>
                            
                            <tr class="sub-main3" id="sub34" onclick="return main(this)">
                                <th>(D)</th>
                                <td>Ineligible ITC</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="sub-sub34">
                                <th>(1)</th>
                                <td>As per section 17(5)</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-sub34">
                                <th>(2)</th>
                                <td>Others</td>
                                
                                </td>
                            </tr>

                            <tr class="main4" id="main4" onclick="return main(this)">
                                <th>5</th>
                                <td>Value of exempt,nil rated and non-GST inward supplies</td>
                                
                            </tr>
                            
                            <tr class="sub-main4">
                                <th></th>
                                <td>From a supplier under composition scheme,exempt and nil rated supply</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-main4">
                                <th></th>
                                <td>Non GST supply</td>
                                
                                </td>
                            </tr>
                            
                            <tr class="main5" id="main5" onclick="return main(this)">
                                <th>5.1</th>
                                <td>Interest and Late fee Payable</td>
                                
                            </tr>
                            
                            <tr class="sub-main5">
                                <th></th>
                                <td>Interest</td>
                                
                                </td>
                            </tr>
                            <tr class="sub-main5">
                                <th></th>
                                <td>Late Fees</td>
                                
                                </td>
                            </tr>

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
function goto_url(type){

    var start  = $('#start_date').text();
    var from = start.split("/");
    var f = new Date(from[2], from[1], from[0]);
    var start_date = f.getFullYear() + "-" + f.getMonth() + "-" + f.getDate();
    
 
    var end  = $('#end_date').text();
    var to = end.split("/");
    
    var t = new Date(to[2],to[1],to[0]);
  
    var end_date = t.getFullYear() + "-" + t.getMonth() + "-" + t.getDate();
    var url = "<?=url('Addbook/View_filter/')?>";
    window.location =  url +'?type='+ type + '&from=' + start_date + '&to=' + end_date; 
    
    // $.ajax({
    //     type: "post",
    //     url: "Addbook/View_filter/sales",
    //     data: {
    //         from : start_date,
    //         to : end_date
    //     },
    //     success: function (data){
    //         alert(data);
    //     },
    //     error: function (xhr, ajaxOptions, thrownError){

    //     }
    // });

    
}
function main(obj){
    var idOfParent = $(obj).attr('id');
    $('tr.sub-'+idOfParent).toggle('fast');
  
  $('tr[class^=child-]').hide().children('td');
}


// function main1(){
//     if($('.sub1').style.display == "table-row"){
//         $('.sub1').style.display="none";
//     }

// }          

function main2(){
  if($('.sub2').style.display == "block"){
    $('.sub2').style.display = "none";
  }else{
    $('.sub2').style.display = "block";
  }          
}
function main3(){
  if($('.sub3').style.display == "block"){
    $('.sub3').style.display = "none";
  }else{
    $('.sub3').style.display = "block";
  }          
}
function main4(){
  if($('.sub4').style.display == "block"){
    $('.sub4').style.display = "none";
  }else{
    $('.sub4').style.display = "block";
  }          
}
function main5(){
  if($('.sub5').style.display == "block"){
    $('.sub5').style.display = "none";
  }else{
    $('.sub5').style.display = "block";
  }          
}
$(document).ready(function() {
    $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $('.dateMask').mask('99-99-9999');
    
    $('.sub')


});

</script>
<?= $this->endSection() ?>