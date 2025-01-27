<?php

if (! defined('ABSPATH'))
    exit;

require __DIR__ . '/../vendor/autoload.php';


//use Sample\PayPalClient;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;
use PayPalHttp\HttpException;
use PaypalPayoutsSDK\Payouts\PayoutsGetRequest;

class TruelysellPayPalSandboxClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client($client_id, $client_secret)
    {
        return new PayPalHttpClient(self::environment($client_id, $client_secret));
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment($client_id, $client_secret)
    {
        return new SandboxEnvironment($client_id, $client_secret);
    }
}

class TruelysellPayPalPayOut
{
    private static $client = null;

    public static function buildRequestBody($receiver, $sender_item_id, $amount, $currency, $note = '', $email_subject = null, $email_message = null, $recipient_type = 'EMAIL')
    {
        $allowed_recipient_types = [
            'EMAIL',
            'PHONE'
        ];

        if (! in_array($recipient_type, $allowed_recipient_types, true)){
            truelysell_plugin_logs('Recipient type is not allowed. Recipient can be Email or Phone. But provided `' . $recipient_type . '`');
            return false;
        }

        $currency = strtoupper($currency);

        if ($email_subject === null){
            $db_email_subject = esc_attr(truelysell_fl_framework_getoptions('payout_email_subject'));

            if (!empty($db_email_subject)){
                $email_subject = $db_email_subject;
            }else{
                $email_subject = 'Here is your commission.';
            }
        }

        if ($email_message === null){
            $db_email_message = esc_attr(truelysell_fl_framework_getoptions('payout_email_message'));

            if (!empty($db_email_message)){
                $email_message = $db_email_message;
            }else{
                $email_message = 'You have received a payout (commission)! Thanks for using our service!';
            }
        }

        if (empty($note)){
            $db_trx_note = esc_attr(truelysell_fl_framework_getoptions('payout_trx_note'));

            if (!empty($db_trx_note)){
                $note = $db_trx_note;
            }
        }

        $amount = (float) $amount;
        $amount = number_format($amount, 2);

        return json_decode(
            '{
                    "sender_batch_header":
                    {
                      "email_subject": "'.$email_subject.'",
                      "email_message": "'.$email_message.'"
                    },
                    "items": 
                    [
                        {
                          "recipient_type": "'.$recipient_type.'",
                          "receiver": "'.$receiver.'",
                          "note": "'.$note.'",
                          "sender_item_id": "'.$sender_item_id.'",
                          "amount":
                          {
                            "currency": "'.$currency.'",
                            "value": "'.$amount.'"
                          }
                        }
                    ]
                }',
            true
        );
    }

    public function get_pp_client(){
        if (! self::$client)
            self::setup_pp_client();

        return self::$client;
    }

    private static function setup_pp_client()
    {
        $env = esc_attr(truelysell_fl_framework_getoptions('payout_environment'));

        if (empty($env)){
            $env = 'sandbox';
        }

        if ($env === 'sandbox'){
            $clientId = esc_attr(truelysell_fl_framework_getoptions('payout_sandbox_client_id'));
            $clientSecret = esc_attr(truelysell_fl_framework_getoptions('payout_sandbox_client_secret'));

            self::$client = TruelysellPayPalSandboxClient::client($clientId, $clientSecret);
        }else {
            // @todo: need to check -- Error can be on ProductionEnvironment
            $clientId = esc_attr(truelysell_fl_framework_getoptions('payout_live_client_id'));
            $clientSecret = esc_attr(truelysell_fl_framework_getoptions('payout_live_client_secret'));

            self::$client = new \PaypalPayoutsSDK\Core\ProductionEnvironment($clientId, $clientSecret);
        }
    }

    /**
     * This function can be used to create payout.
     */
    public static function CreatePayout($receiver, $sender_item_id, $amount, $currency, $note = '', $email_subject = null, $email_message = null, $recipient_type = 'EMAIL', $debug = false)
    {
        try {
            $request = new PayoutsPostRequest();
            $request->body = self::buildRequestBody($receiver, $sender_item_id, $amount, $currency, $note, $email_subject, $email_message);

            if ($request->body === false){
                return false;
            }

            if (! self::$client)
                self::setup_pp_client();

            $response = self::$client->execute($request);
            if ($debug) {
                print "Status Code: {$response->statusCode}\n";
                print "Status: {$response->result->batch_header->batch_status}\n";
                print "Batch ID: {$response->result->batch_header->payout_batch_id}\n";
                print "Links:\n";

                echo "<br />\n<br />";
                $batchId = $response->result->batch_header->payout_batch_id;
                $request = new PayoutsGetRequest($batchId);
                $response = self::$client->execute($request);
                echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
                echo "<br />\n<br />";

                foreach ($response->result->links as $link) {
                    print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
                }
                // To toggle printing the whole response body comment/uncomment below line
                echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
            }
            return $response;
        } catch (HttpException $e) {
            //Parse failure response
            echo $e->getMessage() . "\n";
            $error = json_decode($e->getMessage());
            echo $error->message . "\n";
            echo $error->name . "\n";
            echo $error->debug_id . "\n";
        }
    }
}

if (! function_exists('truelysell_send_commission_to_listing_owners')){
    add_action('woocommerce_order_status_changed', 'truelysell_send_commission_to_listing_owners', 999, 4);
    function truelysell_send_commission_to_listing_owners($order_id, $from, $to, $order_obj){

        global $wpdb;

        if ($to !== 'completed'){
            return;
        }

        if (! truelysell_is_payout_active()){
            return;
        }

        $is_payout_processed = esc_attr(get_post_meta($order_id, 'truelysell_payout_processed', true));

        if ($is_payout_processed === 'yes'){
            truelysell_plugin_logs('Payout is already processed for Order ID:'.$order_id);
            return;
        }

        $order = wc_get_order($order_id);
        $order_data = $order->get_data();

        $line_items = $order_data['line_items'];

        /**
         * @var $item WC_Order_Item_Product
         */
        foreach ($line_items as $item){
            $product_data[] = $item->get_data();
        }

        $p_data = $product_data[0];
        $post = get_post($p_data['product_id']);

        $owner_id = $post->post_author;
        $owner_email = esc_attr(get_user_meta($owner_id, 'truelysell_paypal_payout_email', true));

        if (! is_email($owner_email)){
            truelysell_plugin_logs('Unable to send payout (commission) to listing owner `User ID: ('.$owner_id.')` because the PayPal Payout email address is not a valid email. Provided PayPal Payout Email address is `'.$owner_email.'`');
            return false;
        }

        $sender_item_id = 'postid_' . $post->ID . '__oid_' . $order->get_id() . '__proid_' . $p_data['product_id'];


        $note = esc_attr(truelysell_fl_framework_getoptions('payout_trx_note'));
        $db_email_subject = esc_attr(truelysell_fl_framework_getoptions('payout_email_subject'));
        $db_email_message = esc_attr(truelysell_fl_framework_getoptions('payout_email_message'));

        $total_price = $p_data['total'];
        $truelysell_commission_rate = esc_attr(truelysell_fl_framework_getoptions('commission_rate'));
        $commission_rate = apply_filters('truelysell_commission_rate', $truelysell_commission_rate, $owner_id);

        $owner_earning = ($commission_rate / 100) * $total_price;
        $currency = get_woocommerce_currency();

        // Run the payout script here.
        $response = TruelysellPayPalPayOut::CreatePayout($owner_email, $sender_item_id, $owner_earning , $currency, $note, $db_email_subject, $db_email_message);

        if ($response !== false){
            if ($response->statusCode >= 200 && $response->statusCode < 300){

                $batchId = $response->result->batch_header->payout_batch_id;

                // get PayOut items
                $request = new PayoutsGetRequest($batchId);
                $payout_item_response = (new TruelysellPayPalPayOut())->get_pp_client()->execute($request);

                if (isset($payout_item_response->result->items) && isset($payout_item_response->result->items[0])){

                    $item = $payout_item_response->result->items[0];

                    $status_code = $payout_item_response->statusCode;
                    $payout_batch_id = $payout_item_response->result->batch_header->payout_batch_id;
                    $batch_status = $payout_item_response->result->batch_header->batch_status;
                    $time_created = $payout_item_response->result->batch_header->time_created;
                    $time_completed = $payout_item_response->result->batch_header->time_completed;

                    $fee_currency = $item->payout_item_fee->currency;
                    $fee_value = $item->payout_item_fee->value;

                    $funding_source = $payout_item_response->result->batch_header->funding_source;

                    $currency = $item->payout_item->amount->currency;
                    $amount = $item->payout_item->amount->value;

                    $payout_item_id = $item->payout_item_id;
                    $transaction_id = $item->transaction_id;
                    $activity_id = $item->activity_id;
                    $trx_status = $item->transaction_status;

                    if (isset($item->links[0])){
                        $link = $item->links[0]->href;
                    }


                    if (strtolower($trx_status) !== 'success'){
                        sleep(2);
                        $details = truelysell_get_payout_item_details($item->payout_item_id);

                        if (isset($details->result) && $details->result->errors){
                            $error_name = $details->result->errors->name;
                            $error_message = $details->result->errors->message;
                        }else{
                            $trx_status = $details->result->transaction_status;
                        }
                    }

                    $commission_table = $wpdb->prefix . 'truelysell_core_commissions';

                    $pp_data = [
                        'pp_status_code' => $status_code,
                        'payout_batch_id' => $payout_batch_id,
                        'batch_status' => $batch_status,
                        'time_created' => $time_created,
                        'time_completed' => $time_completed,
                        'fees_currency' => $fee_currency,
                        'fee_value' => $fee_value,
                        'funding_source' => $funding_source,
                        'sent_amount_currency' => $currency,
                        'sent_amount_value' => $amount,
                        'payout_item_id' => $payout_item_id,
                        'payout_item_transaction_id' => $transaction_id,
                        'payout_item_activity_id' => $activity_id,
                        'payout_item_transaction_status' => $trx_status,
                        'error_name' => $error_name,
                        'error_message' => $error_message,
                        'payout_item_link' => $link
                    ];

                    if (strtolower($trx_status) === 'success'){
                        $pp_data['status'] = 'paid';
                    }

                    $is_updated = $wpdb->update(
                        $commission_table,
                        $pp_data,
                        [
                            'order_id' => $order_id
                        ]
                    );

                    if (! $is_updated){

                        $msg = 'Unable to update commission table `'.$commission_table.'`. Reason Unknown!!!';
                        $msg .= PHP_EOL . 'Following was the data at this stage: ' . PHP_EOL . PHP_EOL;
                        $msg .= var_export($pp_data, true);
                        $msg .= PHP_EOL . PHP_EOL;

                        truelysell_plugin_logs($msg);
                        return false;
                    }else if (strtolower($trx_status) === 'success'){
                        // update the payout table.
                        $commission_payout_table = $wpdb->prefix . 'truelysell_core_commissions_payouts';

                        $row = $wpdb->get_row("SELECT * FROM {$commission_table} WHERE order_id = $order_id");
                        $p_user_id = $row->user_id;

                        $payout_rows = $wpdb->get_results(
                            "SELECT id FRoM {$commission_payout_table} WHERE user_id = $p_user_id", ARRAY_A
                        );

                        $counter = count($payout_rows);

                        $args = array(
                            'user_id'         => $row->user_id,
                            'status'          => 'paid',
                            'orders'          => json_encode([++$counter]),
                            'date'            => current_time('mysql'),
                            'amount'          => $amount,
                            'payment_method'  => 'PayPal Payout',
                            'payment_details'  => 'PayOut Batch ID:' . $payout_batch_id . '|||Payout Item ID:' . $payout_item_id

                        );

                        $wpdb->insert(
                            $commission_payout_table,
                            $args
                        );
                    }
                }
            }else {

                $msg = 'PayPal Create Payout response was not OK (Success).';
                $msg .= PHP_EOL . 'Following was the response data at this stage: ' . PHP_EOL . PHP_EOL;
                $msg .= var_export($response, true);
                $msg .= PHP_EOL . PHP_EOL;

                truelysell_plugin_logs($msg);
                return false;
            }

            update_post_meta($order_id, 'truelysell_payout_processed', 'yes');
        }



        // Pending
        // 1. Enable/disable option by admin for payout. based on that option the above hook/code run or not run. [Done]
        // 2. ask the listing owner to set the email address (forcefully) [Done]

        // 3. create and send the payout [Done]
        //    * Save the success transaction [Done]
        //    * Listing Owner can view the wallet  [Done]
        //    * Admin can view the details about the transaction [Done]

        // 4. Crease statuses for the payout - Claimed/Unclaimed etc. So, admin can view from the dashboard [Done]
        //    * and get the payout details (for unclaimed) at time opening commission url in admin side [Done]
        //    * and can request to cancel the payout [Done]
        //    * Upon cancellation, admin can send the payment manually. As previously. [Done]

        // 5. Restructure the code (if needed)
        //    * Remove all todos [Done]
        //    * check the scope on CA [Done]

        // 6. Testing
    }
}

if (! function_exists('truelysell_get_payout_item_details')){
    function truelysell_get_payout_item_details($payout_item_id){
        // get item details
        $request = new \PaypalPayoutsSDK\Payouts\PayoutsItemGetRequest($payout_item_id);
        $payout_item_details_response = (new TruelysellPayPalPayOut())->get_pp_client()->execute($request);
        return $payout_item_details_response;
    }
}

if (! function_exists('truelysell_is_payout_active')){
    function truelysell_is_payout_active(){
        $is_payout_feature_active = esc_attr(truelysell_fl_framework_getoptions('payout_activation'));

        if ($is_payout_feature_active === 'yes'){
            return true;
        }

        return false;
    }
}

if (! function_exists('truelysell_display_email_modal')){
    function truelysell_display_email_modal(){
        global $post;

        if (! truelysell_is_payout_active()){
            return;
        }

        // check if current user is admin or listing owner or shop manager.
        if (wc_current_user_has_role('administrator') || wc_current_user_has_role('owner') || wc_current_user_has_role('shop_manager')){
            $truelysell_dashboard_pages = [
                esc_attr(truelysell_fl_framework_getoptions('dashboard_page')), // Dashboard
                esc_attr(truelysell_fl_framework_getoptions('user_bookings_page')), // My Booking
                esc_attr(truelysell_fl_framework_getoptions('messages_page')), // Messages
                esc_attr(truelysell_fl_framework_getoptions('bookings_page')), // Bookings
                esc_attr(truelysell_fl_framework_getoptions('wallet_page')), // Wallet
                esc_attr(truelysell_fl_framework_getoptions('payout_page')), // Wallet
                esc_attr(truelysell_fl_framework_getoptions('submit_page')), // Add Listing
                esc_attr(truelysell_fl_framework_getoptions('listings_page')), // My Listings
                esc_attr(truelysell_fl_framework_getoptions('coupons_page')), // Coupons
                esc_attr(truelysell_fl_framework_getoptions('reviews_page')), // Review
                esc_attr(truelysell_fl_framework_getoptions('profile_page')), // My Profile
            ];

            $truelysell_dashboard_pages = apply_filters('truelysell_dashboard_page_ids', $truelysell_dashboard_pages);

            if ( in_array($post->ID, $truelysell_dashboard_pages) ){

                $is_payout_email_added = esc_attr(get_user_meta(get_current_user_id(), 'truelysell_paypal_payout_email', true));

                if (empty($is_payout_email_added)){
                    // display modal.

                    $template_loader = new Truelysell_Core_Template_Loader;
                }
            }
        }
    }
}

if (! function_exists('truelysell_add_paypal_payout_user_email_ajax_request')){
    function truelysell_add_paypal_payout_user_email_ajax_request(){
        $email = isset($_REQUEST['email_address']) ? sanitize_text_field($_REQUEST['email_address']) : '';

        if (is_email($email)){
            update_user_meta(get_current_user_id(), 'truelysell_paypal_payout_email', $email);

            $success_msg = apply_filters( 'truelysell_paypal_payout_valid_email_msg', __('Thank you for adding the email address.', 'truelysell') );
            wp_send_json_success( [ 'success' => true, 'msg' => $success_msg ] );
            exit;
        }else{
            $error_msg = apply_filters( 'truelysell_paypal_payout_invalid_email_msg', __('The email is not valid. Please add a valid email address.', 'truelysell') );
            wp_send_json_error( [ 'success' => false, 'msg' => $error_msg ] );
            exit;
        }
    }

    add_action('wp_ajax_truelysell_add_paypal_payout_user_email', 'truelysell_add_paypal_payout_user_email_ajax_request');
}

if (! function_exists('truelysell_cancel_payout_request')){
    function truelysell_cancel_payout_request(){

        if (!current_user_can('manage_options')){
            wp_send_json_error( [ 'success' => false, 'msg' => __('Permission denied', 'truelysell') ] );
            wp_die();
        }

        $pp_item_id = isset($_REQUEST['pp_item_id']) ? sanitize_text_field($_REQUEST['pp_item_id']) : '';
        $commission_id = isset($_REQUEST['commission_id']) ? sanitize_text_field($_REQUEST['commission_id']) : '';

        if (! empty($pp_item_id) && !empty($commission_id)){
            global $wpdb;

            try{
                $request = new \PaypalPayoutsSDK\Payouts\PayoutsItemCancelRequest(($pp_item_id));
                $pp_cancel_request_response = (new TruelysellPayPalPayOut())->get_pp_client()->execute($request);


                $wpdb->update(
                    $wpdb->prefix . 'truelysell_core_commissions',
                    [
                        'payout_item_transaction_status' => $pp_cancel_request_response->result->transaction_status,
                    ],
                    [
                        'id' => $commission_id
                    ]
                );

                $success_msg =  __('The item is cancelled successfully.', 'truelysell' );
                wp_send_json_success( [ 'success' => true, 'msg' => $success_msg ] );
                exit;
            }catch (HttpException $exception){

                $exception_data = json_decode($exception->getMessage());

                if ($exception_data->name === 'ITEM_ALREADY_CANCELLED'){
                    // try again to update the db.
                    $wpdb->update(
                        $wpdb->prefix . 'truelysell_core_commissions',
                        [
                            'payout_item_transaction_status' => 'RETURNED',
                        ],
                        [
                            'id' => $commission_id
                        ]
                    );

                    $success_msg =  __('The item is cancelled successfully.', 'truelysell' );
                    wp_send_json_success( [ 'success' => true, 'msg' => $success_msg ] );
                    exit;
                }

                wp_send_json_error( [ 'success' => false, 'name' => $exception_data->name, 'msg' => $exception_data->message ] );
                exit;
            }
        }else{
            $error_msg = __('The item id or commission id is missing. Please refresh the page and try again.');
            wp_send_json_error( [ 'success' => false, 'msg' => $error_msg ] );
            exit;
        }
    }

    add_action('wp_ajax_truelysell_cancel_payout_request', 'truelysell_cancel_payout_request');
}

if (! function_exists('truelysell_plugin_logs')){
    function truelysell_plugin_logs($err_msg){

        $key = esc_attr(get_option('truelysell_debug_key'));

        if (empty($key)){
            $key = wp_generate_password(10, false, false);
            update_option('truelysell_debug_key', $key);
        }

        $pluginlog = REALTEO_PLUGIN_DIR . 'debug-' . $key .'.log';
        $message = PHP_EOL . '----' . PHP_EOL . 'SOME ERROR: '. $err_msg . PHP_EOL . 'DATE: ' . date('d-m-Y H:i:s') . PHP_EOL . '----' . PHP_EOL;
        error_log($message, 3, $pluginlog);
    }
}