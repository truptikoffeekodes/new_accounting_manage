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
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Account/voucher')?>">Voucher Type</a>
                            </li>
                        </ul>
                    </li>

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

                    <li class="nav-item ">
                        <a class="nav-link with-sub" href="#"><i class="fe fe-box"></i><span
                                class="sidemenu-label">Others</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/billterm')?>">Billterm</a>
                            </li>

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/warehouse')?>">Warehouse</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/transport') ?>">Transport</a>
                            </li>
                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/supervisor') ?>">Supervisor</a>
                            </li>

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('master/uom')?>">UOM</a>
                            </li>


                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/vehicle') ?>">Vehicle</a>
                            </li>

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?= url('master/hsn') ?>">HSN</a>
                            </li>


                        </ul>
                    </li>
                </ul>
            </li>


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

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('sales/ac_invoice')?>">General Sales</a>
                            </li>

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

                            <li class="nav-sub-item ">
                                <a class="nav-sub-link" href="<?=url('Bank/unreconsilation')?>">Unlink
                                    Reconciliation</a>
                            </li>
                        </ul>
                    </li>

                    <!------------------------------ -->
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
                        <a class="nav-sub-link" href="<?=url('Profitloss/pl_dashboard')?>">Profit/Loss</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Balancesheet')?>">Balance Sheet</a>
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

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/all_stock')?>">ALL</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/gray_stock')?>">Gray Stock </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/mill_stock')?>">Mill Stock </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/finish_stock')?>">Finish Stock </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/job_stock')?>">Jobwork Stock </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/recjob_stock')?>">Received Jobwork Stock </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-sub-link" href="<?=url('Stock/avg_price')?>">Average Price </a>
                    </li>
                </ul>

            </li>



            <li class="nav-label">Register Reports</li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">Account
                        Book</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Sales_register');?>">Sales Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Sales_gst_register');?>">Sales GST Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Sales_gst_register2');?>">Sales GST Register 2</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_sales_register');?>">General Sales
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_sales_gst_register');?>">General Sales GST
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Purchase_register')?>">Purchase Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Purchase_gst_register');?>">Purchase GST
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Purchase_gst_register2');?>">Purchase GST Register
                            2</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_purchase_register');?>">General Purchase
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_purchase_gst_register');?>">General Purchase
                            GST Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Sales_return_register')?>">Credit Note
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Creditnote_gst_register')?>">Credit Note GST
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Purchase_return_register')?>">Debit Note
                            Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_purchase_rtn_register')?>">General Purchase
                            Return</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Gnrl_sales_rtn_register')?>">General Sales
                            Return</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/payment')?>">Payment Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/receipt')?>">Receipts Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/contra')?>">Contra Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/cash')?>">Cash Register</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/bank')?>">Bank Register</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/journal')?>">Journal Register</a>
                    </li>



                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">Other Books</span><i class="angle fe fe-chevron-right"></i></a>

                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/ledger')?>">Ledger Register</a>
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
                        <a class="nav-sub-link" href="<?=url('Addbook/Ledger_outstanding')?>">Single Ledger Outstandig</a>
                    </li>
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/Ledger_outstanding_report')?>">Ledger Outstandig Report</a>
                    </li>

                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Addbook/closing_bal_report')?>">Closing Balance Report</a>
                    </li>
                </ul>
            </li>


            <li class="nav-label">GST</li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">GST</span><i
                        class="angle fe fe-chevron-right"></i></a>
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

            <li class="nav-label">Other</li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-box"></i><span class="sidemenu-label">Other</span><i
                        class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Testing/hsn_core_data');?>">HSN Core Data</a>
                    </li>
                </ul>
                <?php
                //print_r(session('name'));exit;
                if(session('code') == 'ACE20223HUY' OR session('code') == 'KLA2022ZFDH' OR session('code') == 'TRU20220AOS')
                {
                ?>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Testing/Invoice_list');?>">JV Management</a>
                    </li>
                </ul>
                <?php
                }
                ?>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Testing/shortcut_keys_list');?>">Shortcut Key List</a>
                    </li>
                </ul>
                <ul class="nav-sub">
                    <li class="nav-sub-item ">
                        <a class="nav-sub-link" href="<?=url('Testing/tds_report');?>">Tds Report</a>
                    </li>
                </ul>
                
            </li>
          
        </ul>
    </div>
</div>
<!-- End Sidemenu -->