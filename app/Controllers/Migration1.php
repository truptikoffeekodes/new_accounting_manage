<?php

namespace App\Controllers;

class Migration extends BaseController
{
    /**
     * Change the database name before execute the function.
     */
    public $database_name = "liv2022rymq";

    /**
     * Alter database column name
     */
    public function alter($type = '')
    {
        $sql    = array();
        $result = array();

        if ($type != '') {
            if ($type == 'sales_challan') {

                $sql = array(
                    "ALTER TABLE `sales_challan` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `sales_challan` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `sales_challan` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`"
                );
            }


            if ($type == 'sales_invoice') {

                $sql = array(
                    "ALTER TABLE `sales_invoice` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `sales_invoice` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `sales_invoice` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`"
                );
            }


            if ($type == 'sales_return') {

                $sql = array(
                    "ALTER TABLE `sales_return` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `sales_return` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `sales_return` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`"


                );
            }


            if ($type == 'sales_item') {

                $sql = array(
                    "ALTER TABLE `sales_item` ADD `discount` DECIMAL(19,2) NOT NULL AFTER `item_disc`, ADD `added_amt` DECIMAL(19,2) NOT NULL AFTER `discount`, ADD `sub_total` DECIMAL(19,2) NOT NULL AFTER `added_amt`",
                    "ALTER TABLE `sales_item` ADD `is_update_subtotal` INT(1) NOT NULL DEFAULT '0' AFTER `update_by`",
                    "ALTER TABLE `sales_item` ADD `is_expence` INT(1) NOT NULL DEFAULT '0' AFTER `parent_id`",
                    "ALTER TABLE `sales_item` ADD `hsn` VARCHAR(255) NOT NULL AFTER `item_id`",
                    "ALTER TABLE `sales_item` ADD `is_update_taxiblity` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_subtotal`"
                    
                );
            }


            if ($type == 'sales_ACinvoice') {

                $sql = array(
                    "ALTER TABLE `sales_ACinvoice` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `sales_acinvoice` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `sales_acinvoice` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`"
                    
                );
            }


            if ($type == 'sales_acparticu') {

                $sql = array(
                    "ALTER TABLE `sales_acparticu` ADD `discount` DECIMAL(19,2) NOT NULL AFTER `sgst_amt`, ADD `added_amt` DECIMAL(19,2) NOT NULL AFTER `discount`, ADD `sub_total` DECIMAL(19,2) NOT NULL AFTER `added_amt`",
                    "ALTER TABLE `sales_acparticu` ADD `is_update_subtotal` INT(1) NOT NULL DEFAULT '0' AFTER `is_delete`"
                );
            }


            if ($type == 'purchase_challan') {

                $sql = array(
                    "ALTER TABLE `purchase_challan` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `purchase_challan` ADD `inv_taxability` VARCHAR(255) NOT NULL AFTER `taxes`",
                    "ALTER TABLE `purchase_challan` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `inv_taxability`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `purchase_challan` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`",

                );
            }


            if ($type == 'purchase_invoice') {

                $sql = array(
                    "ALTER TABLE `purchase_invoice` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `purchase_invoice` ADD `inv_taxability` VARCHAR(255) NOT NULL AFTER `taxes`",
                    "ALTER TABLE `purchase_invoice` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `inv_taxability`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `purchase_invoice` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`",
                );
            }

            if ($type == 'purchase_item') {

                $sql = array(
                    "ALTER TABLE `purchase_item` ADD `is_expence` INT(1) NOT NULL DEFAULT '0' AFTER `parent_id`",
                    "ALTER TABLE `purchase_item` ADD `taxability` VARCHAR(255) NOT NULL AFTER `is_expence`",
                    "ALTER TABLE `purchase_item` ADD `discount` DECIMAL(19,2) NOT NULL AFTER `item_disc`, ADD `added_amt` DECIMAL(19,2) NOT NULL AFTER `discount`, ADD `sub_total` DECIMAL(19,2) NOT NULL AFTER `added_amt`",
                    "ALTER TABLE `purchase_item` ADD `is_update_subtotal` INT(1) NOT NULL DEFAULT '0' AFTER `update_by`",
                    "ALTER TABLE `purchase_item` ADD `igst_amt` INT(1) NOT NULL DEFAULT '0' AFTER `igst`",
                    "ALTER TABLE `purchase_item` ADD `cgst_amt` INT(1) NOT NULL DEFAULT '0' AFTER `cgst`",
                    "ALTER TABLE `purchase_item` ADD `sgst_amt` INT(1) NOT NULL DEFAULT '0' AFTER `sgst`",
                    "ALTER TABLE `purchase_item` ADD `is_update_taxability` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_subtotal`"

                );
            }


            if ($type == 'purchase_general') {

                $sql = array(
                    "ALTER TABLE `purchase_general` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `purchase_general` ADD `gst_no` VARCHAR(255) NOT NULL AFTER `party_account`",
                    "ALTER TABLE `purchase_general` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`, ADD `inv_taxability` VARCHAR(255) NOT NULL AFTER `sgst_acc`",
                    "ALTER TABLE `purchase_general` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`"

                );
            }

            if ($type == 'purchase_particu') {

                $sql = array(
                    "ALTER TABLE `purchase_particu` ADD `igst_amt` DECIMAL(19,2) NOT NULL AFTER `igst`",
                    "ALTER TABLE `purchase_particu` ADD `cgst_amt` DECIMAL(19,2) NOT NULL AFTER `cgst`",
                    "ALTER TABLE `purchase_particu` ADD `sgst_amt` DECIMAL(19,2) NOT NULL AFTER `sgst`",
                    "ALTER TABLE `purchase_particu` ADD `taxability` VARCHAR(255) NOT NULL AFTER `sgst_amt`",
                    "ALTER TABLE `purchase_particu` ADD `discount` DECIMAL(19,2) NOT NULL AFTER `sgst_amt`",
                    "ALTER TABLE `purchase_particu` ADD `added_amt` DECIMAL(19,2) NOT NULL AFTER `discount`",
                    "ALTER TABLE `purchase_particu` ADD `sub_total` DECIMAL(19,2) NOT NULL AFTER `added_amt`",
                );
            }


            if ($type == 'purchase_return') {

                $sql = array(
                    "ALTER TABLE `purchase_return` ADD `gl_group` INT(255) NOT NULL AFTER `id`",
                    "ALTER TABLE `purchase_return` ADD `igst_acc` VARCHAR(255) NOT NULL AFTER `taxes`, ADD `cgst_acc` VARCHAR(255) NOT NULL AFTER `igst_acc`, ADD `sgst_acc` VARCHAR(255) NOT NULL AFTER `cgst_acc`",
                    "ALTER TABLE `purchase_return` ADD `is_update_glgroup` INT(1) NOT NULL DEFAULT '0' AFTER `is_cancle`, ADD `is_update_gst` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_glgroup`, ADD `is_update_discount` INT(1) NOT NULL DEFAULT '0' AFTER `is_update_gst`",
                    "ALTER TABLE `purchase_return` ADD `inv_taxability` VARCHAR(255) NOT NULL AFTER `taxes`"

                );
            }


            /**
             * Execute query
             */

            if (!empty($sql)) {
                $db = \Config\Database::connect();
                $db->setDatabase($this->database_name);

                foreach ($sql as $query) {
                    try {
                        $db->simpleQuery($query);
                        $result[] = array("Successfully Executed" => $query);

                    } catch (\Throwable $th) {
                        $result[] = array("Failed to Executed" => $query);

                    }
                }

                echo '<pre>'; print_r($result); echo "</pre>";
                echo '<p>Executed In {elapsed_time} seconds</p>';
            }
        }
    }


    /**
     * update empty taxability in item, account
     */

    public function update_taxability(){

        $sql = array(
            "UPDATE `account` SET `taxability` = 'N/A' WHERE taxability = '' and is_delete = 0", //account
            "UPDATE `item` SET taxability = 'N/A' WHERE taxability = '' AND is_delete = 0" //item
        );

        /**
         * Execute query
         */

        if (!empty($sql)) {
            $db = \Config\Database::connect();
            $db->setDatabase($this->database_name);

            foreach ($sql as $query) {
                try {
                    $db->simpleQuery($query);
                    $result[] = array("Successfully Executed" => $query, "Updated Rows"=> $db->affectedRows());
                } catch (\Throwable $th) {
                    $result[] = array("Failed to Executed" => $query,"Updated Rows" => $db->affectedRows());
                }
            }

            echo '<pre>';print_r($result);echo "</pre>";
            echo '<p>Executed In {elapsed_time} seconds</p>';
        }
    }
}
