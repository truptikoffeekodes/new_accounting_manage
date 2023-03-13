<!-- Sidemenu -->
<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="<?= url('') ?>">
            <img src="<?= LOGO; ?>" class="header-brand-img desktop-logo" alt="logo">
            <img src="<?= LOGOICON; ?>" class="header-brand-img icon-logo" alt="logo">
            <img src="<?= LOGO; ?>" class="header-brand-img desktop-logo theme-logo" alt="logo">
            <img src="<?= LOGOICON; ?>" class="header-brand-img icon-logo theme-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
        <ul class="nav">
            <li class="nav-label">Dashboard</li>
            <li class="nav-item ">
                <a class="nav-link" href="<?= url('') ?>"><i class="fe fe-airplay"></i><span
                        class="sidemenu-label">Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">
                        Master</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Account</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Account')?>">Add Account (Ledger) </a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Master/glgrp')?>">General Ledger Group</a>
                            </li>
                        </ul>
                        
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Items</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Items')?>">Item Entry</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Items/item_grp')?>">Item Group</a>
                            </li>
                        </ul>
                    </li>


                    <!-- <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Broker</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/broker')?>">Broker</a>
                            </li>
                        </ul>
                    </li> -->


                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">TDS
                                Rate</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/tds')?>">TDS Rate</a>
                            </li>
                        </ul>
                    </li>
                    <!-- ------------------------------ -->

                    <!-- <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">Bill
                                Terms</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('account/billterm')?>">Bill Terms</a>
                            </li>
                        </ul>
                    </li> -->

                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Others</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/billterm')?>">Billterm</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/bank')?>">Bank</a>
                            </li>
                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/godown')?>">Godown</a>
                            </li> -->
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/warehouse')?>">Warehouse</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/transport') ?>">Transport</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/supervisor') ?>">Supervisor</a>
                            </li>
                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/jv_paricular')?>">Jv Paricular</a>
                            </li> -->
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/uom')?>">UOM</a>
                            </li>
                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/style')?>">Style</a>
                            </li> -->
                            
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/vehicle') ?>">Vehicle</a>
                            </li>
                            
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/hsn') ?>">HSN</a>
                            </li>

                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/screenseries')?>">Screen Series</a>
                            </li> -->
                        </ul>
                    </li>
                </ul>
            </li>

            <!-- --------------------------------------------------------------------------------------------------------- -->

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span
                        class="sidemenu-label">Transactions</span><i class="angle fe fe-chevron-right"></i></a>

                <ul class="nav-sub">
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Sales</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/challan')?>">Item Challan</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/salesinvoice')?>">Item Invoice</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/salesreturn')?>">Item Return</a>
                            </li>
                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/ac_challan')?>">General Return</a>
                            </li> -->
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/ac_invoice')?>">General Sales</a>
                            </li>

                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/bill_generate')?>">Generate Sale Bill</a>
                            </li> -->
                        </ul>

                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Purchase</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Purchase/purchasechallan')?>">Item Challan</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Purchase/purchaseinvoice')?>">Item Invoice</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Purchase/purchasereturn')?>">Item Return</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Purchase/general_purchase')?>">General
                                    Purchase</a>
                            </li>
                        </ul>
                    </li>

                    <!-- ---------------------------- -->
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">Bank
                                /Cash</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Bank/bank_transaction')?>">Bank Transaction</a>
                            </li>

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Bank/cash_transaction')?>">Cash Transaction</a>
                            </li>

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Bank/reconciliation')?>">Bank Reconciliation</a>
                            </li>
                        </ul>
                    </li>

                    <!-- ---------------------------- -->
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">JV
                                Particular</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('bank/jv_particular')?>">Jv Paricular</a>
                            </li>
                        </ul>
                    </li>

                    <!-- ---------------------------- -->
                    <!-- <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">Reporting</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Trading/dashboard')?>">Trading</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Trading/pl_dashboard')?>">Profit/Loss</a>
                            </li>
                        </ul>
                    </li> -->

                    <!-- ---------------------------- -->
                    <!-- <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Stock</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Stock/item_stock')?>">Item Wise </a>
                            </li>
                        </ul>
                    </li>  -->

                    <!-- <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Inventory</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('InventoryIssue/inventory_issue')?>">Inventory
                                    Issue</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('account/inventory_receipts')?>">Inventory
                                    Receipts</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('account/physical_stock_adjust')?>">Inventory Stock
                                    Adjustment</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('account/lotno_entry')?>">Lot No Entry</a>
                            </li>
                        </ul>
                    </li> -->
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span
                        class="sidemenu-label">Reporting</span><i class="angle fe fe-chevron-right"></i></a>

                <ul class="nav-sub">
                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Trading/dashboard')?>">Trading</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Trading/pl_dashboard')?>">Profit/Loss</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Trading/balacesheet')?>">Balance Sheet</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Trading/oc_closing')?>">Closing Balance</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span
                        class="sidemenu-label">Stock</span><i class="angle fe fe-chevron-right"></i></a>

                <ul class="nav-sub">
                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/item_stock')?>">Item Wise </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span
                        class="sidemenu-label">Milling</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/Grey_Challan')?>">Gray/Finish Challan</a>
                    </li>
       
                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/Grey_invoice')?>">Gray/Finish Invoice</a>
                    </li>

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/retGrayFinish')?>">Gray/Finish Return</a>
                    </li>

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/mill_challan')?>">Mill Issue</a>
                    </li>

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/mill_rec')?>">Mill Received</a>
                    </li>

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/return_mill')?>">Mill Return</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Milling/Jobwork')?>">Jobwork Issue</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Milling/Jobwork_rec')?>">Jobwork Received</a>
                    </li>

                    <li class = "nav-sub-item">
                        <a class="nav-sub-link" href="<?=url('Milling/return_jobwork')?>">Jobwork Return</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('milling/mill_sale_challan')?>">Mill Sale Challan</a>
                    </li>   
                    
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('milling/mill_sale_invoice')?>">Mill Sale Invoice</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('milling/mill_sale_return')?>">Mill Sale Return</a>
                    </li>
                    

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Milling/milling_stock')?>">Mill Zoom Report</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Milling/jobwork_stock')?>">Jobwork Zoom Report</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span
                        class="sidemenu-label">Milling Reporting</span><i class="angle fe fe-chevron-right"></i></a>

                <ul class="nav-sub">
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">Gray Report</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/gray_item_wise')?>">Gray Item Wise Report</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/Gray_Invoice_wise')?>">Gray Invoice Wise Report</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">Mill Report</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/Mill_report')?>">Mill Report</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/sendMill_item_wise')?>">Mill Item Report</a>
                            </li>
                            
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/mill_rec_report')?>">Mill Received Sceen Wise Report</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/RecMill_report')?>">Received Mill Report</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/recMill_item_wise')?>">Received Mill Item Report</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span class="sidemenu-label">JOB Report</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/sendJob_report')?>">SendJob Report</a>
                            </li>
                            <!-- <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/sendMill_item_wise')?>">Mill Item Report</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('MillingReport/RecMill_report')?>">Received Mill Report</a>
                            </li> -->
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">Account
                        Book</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/sales');?>">Sales Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/purchase')?>">Purchase Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/creditnote')?>">Credit Not
                            Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/debitnote')?>">Debit Not Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/payment')?>">Payment Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/receipt')?>">Receipts Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/cash')?>">Cash Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/bank')?>">Bank Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/journal')?>">Journal Register</a>
                    </li>
                    
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/View_filter/ledger')?>">Ledger Register</a>
                    </li>
                    
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Groupsummary')?>">Group Summary</a>
                    </li>
                    
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Outstanding/receivable')?>">Receivable</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Outstanding/payable')?>">Payable</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Ledger_outstanding')?>">Ledger Outstandig</a>
                    </li>

                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">GST</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('gst/gstr1');?>">GSTR 1</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('gst/gstr2');?>">GSTR 2</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('gst/gstr3');?>">GSTR 3</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- End Sidemenu -->