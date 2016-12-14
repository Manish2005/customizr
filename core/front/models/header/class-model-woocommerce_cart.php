<?php
class CZR_woocommerce_cart_model_class extends CZR_Model {

  public $display_widget;
  public $defaults = array( 'element_tag' => 'div' );

  public function __construct( $model ) {
    parent::__construct( $model);
    add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'czr_fn_woocommerce_add_to_cart_fragment' ) );
  }

  function czr_fn_extend_params( $model = array() ) {
    $_model = array(
      'display_widget' => function_exists( 'czr_fn_wc_is_checkout_cart' ) ? ! czr_fn_wc_is_checkout_cart() : true,
    );

    return array_merge( $model, $_model );
  }

  // Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
  function czr_fn_woocommerce_add_to_cart_fragment( $fragments ) {
    if ( 1 == esc_attr( czr_fn_get_opt( 'tc_woocommerce_header_cart' ) ) ) {
      $fragments['sup.czr-wc-count'] = $this -> czr_fn_get_wc_cart_count_html();
    }
    return $fragments;
  }

  function czr_fn_get_wc_cart_count_html() {
    $WC          = WC();
    $_cart_count = $WC->cart->get_cart_contents_count();
    return sprintf( '<sup class="count czr-wc-count">%1$s</sup>', $_cart_count ? $_cart_count : '' );
  }


  /**
  * @hook czr_fn_user_options_style
  */
  function czr_fn_user_options_style_cb( $_css ) {
    return sprintf( "%s\n%s",
          $_css,
          ".sticky-enabled .czr-wccart-off .primary-nav__woocart { display: none; }
    ");
  }/*end rendering the cart icon in the header */
}//end class