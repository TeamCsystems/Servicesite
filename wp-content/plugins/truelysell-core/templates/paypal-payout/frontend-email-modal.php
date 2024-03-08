<?php

if (! defined('ABSPATH'))
    exit;

?>

<div class="wrapper-paypal-payout-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php _e('Email Required', 'truelysell'); ?></h5>
        </div>
        <div class="modal-body">
            <?php _e('Please add your email address. This email address will use to send you the commission by using PayPal Payout service.', 'truelysell'); ?>

            <form action="#" method="post">
                <label for="truelysell_paypal_payout_email"><?php _e('PayPal Payout Email', 'truelysell'); ?></label>
                <input type="email" name="truelysell_paypal_payout_email" value="" class="truelysell_paypal_payout_email" id="truelysell_paypal_payout_email">
                <input type="submit" value="Save" class="truelysell_paypal_payout_email_save_btn" id="truelysell_paypal_payout_email_save_btn">
                <span class="truelysell-loader-wrapper truelysell_hidden">
                    <span class="fa fa-spinner fa-spin"></span>
                </span>

                <div class="truelysell-errors-wrapper truelysell-hidden">
                    <div class="truelysell-error-message"></div>
                </div>

                <div class="truelysell-success-wrapper truelysell-hidden">
                    <div class="truelysell-success-message"></div>
                </div>
            </form>
        </div>
    </div>
</div>
