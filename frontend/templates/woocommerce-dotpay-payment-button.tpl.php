<form method="post" action=<?php echo esc_attr($dotpay_url) ?>>
    <h3><?php __('Transaction Details', 'dotpay-payment-gateway') ?></h3>

    <p><?php __('You chose payment by Dotpay. Click Continue do proceed', 'dotpay-payment-gateway') ?></p>
	
    <p class="form-submit">
        <input type="hidden" value="<?php echo esc_attr( $customer_id ); ?>" name="id">
        <input type="hidden" value="<?php echo esc_attr( $amount ); ?>" name="amount">
        <input type="hidden" value="<?php echo esc_attr( $firstname ); ?>" name="firstname">
        <input type="hidden" value="<?php echo esc_attr( $lastname ); ?>" name="lastname">
        <input type="hidden" value="<?php echo esc_attr( $street ); ?>" name="street">
        <input type="hidden" value="<?php echo esc_attr( $street_n1 ); ?>" name="street_n1">
        <input type="hidden" value="<?php echo esc_attr( $city ); ?>" name="city">
        <input type="hidden" value="<?php echo esc_attr( $postcode ); ?>" name="postcode">
        <input type="hidden" value="<?php echo esc_attr( $country ); ?>" name="country">
        <input type="hidden" value="<?php echo esc_attr( $email ); ?>" name="email">
        <input type="hidden" value="<?php echo esc_attr( $payment_currency ); ?>" name="currency">
        <input type="hidden" value="<?php echo esc_attr( $return_url ); ?>" name="URL">
        <input type="hidden" value="<?php echo esc_attr( $notify_url ); ?>" name="URLC">
        <input type="hidden" value="<?php echo esc_attr( $payment_type ); ?>" name="type">
        <input type="hidden" value="<?php sprintf(__('Payment for order no. %1$s (%2$s)', 'dotpay-payment-gateway'), esc_attr( $order_id ), bloginfo('title')) ?>" name="description">
        <input class="button" type="submit" value="<?php __('Continue', 'dotpay-payment-gateway') ?>" id="submit_dotpay_payment_form" name="submit">
    </p>
</form>
