<?php

if (!defined('ABSPATH')) exit;

/**
 * Truelysell Core Widget base
 */
class Truelysell_Core_Widget extends WP_Widget
{
	/**
	 * Widget CSS class
	 *
	 * @access public
	 * @var string
	 */
	public $widget_cssclass;

	/**
	 * Widget description
	 *
	 * @access public
	 * @var string
	 */
	public $widget_description;

	/**
	 * Widget id
	 *
	 * @access public
	 * @var string
	 */
	public $widget_id;

	/**
	 * Widget name
	 *
	 * @access public
	 * @var string
	 */
	public $widget_name;

	/**
	 * Widget settings
	 *
	 * @access public
	 * @var array
	 */
	public $settings;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->register();
	}


	/**
	 * Register Widget
	 */
	public function register()
	{
		$widget_ops = array(
			'classname'   => $this->widget_cssclass,
			'description' => $this->widget_description
		);

		parent::__construct($this->widget_id, $this->widget_name, $widget_ops);

		add_action('save_post', array($this, 'flush_widget_cache'));
		add_action('deleted_post', array($this, 'flush_widget_cache'));
		add_action('switch_theme', array($this, 'flush_widget_cache'));
	}



	/**
	 * get_cached_widget function.
	 */
	public function get_cached_widget($args)
	{

		return false;

		$cache = wp_cache_get($this->widget_id, 'widget');

		if (!is_array($cache))
			$cache = array();

		if (isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return true;
		}

		return false;
	}

	/**
	 * Cache the widget
	 */
	public function cache_widget($args, $content)
	{
		$cache[$args['widget_id']] = $content;

		wp_cache_set($this->widget_id, $cache, 'widget');
	}

	/**
	 * Flush the cache
	 * @return [type]
	 */
	public function flush_widget_cache()
	{
		wp_cache_delete($this->widget_id, 'widget');
	}

	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		if (!$this->settings)
			return $instance;

		foreach ($this->settings as $key => $setting) {
			$instance[$key] = sanitize_text_field($new_instance[$key]);
		}

		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @access public
	 * @param array $instance
	 * @return void
	 */
	function form($instance)
	{

		if (!$this->settings)
			return;

		foreach ($this->settings as $key => $setting) {

			$value = isset($instance[$key]) ? $instance[$key] : $setting['std'];

			switch ($setting['type']) {
				case 'text':
?>
					<p>
						<label for="<?php echo $this->get_field_id($key); ?>"><?php echo $setting['label']; ?></label>
						<input class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>" type="text" value="<?php echo esc_attr($value); ?>" />
					</p>
				<?php
					break;
				case 'checkbox':
				?>
					<p>
						<label for="<?php echo $this->get_field_id($key); ?>"><?php echo $setting['label']; ?></label>
						<input class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>" type="checkbox" <?php checked(esc_attr($value), 'on'); ?> />
					</p>
				<?php
					break;
				case 'number':
				?>
					<p>
						<label for="<?php echo $this->get_field_id($key); ?>"><?php echo $setting['label']; ?></label>
						<input class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>" type="number" step="<?php echo esc_attr($setting['step']); ?>" min="<?php echo esc_attr($setting['min']); ?>" max="<?php echo esc_attr($setting['max']); ?>" value="<?php echo esc_attr($value); ?>" />
					</p>
				<?php
					break;
				case 'dropdown':
				?>
					<p>
						<label for="<?php echo $this->get_field_id($key); ?>"><?php echo $setting['label']; ?></label>
						<select class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>">

							<?php foreach ($setting['options'] as $key => $option_value) { ?>
								<option <?php selected($value, $key); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($option_value); ?></option>
							<?php } ?>
						</select>

					</p>
			<?php
					break;
			}
		}
	}

	/**
	 * widget function.
	 *
	 * @see    WP_Widget
	 * @access public
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget($args, $instance)
	{
	}
}


/**
 * Featured listings Widget
 */
class Truelysell_Core_Featured_Properties extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wp_post_types;

		$this->widget_cssclass    = 'truelysell_core widget_featured_listings';
		$this->widget_description = __('Display a list of featured listings on your site.', 'truelysell_core');
		$this->widget_id          = 'widget_featured_listings';
		$this->widget_name        =  __('Featured Properties', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Featured Properties', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 10,
				'label' => __('Number of listings to show', 'truelysell_core')
			)
		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{


		ob_start();

		extract($args);

		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$number = absint($instance['number']);
		$listings   = new WP_Query(array(
			'posts_per_page' => $number,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_type' 	 => 'listing',
			'meta_query'     =>  array(
				array(
					'key'     => '_featured',
					'value'   => 'on',
					'compare' => '=',
				),
				array('key' => '_thumbnail_id')
			)
		));

		$template_loader = new Truelysell_Core_Template_Loader;
		if ($listings->have_posts()) : ?>

			<?php echo $before_widget; ?>

			<?php if ($title) echo $before_title . $title . $after_title; ?>

			<div class="widget-listing-slider dots-nav" data-slick='{"autoplay": true, "autoplaySpeed":3000}'>
				<?php while ($listings->have_posts()) : $listings->the_post(); ?>
					<div class="fw-carousel-item">
						<?php
						$template_loader->get_template_part('content-listing-grid');
						?>
					</div>
				<?php endwhile; ?>
			</div>

			<?php echo $after_widget; ?>

		<?php else : ?>

			<?php $template_loader->get_template_part('listing-widget', 'no-content'); ?>

		<?php endif;

		wp_reset_postdata();

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}
}


/**
 * Save & Print listings Widget
 */
class Truelysell_Core_Bookmarks_Share_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wp_post_types;

		$this->widget_cssclass    = 'truelysell_core widget_buttons';
		$this->widget_description = __('Display a Bookmarks and share buttons.', 'truelysell_core');
		$this->widget_id          = 'widget_buttons_listings';
		$this->widget_name        =  __('Truelysell Bookmarks & Share', 'truelysell_core');
		$this->settings           = array(
			'bookmarks' => array(
				'type'  => 'checkbox',
				'std'	=> 'on',
				'label' => __('Bookmark button', 'truelysell_core')
			),
			'share' => array(
				'type'  => 'checkbox',
				'std'	=> 'on',
				'label' => __('Share buttons', 'truelysell_core')
			),

		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{
		if ($this->get_cached_widget($args)) {
			return;
		}

		ob_start();

		extract($args);

		global $post;
		$share = (isset($instance['share'])) ? $instance['share'] : '';
		$bookmarks = (isset($instance['bookmarks'])) ? $instance['bookmarks'] : '';

		echo $before_widget;

		?>
		<div class="listing-share">

			<?php
			if (!empty($bookmarks)) :

				$nonce = wp_create_nonce("truelysell_core_bookmark_this_nonce");

				$classObj = new Truelysell_Core_Bookmarks;

				if ($classObj->check_if_added($post->ID)) { ?>
					<button onclick="window.location.href='<?php echo get_permalink(truelysell_fl_framework_getoptions('bookmarks_page')) ?>'" class="btn btn-primary like-button save liked"><span class="like-icon liked"></span><i class="feather-heart me-2"></i><?php esc_html_e('Favourited', 'truelysell_core') ?>
					</button>
					<?php } else {
					if (is_user_logged_in()) { 
						$current_user = wp_get_current_user();
						$user_id = get_current_user_id();
						$roles = $current_user->roles;
						$role = array_shift($roles);
						?>
						<button class="like-button truelysell_core-bookmark-it btn btn-primary" data-post_id="<?php echo esc_attr($post->ID); ?>" data-confirm="<?php esc_html_e('Favourited', 'truelysell_core'); ?>" data-nonce="<?php echo esc_attr($nonce); ?>"><span class="like-icon "></span> <i class="feather-heart me-2"></i><?php esc_html_e('Favourite', 'truelysell_core') ?>
						</button>
						<?php } else {
						$popup_login = truelysell_fl_framework_getoptions('popup_login', 'ajax');
						if ($popup_login == 'ajax') { ?>
							<button href="#sign-in-dialog" class="like-button-notlogged sign-in popup-with-zoom-anim"><span class="like-icon"></span> <i class="feather-heart me-2"></i><?php esc_html_e('Login To Favourite', 'truelysell_core') ?></button>
						<?php } else {
							$login_page = truelysell_fl_framework_getoptions('profile_page'); ?>
							<a href="<?php echo esc_url(get_permalink($login_page)); ?>" class="like-button-notlogged btn btn-primary"><i class="feather-heart me-2"></i><?php esc_html_e('Login To Favourite', 'truelysell_core') ?></a>
						<?php } ?>
					<?php } ?>

				<?php }

				$count = get_post_meta($post->ID, 'bookmarks_counter', true);
				if ($count) :
					if ($count < 0) {
						$count = 0;
					} ?>
				<?php endif; ?>
			<?php
			endif;
			if (!empty($share)) :
				$id = $post->ID;
				$title = urlencode($post->post_title);
				$url =  urlencode(get_permalink($id));
				$summary = urlencode(truelysell_string_limit_words($post->post_excerpt, 20));
				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'medium');
				if ($thumb) {
					$imageurl = urlencode($thumb[0]);
				} else {
					$imageurl = false;
				}

			?>
				<ul class="share-buttons margin-bottom-0">
					<li><?php echo '<a target="_blank" class="fb-share" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '"><i class="fa fa-facebook"></i> ' . esc_html__('Share', 'truelysell_core') . '</a>'; ?></li>
					<li><?php echo '<a target="_blank" class="twitter-share" href="https://twitter.com/share?url=' . $url . '&amp;text=' . esc_attr($summary) . '" title="' . __('Twitter', 'truelysell_core') . '"><i class="fa fa-twitter"></i> Tweet</a>'; ?></li>
					<li><?php echo '<a target="_blank"  class="pinterest-share" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . esc_attr($summary) . '&media=' . esc_attr($imageurl) . '" onclick="window.open(this.href); return false;"><i class="fa fa-pinterest-p"></i> Pin It</a>'; ?></li>
				</ul>

				<div class="clearfix"></div>

			<?php endif;
			?>
		</div>
	<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}
}


/**
 * Featured listings Widget
 */
class Truelysell_Core_Contact_Vendor_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wp_post_types;

		$this->widget_cssclass    = 'truelysell_core  boxed-widget message-vendor ';
		$this->widget_description = __('Display a Contact form.', 'truelysell_core');
		$this->widget_id          = 'widget_contact_widget_truelysell';
		$this->widget_name        =  __('Truelysell Contact Widget', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Message Vendor', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),

			'contact' => array(
				'type'  => 'dropdown',
				'std'	=> '',
				'options' => $this->get_forms(),
				'label' => __('Choose contact form', 'truelysell_core')
			),
		);
		$this->register();


	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{

		global $post;
		$contact_enabled = get_post_meta($post->ID, '_email_contact_widget', true);

		if (!$contact_enabled) {
			return;
		}

		ob_start();

		extract($args);

		echo $before_widget;
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

	?>
		<h3><i class="fa fa-envelope-o"></i> <?php echo $title ?></h3>
		<div class="with-forms  margin-top-0">
			<?php
			if (get_post($instance['contact'])) {
				echo do_shortcode(sprintf('[contact-form-7 id="%s"]', $instance['contact']));
			} else {
				echo 'Please choose "Contact Owner Widget" form in Appearance  → Widgets  (Single Listing Sidebar  → Truelysell Contact Widget)';
				echo ' <a href="http://www.docs.purethemes.net/truelysell/knowledge-base/how-to-configure-message-vendor-form/">More information.</a>';
			} ?>
		</div>

		<!-- Agent Widget / End -->
	<?php

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}

	public function get_forms()
	{
		$forms  = array(0 => __('Please select a form', 'truelysell_core'));

		$_forms = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => 'wpcf7_contact_form',
			)
		);

		if (!empty($_forms)) {

			foreach ($_forms as $_form) {
				$forms[$_form->ID] = $_form->post_title;
			}
		}

		return $forms;
	}
}




/**
 * Save & Print listings Widget
 */
class Truelysell_Core_Search_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		global $wp_post_types;

		$this->widget_cssclass    = 'truelysell_core widget_buttons';
		$this->widget_description = __('Display a Advanced Search Form.', 'truelysell_core');
		$this->widget_id          = 'widget_search_form_listings';
		$this->widget_name        =  __('Truelysell Search Form', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Find New Home', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),
			'action' => array(
				'type'  => 'dropdown',
				'std'	=> 'archive',
				'options' => array(
					'current_page' => __('Redirect to current page', 'truelysell_core'),
					'archive' => __('Redirect to listings archive page', 'truelysell_core'),
				),
				'label' => __('Choose form action', 'truelysell_core')
			),

		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{
		if ($this->get_cached_widget($args)) {
			return;
		}


		extract($args);

		echo $before_widget;
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		if (isset($instance['action'])) {
			$action  = apply_filters('truelysell_core_search_widget_action', $instance['action'], $instance, $this->id_base);
		}


		if ($title) {
			echo $before_title . $title;
			echo $after_title;
		}
		$dynamic =  (truelysell_fl_framework_getoptions('dynamic_features') == "on") ? "on" : "off";

		if (isset($action) && $action == 'archive') {
			echo do_shortcode('[truelysell_search_form dynamic_filters="' . $dynamic . '" 	more_text_open="' . esc_html__('More Filters', 'truelysell_core') . '" more_text_close="' . esc_html__('Close Filters', 'truelysell_core') . '" ajax_browsing="false" action=' . get_post_type_archive_link('listing') . ']');
		} else {
			echo do_shortcode('[truelysell_search_form  dynamic_filters="' . $dynamic . '" more_text_close="' . esc_html__('Close Filters', 'truelysell_core') . '" more_text_open="' . esc_html__('More Filters', 'truelysell_core') . '"]');
		}

		echo $after_widget;
	}
}

class Truelysell_Core_External_Booking_Widget extends Truelysell_Core_Widget
{
	public function __construct()
	{

		// create object responsible for bookings
		$this->bookings = new Truelysell_Core_Bookings_Calendar;

		$this->widget_cssclass    = 'truelysell_core boxed-widget booking-external-widget margin-bottom-35';
		$this->widget_description = __('Shows Booking Button for external site.', 'truelysell_core');
		$this->widget_id          = 'widget_external_booking_listings';
		$this->widget_name        =  __('Truelysell External Booking', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Booking', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),
			'btn' => array(
				'type'  => 'text',
				'std'   => __('Book Now', 'truelysell_core'),
				'label' => __('Button Label', 'truelysell_core')
			),


		);
		$this->register();
	}

	public function widget($args, $instance)
	{



		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$btn  = $instance['btn'];
		$queried_object = get_queried_object();
		if ($queried_object) {
			$post_id = $queried_object->ID;
		}
		$book_btn = get_post_meta($post_id, '_booking_link', true);
		if (empty($book_btn)) {
			return;
		}
		echo $before_widget;
		if ($title) {
			echo $before_title . '<i class="fa fa-calendar-check"></i> ' . $title . $after_title;
		}



	?>

		<div class="row with-forms  margin-top-0" id="booking-widget-anchor">
			<form autocomplete="off" id="form-booking">

				<a href="<?php echo $book_btn; ?>" class="button fullwidth margin-top-5"><span class="book-now-text"><?php echo $btn; ?></span></a>

			</form>
		</div>
		<?php

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}
}



/**
 * Booking Widget
 */
class Truelysell_Core_Booking_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		// create object responsible for bookings
		$this->bookings = new Truelysell_Core_Bookings_Calendar;

		$this->widget_cssclass    = 'truelysell_core boxed-widget booking-widget';
		$this->widget_description = __('Shows Booking Form.', 'truelysell_core');
		$this->widget_id          = 'widget_booking_listings';
		$this->widget_name        =  __('Truelysell Booking Form', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Booking', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),


		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{



		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();
		$packages_disabled_modules = truelysell_fl_framework_getoptions('listing_packages_options', array());
		if (empty($packages_disabled_modules)) {
			$packages_disabled_modules = array();
		}
		if ($queried_object) {
			$post_id = $queried_object->ID;



			if (empty($packages_disabled_modules)) {
				$packages_disabled_modules = array();
			}

			$user_package = get_post_meta($post_id, '_user_package_id', true);
			if ($user_package) {
				$package = truelysell_core_get_user_package($user_package);
			}

			$offer_type = get_post_meta($post_id, '_listing_type', true);
		}

		if (in_array('option_booking', $packages_disabled_modules)) {

			if (isset($package) && $package->has_listing_booking() != 1) {
				return;
			}
		}

		if ($queried_object) {
			$post_id = $queried_object->ID;
			$_booking_status = get_post_meta($post_id, '_booking_status', true); {
				if (!$_booking_status) {
					return;
				}
			}
		}
		echo $before_widget;
		

		$days_list = array(
			0	=> __('Monday', 'truelysell_core'),
			1 	=> __('Tuesday', 'truelysell_core'),
			2	=> __('Wednesday', 'truelysell_core'),
			3 	=> __('Thursday', 'truelysell_core'),
			4 	=> __('Friday', 'truelysell_core'),
			5 	=> __('Saturday', 'truelysell_core'),
			6 	=> __('Sunday', 'truelysell_core'),
		);

		// get post meta and save slots to var
		$post_info = get_queried_object();

		$post_meta = get_post_meta($post_info->ID);

		// get slots and check if not empty

		if (isset($post_meta['_slots_status'][0]) && !empty($post_meta['_slots_status'][0])) {
			if (isset($post_meta['_slots'][0])) {
				$slots = json_decode($post_meta['_slots'][0]);
				if (strpos($post_meta['_slots'][0], '-') == false) $slots = false;
			} else {
				$slots = false;
			}
		} else {
			$slots = false;
		}
		// get opening hours
		if (isset($post_meta['_opening_hours'][0])) {
			$opening_hours = json_decode($post_meta['_opening_hours'][0], true);
		}

		if ($post_meta['_listing_type'][0] == 'rental' || $post_meta['_listing_type'][0] == 'service') {

			// get reservations for next 10 years to make unable to set it in datapicker
			if ($post_meta['_listing_type'][0] == 'rental') {
				$records = $this->bookings->get_bookings(
					date('Y-m-d H:i:s'),
					date('Y-m-d H:i:s', strtotime('+3 years')),
					array('listing_id' => $post_info->ID, 'type' => 'reservation'),
					$by = 'booking_date',
					$limit = '',
					$offset = '',
					$all = '',
					$listing_type = 'rental'
				);
			} else {

				$records = $this->bookings->get_bookings(
					date('Y-m-d H:i:s'),
					date('Y-m-d H:i:s', strtotime('+3 years')),
					array('listing_id' => $post_info->ID, 'type' => 'reservation'),
					'booking_date',
					$limit = '',
					$offset = '',
					'owner'
				);
			}


			// store start and end dates to display it in the widget
			$wpk_start_dates = array();
			$wpk_end_dates = array();
			if (!empty($records)) {
				foreach ($records as $record) {

					if ($post_meta['_listing_type'][0] == 'rental') {
						// when we have one day reservation
						if ($record['date_start'] == $record['date_end']) {
							$wpk_start_dates[] = date('Y-m-d', strtotime($record['date_start']));
							$wpk_end_dates[] = date('Y-m-d', strtotime($record['date_start'] . ' + 1 day'));
						} else {
							/**
							 * Set the date_start and date_end dates and fill days in between as disabled
							 */
							$wpk_start_dates[] = date('Y-m-d', strtotime($record['date_start']));
							$wpk_end_dates[] = date('Y-m-d', strtotime($record['date_end']));

							$period = new DatePeriod(
								new DateTime(date('Y-m-d', strtotime($record['date_start'] . ' + 1 day'))),
								new DateInterval('P1D'),
								new DateTime(date('Y-m-d', strtotime($record['date_end']))) //. ' +1 day') ) )
							);

							foreach ($period as $day_number => $value) {
								$disabled_dates[] = $value->format('Y-m-d');
							}
						}
					} else {
						// when we have one day reservation
						if ($record['date_start'] == $record['date_end']) {
							$disabled_dates[] = date('Y-m-d', strtotime($record['date_start']));
						} else {

							// if we have many dats reservations we have to add every date between this days
							$period = new DatePeriod(
								new DateTime(date('Y-m-d', strtotime($record['date_start']))),
								new DateInterval('P1D'),
								new DateTime(date('Y-m-d', strtotime($record['date_end'] . ' +1 day')))
							);

							foreach ($period as $day_number => $value) {
								$disabled_dates[] = $value->format('Y-m-d');
							}
						}
					}
				}
			}

			if (isset($wpk_start_dates)) {
		?>
				<script>
					var wpkStartDates = <?php echo json_encode($wpk_start_dates); ?>;
					var wpkEndDates = <?php echo json_encode($wpk_end_dates); ?>;
				</script>
			<?php
			}
			if (isset($disabled_dates)) {
			?>
				<script>
					var disabledDates = <?php echo json_encode($disabled_dates); ?>;
				</script>
			<?php
			}
		} // end if rental/service


		if ($post_meta['_listing_type'][0] == 'event') {
			$max_tickets = (int) get_post_meta($post_info->ID, "_event_tickets", true);
			$sold_tickets = (int) get_post_meta($post_info->ID, "_event_tickets_sold", true);
			$av_tickets = $max_tickets - $sold_tickets;

			if ($av_tickets <= 0) { ?>
				<p id="sold-out"><?php esc_html_e('The tickets have sold out', 'truelysell_core') ?></p>
				</div>
		<?php
				return;
			}
		}
		?>
		<div class="with-forms" id="booking-widget-anchor">
			<form ​ autocomplete="off" id="form-booking" data-post_id="<?php echo $post_info->ID; ?>" class="form-booking-<?php echo $post_meta['_listing_type'][0]; ?>" action="<?php echo esc_url(get_permalink(truelysell_fl_framework_getoptions('booking_confirmation_page'))); ?>" method="post">

				<?php if ($post_meta['_listing_type'][0] != 'event') {
					$minspan = get_post_meta($post_info->ID, '_min_days', true);
					//WP Kraken
					// If minimub booking days are not set, set to 2 by default
					if (!$minspan && $post_meta['_listing_type'][0] == 'rental') {
						$minspan = 2;
					}
				?>
					<!-- Date Range Picker - docs: http://www.daterangepicker.com/ -->

 					<div class="col-lg-12">
					 
						<div class="form-group">

							<label><?php echo esc_html('Date','truelysell_core'); ?></label>
							<div class="form-icon">
							<input type="text" data-minspan="<?php echo ($minspan) ? $minspan : '0'; ?>" id="date-picker"   class="form-control date-picker-listing-<?php echo esc_attr($post_meta['_listing_type'][0]); ?>" autocomplete="off" placeholder="<?php esc_attr_e('Date', 'truelysell_core'); ?>" value=""  data-listing_type="<?php echo $post_meta['_listing_type'][0]; ?>" />
							<span class="cus-icon"><i class="feather-calendar"></i></span>
 						</div>
					</div>

					<!-- Panel Dropdown -->
					<?php if ($post_meta['_listing_type'][0] == 'service' &&   is_array($slots)) { ?>
						<div class="col-lg-12">
							<div class="form-group">
							<div class="panel-dropdown time-slots-dropdown">
								<a href="#" placeholder="<?php esc_html_e('Time Slots', 'truelysell_core') ?>"><?php esc_html_e('Time Slots', 'truelysell_core') ?></a>

								<div class="panel-dropdown-content padding-reset">
									<div class="no-slots-information"><?php esc_html_e('No slots for this day', 'truelysell_core') ?></div>
									<div class="panel-dropdown-scrollable">
										<input id="slot" type="hidden" name="slot" value="" />
										<input id="listing_id" type="hidden" name="listing_id" value="<?php echo $post_info->ID; ?>" />
										<?php foreach ($slots as $day => $day_slots) {
											if (empty($day_slots)) continue;
										?>

											<?php foreach ($day_slots as $number => $slot) {
												$slot = explode('|', $slot); ?>
												<!-- Time Slot -->
												<div class="time-slot" day="<?php echo $day; ?>">
													<input type="radio" name="time-slot" id="<?php echo $day . '|' . $number; ?>" value="<?php echo $day . '|' . $number; ?>">
													<label for="<?php echo $day . '|' . $number; ?>">
														<p class="day"><?php echo $days_list[$day]; ?></p>
														<strong><?php echo $slot[0]; ?></strong>
														<span><?php echo $slot[1];
																esc_html_e(' slots available', 'truelysell_core') ?></span>
													</label>
												</div>
											<?php } ?>

										<?php } ?>
									</div>
								</div>
							</div>
							</div>
						</div>
					<?php } else if ($post_meta['_listing_type'][0] == 'service') { ?>
						<div class="col-lg-12">
							<div class="form-group">
							<label><?php echo esc_html('Start Time','truelysell_core'); ?></label>
							<div class="form-icon">
								<input type="text" class="form-control time-picker sdate flatpickr-input active" placeholder="<?php esc_html_e('Start Time', 'truelysell_core') ?>" id="_hour" name="_hour" readonly="readonly" required="" data-readonly>
								<span class="cus-icon"><i class="feather-clock"></i></span>
 								<span id="alart_hour" class="invalid-feedback" style="display:none;">Please select start date</span>
							</div>
							</div>
						</div>
						<?php if (get_post_meta($post_id, '_end_hour', true)) : ?>
							<div class="col-lg-12">
								<div class="form-group">
								<label><?php echo esc_html('End Time','truelysell_core'); ?></label>
								<div class="form-icon">
									<input type="text" class="form-control time-picker edate flatpickr-input active" placeholder="<?php esc_html_e('End Time', 'truelysell_core') ?>" id="_hour_end" name="_hour_end" readonly="readonly" required="" data-readonly>
									<span class="cus-icon"><i class="feather-clock"></i></span>
 									<span id="alart_edate" class="invalid-feedback" style="display:none;">Please select end date</span>
								</div>
								</div>
							</div>
						<?php
						endif;
						$_opening_hours_status = get_post_meta($post_id, '_opening_hours_status', true);
						$_opening_hours_status = '';
						?>
						<script>
							var availableDays = <?php if ($_opening_hours_status) {
													echo json_encode($opening_hours, true);
												} else {
													echo json_encode('', true);
												} ?>;
						</script>

					<?php } ?>

					<?php $bookable_services = truelysell_get_bookable_services($post_info->ID);

					if (!empty($bookable_services)) : ?>

						<!-- Panel Dropdown -->
						<div class="col-lg-12">
							<div class="panel-dropdown booking-services">
								<a href="#"><?php esc_html_e('Extra Services', 'truelysell_core'); ?> <span class="services-counter">0</span></a>
								<div class="panel-dropdown-content padding-reset">
									<div class="panel-dropdown-scrollable">

										<!-- Bookable Services -->
										<div class="bookable-services">
											<?php
											$i = 0;
											$currency_abbr = truelysell_fl_framework_getoptions('currency');
											$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
											foreach ($bookable_services as $key => $service) {
												$i++; ?>
												<div class="single-service <?php if (isset($service['bookable_quantity'])) : ?>with-qty-btns<?php endif; ?>">

													<input type="checkbox" autocomplete="off" class="bookable-service-checkbox" name="_service[<?php echo sanitize_title($service['name']); ?>]" value="<?php echo sanitize_title($service['name']); ?>" id="tag<?php echo esc_attr($i); ?>" />

													<label for="tag<?php echo esc_attr($i); ?>">
														<h5><?php echo esc_html($service['name']); ?></h5>
														<span class="single-service-price"> <?php
																							if (empty($service['price']) || $service['price'] == 0) {
																								esc_html_e('Free', 'truelysell_core');
																							} else {
																								if ($currency_postion == 'before') {
																									echo $currency_symbol . ' ';
																								}
																								$price = $service['price'];
																								if (is_numeric($price)) {
																									$decimals = truelysell_fl_framework_getoptions('number_decimals', 2);
																									echo number_format_i18n($price, $decimals);
																								} else {
																									echo esc_html($price);
																								}
																								if ($currency_postion == 'after') {
																									echo ' ' . $currency_symbol;
																								}
																							}
																							?></span>
													</label>

													<?php if (isset($service['bookable_quantity'])) : ?>
														<div class="qtyButtons">
															<input type="text" class="bookable-service-quantity" name="_service_qty[<?php echo sanitize_title($service['name']); ?>]" value="1">
														</div>
													<?php else : ?>
														<input type="hidden" class="bookable-service-quantity" name="_service_qty[<?php echo sanitize_title($service['name']); ?>]" value="1">
													<?php endif; ?>

												</div>
											<?php } ?>
										</div>
										<div class="clearfix"></div>
										<!-- Bookable Services -->


									</div>
								</div>
							</div>
						</div>
						<!-- Panel Dropdown / End -->
					<?php
					endif;
					$max_guests = get_post_meta($post_info->ID, "_max_guests", true);
					$count_per_guest = get_post_meta($post_info->ID, "_count_per_guest", true);
					if (truelysell_fl_framework_getoptions('remove_guests')) {
						$max_guests = 1;
					}
					?>
					<!-- Panel Dropdown -->
					<!-- <div class="col-lg-12" <?php //if ($max_guests == 1) {
												//echo 'style="display:none;"';
											} ?>>
						<div class="panel-dropdown">
							<a href="#"><?php //esc_html_e('Guests', 'truelysell_core') ?> <span class="qtyTotal" name="qtyTotal">1</span></a>
							<div class="panel-dropdown-content" style="width: 269px;">-->
								<!-- Quantity Buttons -->
								<!-- <div class="qtyButtons">
									<div class="qtyTitle"><?php //esc_html_e('Guests', 'truelysell_core') ?></div>
									<input type="text" name="qtyInput" data-max="<?php //echo esc_attr($max_guests); ?>" class="adults <?php //if ($count_per_guest) echo 'count_per_guest'; ?>" value="1">
								</div> 

							</div>
						</div>
					</div> -->
					<!-- Panel Dropdown / End -->

				<?php //} //eof !if event 
				?>

				<?php if ($post_meta['_listing_type'][0] == 'event') {
					$max_guests 	= (int) get_post_meta($post_info->ID, "_max_guests", true);
					$max_tickets 	= (int) get_post_meta($post_info->ID, "_event_tickets", true);
					$sold_tickets 	= (int) get_post_meta($post_info->ID, "_event_tickets_sold", true);
					$av_tickets 	= $max_tickets - $sold_tickets;
					if ($av_tickets > $max_guests && $max_guests > 0) {
						$av_tickets = $max_guests;
					}

				?><input type="hidden" id="date-picker" readonly="readonly" class="date-picker-listing-<?php echo esc_attr($post_meta['_listing_type'][0]); ?>" autocomplete="off" placeholder="<?php esc_attr_e('Date', 'truelysell_core'); ?>" value="<?php echo $post_meta['_event_date'][0]; ?>" listing_type="<?php echo $post_meta['_listing_type'][0]; ?>" />
					<div class="col-lg-12 tickets-panel-dropdown">
						<div class="panel-dropdown">
							<a href="#"><?php esc_html_e('Tickets', 'truelysell_core') ?> <span class="qtyTotal" name="qtyTotal">1</span></a>
							<div class="panel-dropdown-content" style="width: 269px;">
								<!-- Quantity Buttons -->
								<div class="qtyButtons">
									<div class="qtyTitle"><?php esc_html_e('Tickets', 'truelysell_core') ?></div>
									<input type="text" name="qtyInput" <?php if ($max_tickets > 0) { ?>data-max="<?php echo esc_attr($av_tickets); ?>" <?php } ?> id="tickets" value="1">
								</div>

							</div>
						</div>
					</div>
					<?php $bookable_services = truelysell_get_bookable_services($post_info->ID);

					if (!empty($bookable_services)) : ?>

						<!-- Panel Dropdown -->
						<div class="col-lg-12">
							<div class="panel-dropdown booking-services">
								<a href="#"><?php esc_html_e('Extra Services', 'truelysell_core'); ?> <span class="services-counter">0</span></a>
								<div class="panel-dropdown-content padding-reset">
									<div class="panel-dropdown-scrollable">

										<!-- Bookable Services -->
										<div class="bookable-services">
											<?php
											$i = 0;
											$currency_abbr = truelysell_fl_framework_getoptions('currency');
											$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
											$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
											foreach ($bookable_services as $key => $service) {
												$i++; ?>
												<div class="single-service">
													<input type="checkbox" class="bookable-service-checkbox" name="_service[<?php echo sanitize_title($service['name']); ?>]" value="<?php echo sanitize_title($service['name']); ?>" id="tag<?php echo esc_attr($i); ?>" />

													<label for="tag<?php echo esc_attr($i); ?>">
														<h5><?php echo esc_html($service['name']); ?></h5>
														<span class="single-service-price"> <?php
																							if (empty($service['price']) || $service['price'] == 0) {
																								esc_html_e('Free', 'truelysell_core');
																							} else {
																								if ($currency_postion == 'before') {
																									echo $currency_symbol . ' ';
																								}
																								echo esc_html($service['price']);
																								if ($currency_postion == 'after') {
																									echo ' ' . $currency_symbol;
																								}
																							}
																							?></span>
													</label>

													<?php if (isset($service['bookable_quantity'])) : ?>
														<div class="qtyButtons">
															<input type="text" class="bookable-service-quantity" name="_service_qty[<?php echo sanitize_title($service['name']); ?>]" data-max="" class="" value="1">
														</div>
													<?php else : ?>
														<input type="hidden" class="bookable-service-quantity" name="_service_qty[<?php echo sanitize_title($service['name']); ?>]" data-max="" class="" value="1">
													<?php endif; ?>
												</div>
											<?php } ?>
										</div>
										<div class="clearfix"></div>
										<!-- Bookable Services -->


									</div>
								</div>
							</div>
						</div>
						<!-- Panel Dropdown / End -->
					<?php
					endif; ?>
					<!-- Panel Dropdown / End -->
				<?php } ?>

		</div>

		<!-- Book Now -->
		<input type="hidden" id="listing_type" value="<?php echo $post_meta['_listing_type'][0]; ?>" />
		<input type="hidden" id="listing_id" value="<?php echo $post_info->ID; ?>" />
		<input id="booking" type="hidden" name="value" value="booking_form" />
		<?php if (is_user_logged_in()) :

			if ($post_meta['_listing_type'][0] == 'event') {
				$book_btn = esc_html__('Make a Reservation', 'truelysell_core');
			} else {
				if (get_post_meta($post_info->ID, '_instant_booking', true)) {
					$book_btn = esc_html__('Book Now', 'truelysell_core');
				} else {
					$book_btn = esc_html__('Request Booking', 'truelysell_core');
				}
			}

			$post_id = $queried_object->ID;
			$author_id = get_post_field('post_author', $post_id);
			$current_user = wp_get_current_user();
			$user_id = get_current_user_id();
			$roles = $current_user->roles;
			$role = array_shift($roles);
			if (truelysell_fl_framework_getoptions('owners_can_book') != 'on' && 'owner' == $role) { ?>
				<a href="#" class="button btn btn-primary w-100"><span class="book-now-text"><?php echo esc_html__("Please use customer account", 'truelysell_core');  ?></span></a>
			<?php } else {  ?>
				<a href="#" class="button book-now btn btn-primary w-100">
					<div class="loadingspinner"></div><span class="book-now-text"><?php echo $book_btn; ?></span>
				</a>

			<?php } ?>




			<?php else :
			$popup_login = truelysell_fl_framework_getoptions('popup_login', 'ajax');
			if ($popup_login == 'ajax') { ?>

				<a href="#sign-in-dialog" class="button fullwidth margin-top-5 popup-with-zoom-anim book-now-notloggedin">
					<div class="loadingspinner"></div><span class="book-now-text btn btn-primary w-100"><?php esc_html_e('Login to Book', 'truelysell_core') ?></span>
				</a>

			<?php } else {

				$login_page = truelysell_fl_framework_getoptions('profile_page'); ?>
				<a href="<?php echo esc_url(get_permalink($login_page)); ?>" class="button fullwidth margin-top-5 book-now-notloggedin btn btn-primary w-100">
					<div class="loadingspinner"></div><span class="book-now-text"><?php esc_html_e('Login To Book', 'truelysell_core') ?></span>
				</a>
			<?php } ?>

		<?php endif; ?>

		<?php if ($post_meta['_listing_type'][0] == 'event' && isset($post_meta['_event_date'][0])) { ?>
			<div class="booking-event-date">
				<strong><?php esc_html_e('Event date', 'truelysell_core'); ?></strong>
				<span><?php

						$_event_datetime = $post_meta['_event_date'][0];
						$_event_date = list($_event_datetime) = explode(' -', $_event_datetime);

						echo $_event_date[0]; ?></span>
			</div>
		<?php } ?>

		<?php
		$currency_abbr = truelysell_fl_framework_getoptions('currency');
		$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
		$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);
		?>
		<div class="booking-estimated-cost mt-4 button_spacing" <?php if ($post_meta['_listing_type'][0] != 'event') { ?>style="display: none;" <?php } ?>>
			<?php if ($post_meta['_listing_type'][0] == 'event') {
				$reservation_fee = (float) get_post_meta($post_info->ID, '_reservation_price', true);
				$normal_price = (float) get_post_meta($post_info->ID, '_normal_price', true);

				$event_default_price = $reservation_fee + $normal_price;
			}  ?>
			<strong><?php esc_html_e('Total Cost', 'truelysell_core'); ?></strong>
			<span data-price="<?php if (isset($event_default_price)) {
									echo esc_attr($event_default_price);
								} ?>">
				<?php if ($currency_postion == 'before') {
					echo $currency_symbol;
				} ?>
				<?php
				if ($post_meta['_listing_type'][0] == 'event') {

					echo $event_default_price;
				} else echo '0'; ?>
				<?php if ($currency_postion == 'after') {
					echo $currency_symbol;
				} ?>
			</span>
		</div>

		<div class="booking-estimated-discount-cost" style="display: none;">

			<strong><?php esc_html_e('Final Cost', 'truelysell_core'); ?></strong>
			<span>
				<?php if ($currency_postion == 'before') {
					echo $currency_symbol;
				} ?>

				<?php if ($currency_postion == 'after') {
					echo $currency_symbol;
				} ?>
			</span>
		</div>
		<div class="booking-error-message mt-4 alert alert-danger mb-0 button_spacing" style="display: none;">
			<?php if ($post_meta['_listing_type'][0] == 'service' && !$slots) {
				esc_html_e('Unfortunately we are closed at selected hours. Try different please.', 'truelysell_core');
			} else {
				esc_html_e('Unfortunately this request can\'t be processed. Try different dates please.', 'truelysell_core');
			} ?>
		</div>
		</form>
		<?php


		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}
}

/**
 * Booking Widget
 */
class Truelysell_Core_Opening_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->widget_cssclass    = 'truelysell_core boxed-widget opening-hours card available-widget';
		$this->widget_description = __('Shows Opening Hours.', 'truelysell_core');
		$this->widget_id          = 'widget_opening_hours';
		$this->widget_name        =  __('Truelysell Opening Hours', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Opening Hours', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),


		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{


		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();
		$packages_disabled_modules = truelysell_fl_framework_getoptions('listing_packages_options', array());

		if ($queried_object) {
			$post_id = $queried_object->ID;


			if (empty($packages_disabled_modules)) {
				$packages_disabled_modules = array();
			}

			$user_package = get_post_meta($post_id, '_user_package_id', true);
			if ($user_package) {
				$package = truelysell_core_get_user_package($user_package);
			}
			$listing_type = get_post_meta($post_id, '_listing_type', true);
		}

		if (!$listing_type  == 'service') {
			return;
		}

		if (in_array('option_opening_hours', $packages_disabled_modules)) {

			if (isset($package) && $package->has_listing_opening_hours() != 1) {
				return;
			}
		}
		 
		$has_hours = false;
		//check if has any horus saved
		$days = truelysell_get_days();
		foreach ($days as $d_key => $value) {
			$opening_day = get_post_meta($post_id, '_' . $d_key . '_opening_hour', true);
			$closing_day = get_post_meta($post_id, '_' . $d_key . '_closing_hour', true);

			if ((!empty($opening_day) && $opening_day != "Closed")  || (!empty($closing_day) && $closing_day != "Closed")) {
				$has_hours = true;
			}
		}
		if (!$has_hours) {
			return;
		}
		echo $before_widget;
		?>
		<div class="card-available">
		<div class="card-body">
		<div class="available-widget">
        <div class="available-info">
		<?php 
		if ($title) {
			echo $before_title . $title . $after_title;
		}
		?>
		<ul class="">
			<?php
			$clock_format = truelysell_fl_framework_getoptions('clock_format');

			foreach ($days as $d_key => $value) {
				$opening_day = get_post_meta($post_id, '_' . $d_key . '_opening_hour', true);
				$closing_day = get_post_meta($post_id, '_' . $d_key . '_closing_hour', true);

			?>

				<?php

				if (is_array($opening_day)) {
					if (!empty($opening_day[0])) :

						echo '<li>';
						echo '<span>';
						echo esc_html($value);
						echo '</span>';

						foreach ($opening_day as $key => $opening) {
							if (!empty($opening)) {


								$closing = $closing_day[$key];

								if ($clock_format == 12) {
									if (substr($opening, -1) != 'M' && $opening != 'Closed') {
										$opening = DateTime::createFromFormat('H:i', $opening);
										if ($opening) {
											$opening = $opening->format('h:i A');
										}
									}

									if (substr($closing, -1) != 'M' && $closing != 'Closed') {

										$closing = DateTime::createFromFormat('H:i', $closing);
										if ($closing) {
											$closing = $closing->format('h:i A');
										}
										if ($closing == '00:00') {
											$closing = '24:00';
										}
									}
								}

				?>

								<?php echo esc_html($opening); ?>
								-
								<?php
								if ($clock_format == 12 && $closing == '12:00 AM') {
									echo  '12:00 AM';
								} else if ($clock_format != 12 && $closing == '00:00') {
									echo  '24:00';
								} else {
									echo esc_html($closing);
								}
								echo '<br>';
								?>
						<?php }
						}
						echo '</li>';
					else : ?>
						<li><span><?php echo $value; ?></span><?php esc_html_e('Closed', 'truelysell_core') ?>
						<?php endif;
				} else {

					//not array, old listings
					if (!empty($opening_day) && !empty($closing_day)) {
						echo '<li>';
						echo esc_html($value);
						if ($clock_format == 12) {
							if (substr($opening_day, -1) != 'M' && $opening_day != 'Closed') {
								$opening_day = DateTime::createFromFormat('H:i', $opening_day)->format('h:i A');
							}

							if (substr($closing_day, -1) != 'M' && $closing_day != 'Closed') {

								$closing_day = DateTime::createFromFormat('H:i', $closing_day)->format('h:i A');

								if ($closing_day == '00:00') {
									$closing_day = '24:00';
								}
							}
						} ?>
							<span>
								<?php echo esc_html($opening_day); ?>
								-
								<?php
								if ($clock_format == 12 && $closing_day == '12:00 AM') {
									echo  '12:00 PM';
								} else if ($clock_format != 12 && $closing_day == '00:00') {
									echo  '24:00';
								} else {
									echo esc_html($closing_day);
								}

								?> </span>
						<?php } else { ?>
						<li><?php echo $value; ?><span><?php esc_html_e('Closed', 'truelysell_core') ?></span>
						<?php } ?>

						</li>
					<?php }
					?>


				<?php } //end foreach 
				?>
		</ul>
		</div>
		</div>
						</div>
						</div>
	<?php


		echo $after_widget;

		$content = ob_get_clean();

		echo $content;
	}
}


class Truelysell_Core_Classified_Owner_Widget extends Truelysell_Core_Widget
{

	public function __construct()
	{

		$this->widget_cssclass    = 'truelysell_core widget_listing_classified_owner boxed-widget margin-bottom-35';
		$this->widget_description = __('Shows Listing Owner info on Classified ad.', 'truelysell_core');
		$this->widget_id          = 'widget_classified_listing_owner';
		$this->widget_name        =  __('Truelysell Classified Owner Widget', 'truelysell_core');
		$this->settings           = array(

			'phone' => array(
				'type'  => 'checkbox',
				'std'   => 'on',
				'label' => __('Phone number', 'truelysell_core')
			),
			'loggedin' => array(
				'type'  => 'checkbox',
				'std'   => 'on',
				'label' => __('Show Phone to logged in users only', 'truelysell_core')
			),

			'contact' => array(
				'type'  => 'checkbox',
				'std'   => 'on',
				'label' => __('Show Send message button', 'truelysell_core')
			),


		);
		$this->register();
	}




	public function widget($args, $instance)
	{
		
		

		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();
		if (!$queried_object) {
			return;
		}
		$owner_id = $queried_object->post_author;

		if (!$owner_id) {
			return;
		}
		$owner_data = get_userdata($owner_id);
		if ($queried_object) {
			$post_id = $queried_object->ID;
			$listing_type = get_post_meta($post_id, '_listing_type', true);
		}

		if ($listing_type != 'classifieds') {
			return;
		}
		echo $before_widget;


		$show_phone = (isset($instance['phone']) && !empty($instance['phone'])) ? true : false;
		$show_loggedin = (isset($instance['loggedin']) && !empty($instance['loggedin'])) ? true : false;

		$visibility_setting = truelysell_fl_framework_getoptions('user_contact_details_visibility'); // hide_all, show_all, show_logged, 
		if ($visibility_setting == 'hide_all') {
			$show_phone = false;
		} elseif ($visibility_setting == 'show_all') {
			$show_phone = true;
		} else {
			if (is_user_logged_in()) {
				if ($visibility_setting == 'show_logged') {
					$show_phone = true;
				} else {
					$show_phone = false;
				}
			} else {
				$show_phone = false;
			}
		}

		if ($show_loggedin) {
			if (is_user_logged_in()) {
				$show_phone = true;
			} else {
				$show_phone = false;
			}
		}

		$registered_date = get_the_author_meta('user_registered', $owner_id);
	?>



		<div class="classifieds-widget">
			<div class="classifieds-user">
				<div class="classifieds-user-avatar"><a href="<?php echo esc_url(get_author_posts_url($owner_id)); ?>"><?php echo get_avatar($owner_id, 56);  ?></a></div>
				<div class="classifieds-user-details">
					<h3><?php echo truelysell_get_users_name($owner_id); ?></h3>
					<span><?php esc_html_e('User since ', 'truelysell_core');
							echo date_i18n(get_option('date_format'), strtotime($registered_date)); ?> </span>
					<a href="<?php echo esc_url(get_author_posts_url($owner_id)); ?>"><?php esc_html_e('More ads from this user ', 'truelysell_core'); ?> <i class="fa fa-chevron-right"></i></a>
				</div>
			</div>


			<div class="classifieds-widget-buttons">

				<?php


				if ($show_phone) {

					if (isset($owner_data->phone) && !empty($owner_data->phone)) : ?>
						<a class="call-btn" href="tel:<?php echo esc_attr($owner_data->phone); ?>"><?php esc_html_e('Call', 'truelysell_core'); ?></a>
					<?php endif;
				} else { ?>
					<a class="call-btn sign-in popup-with-zoom-anim" href="#sign-in-dialog"><?php esc_html_e('Login to Call', 'truelysell_core'); ?></a>
					<?php }
				if (is_user_logged_in()) {
					if ((isset($instance['contact']) && !empty($instance['contact']))) : ?>
						<!-- Reply to review popup -->
						<div id="small-dialog" class="zoom-anim-dialog mfp-hide">
							<div class="small-dialog-header">
								<h3><?php esc_html_e('Send Message', 'truelysell_core'); ?></h3>
							</div>
							<div class="message-reply margin-top-0">
								<form action="" id="send-message-from-widget" data-listingid="<?php echo esc_attr($post_id); ?>">
									<textarea required data-recipient="<?php echo esc_attr($owner_id); ?>" class="form-control" data-referral="listing_<?php echo esc_attr($post_id); ?>" cols="40" id="contact-message" name="message" rows="3" placeholder="<?php esc_attr_e('Your message to ', 'truelysell_core');
																																																											echo $owner_data->first_name; ?>"></textarea>
									<button class="btn btn-primary btn-block btn-lg  msg-button">
										<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><?php esc_html_e('Send Message', 'truelysell_core'); ?></button>
									<div class="notification closeable success margin-top-20"></div>

								</form>

							</div>
						</div>


						<a href="#small-dialog" class="send-message-to-owner button  popup-with-zoom-anim"><?php esc_html_e('Send Message', 'truelysell_core'); ?></a>
					<?php endif;
				} else { ?>
					<a href="#sign-in-dialog" class="sign-in button  popup-with-zoom-anim"><?php esc_html_e('Send Message', 'truelysell_core'); ?></a>
				<?php }; ?>


			</div>



		</div>
		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}

}




/**
 * Booking Widget
 */
class Truelysell_Core_Owner_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->widget_cssclass    = 'truelysell_core widget_listing_owner boxed-widget margin-bottom-35';
		$this->widget_description = __('Shows Listing Owner box.', 'truelysell_core');
		$this->widget_id          = 'widget_listing_owner';
		$this->widget_name        =  __('Truelysell Message Widget', 'truelysell_core');
		$this->settings           = array(
			 
			'contact' => array(
				'type'  => 'checkbox',
				'std'   => 'on',
				'label' => __('Show Send message button', 'truelysell_core')
			),


		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{
		

		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();
		if (!$queried_object) {
			return;
		}
		$owner_id = $queried_object->post_author;

		if (!$owner_id) {
			return;
		}
		$owner_data = get_userdata($owner_id);
		if ($queried_object) {
			$post_id = $queried_object->ID;
			$listing_type = get_post_meta($post_id, '_listing_type', true);
		}

		if ($listing_type == 'classifieds') {
			return;
		}

		echo $before_widget;

		 ?>
	 

		<?php  
		

		
		if ($show_details && $show_social) { ?>
			<ul class="listing-details-sidebar social-profiles">
				<?php if (isset($owner_data->twitter) && !empty($owner_data->twitter)) : ?><li><a href="<?php echo esc_url($owner_data->twitter) ?>" class="twitter-profile"><i class="fa fa-twitter"></i> Twitter</a></li><?php endif; ?>
				<?php if (isset($owner_data->facebook) && !empty($owner_data->facebook)) : ?><li><a href="<?php echo esc_url($owner_data->facebook) ?>" class="facebook-profile"><i class="fa fa-facebook-square"></i> Facebook</a></li><?php endif; ?>
				<?php if (isset($owner_data->instagram) && !empty($owner_data->instagram)) : ?><li><a href="<?php echo esc_url($owner_data->instagram) ?>" class="instagram-profile"><i class="fa fa-instagram"></i> Instagram</a></li><?php endif; ?>
				<?php if (isset($owner_data->linkedin) && !empty($owner_data->linkedin)) : ?><li><a href="<?php echo esc_url($owner_data->linkedin) ?>" class="linkedin-profile"><i class="fa fa-linkedin"></i> LinkedIn</a></li><?php endif; ?>
				<?php if (isset($owner_data->youtube) && !empty($owner_data->youtube)) : ?><li><a href="<?php echo esc_url($owner_data->youtube) ?>" class="youtube-profile"><i class="fa fa-youtube"></i> YouTube</a></li><?php endif; ?>
				<?php if (isset($owner_data->whatsapp) && !empty($owner_data->whatsapp)) : ?><li><a href="<?php if (strpos($owner_data->whatsapp, 'http') === 0) {
																												echo esc_url($owner_data->whatsapp);
																											} else {
																												echo "https://wa.me/" . esc_attr($owner_data->whatsapp);
																											} ?>" class="whatsapp-profile"><i class="fa fa-whatsapp"></i> WhatsApp</a></li><?php endif; ?>
				<?php if (isset($owner_data->skype) && !empty($owner_data->skype)) : ?><li>
						<a href="<?php if (strpos($owner_data->skype, 'http') === 0) {
										echo esc_url($owner_data->skype);
									} else {
										echo "skype:+" . $owner_data->skype . "?call";
									} ?>" class="skype-profile"><i class="fa fa-skype"></i> Skype</a>
					</li><?php endif; ?>
			</ul>
		<?php } ?>
		<?php
		if (is_user_logged_in()) :
			if ((isset($instance['contact']) && !empty($instance['contact']))) : ?>
				<!-- Reply to review popup -->

				<a type="button" class="btn btn-primary send-message-to-owner button popup-with-zoom-anim" data-bs-toggle="collapse" href="#small-dialog" data-bss-target="#small-dialog" data-bs-toggle="collapse"><i class="fa fa-comments"></i>  
						<?php esc_html_e('Send Message', 'truelysell_core'); ?>
			</a>

 
<div class="card-available">
	<div class="card-body">
				<div class="collapse" id="small-dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><?php esc_html_e('Send Message', 'truelysell_core'); ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  <div id="small-dialog" class="zoom-anim-dialog mfp-hide ">
					 
					 <div class="message-reply margin-top-0">
						 <form action="" id="send-message-from-widget" data-listingid="<?php echo esc_attr($post_id); ?>">
							 <textarea required data-recipient="<?php echo esc_attr($owner_id); ?>" class="form-control" data-referral="listing_<?php echo esc_attr($post_id); ?>" cols="40" id="contact-message" name="message" rows="3" placeholder="<?php esc_attr_e('Your message to ', 'truelysell_core');
																																																									 echo $owner_data->first_name; ?>"></textarea>
							 <button class="btn btn-primary msg-button mt-2">
								 <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i><?php esc_html_e('Send Message', 'truelysell_core'); ?></button>
							 <div class="notification closeable success margin-top-20"></div>
 
						 </form>
 
					 </div>
				 </div>
      </div>
      
    </div>
  </div>
</div>
</div>
</div>

				

				 
					 
 					 
 

				 
				 
			<?php endif; ?>
		<?php endif; ?>

		<?php


		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget($args, $content);
	}
}


/**
 * Core class used to implement a Recent Posts widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Truelysell_Recent_Posts extends WP_Widget
{

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct()
	{
		$widget_ops = array(
			'classname' => 'truelysell_recent_entries',
			'description' => __('Your site&#8217;s most recent Posts.', 'truelysell'),
			'customize_selective_refresh' => true,
		);
		parent::__construct('truelysell-recent-posts', __('Truelysell Recent Posts', 'truelysell'), $widget_ops);
		$this->alt_option_name = 'truelysell_recent_entries';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget($args, $instance)
	{
		if (!isset($args['widget_id'])) {
			$args['widget_id'] = $this->id;
		}

		$title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Posts', 'truelysell');

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		$number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
		if (!$number)
			$number = 5;
		$show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

		/**
		 * Filters the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query(apply_filters('widget_posts_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		)));

		if ($r->have_posts()) :
		?>
			<?php echo $args['before_widget']; ?>
			<?php if ($title) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
			<ul class="latest-posts">
				<?php while ($r->have_posts()) : $r->the_post(); ?>
					<li>
						 
							<?php if (has_post_thumbnail()) { ?>
								<div class="post-thumb">
									<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('truelysell-post-thumb'); ?></a>
								</div>
							<?php } ?>

							<div class="post-info">
							<p class="post-date"><?php echo get_the_date(); ?></p>
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								
							</div>
							<div class="clearfix"></div>
						 
					</li>
				<?php endwhile; ?>
			</ul>
			<?php echo $args['after_widget']; ?>
		<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form($instance)
	{
		$title     = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number    = isset($instance['number']) ? absint($instance['number']) : 5;
		$show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'truelysell'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'truelysell'); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
		</p>

		<p><input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />
			<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?', 'truelysell'); ?></label>
		</p>
	<?php
	}
}



/**
 * Booking Widget
 */
class Truelysell_Coupon_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->widget_cssclass    = 'truelysell_core boxed-widget coupon-widget margin-bottom-35';
		$this->widget_description = __('Shows Listing Coupon.', 'truelysell_core');
		$this->widget_id          = 'widget_coupon';
		$this->widget_name        =  __('Truelysell Coupon Widget ', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('Coupon', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),


		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{


		ob_start();

		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();

		$packages_disabled_modules = truelysell_fl_framework_getoptions('listing_packages_options', array());

		if ($queried_object) {
			$post_id = $queried_object->ID;
			$listing_type = get_post_meta($post_id, '_listing_type', true);

			if (empty($packages_disabled_modules)) {
				$packages_disabled_modules = array();
			}

			$user_package = get_post_meta($post_id, '_user_package_id', true);
			if ($user_package) {
				$package = truelysell_core_get_user_package($user_package);
			}
		}


		if (in_array('option_coupons', $packages_disabled_modules)) {

			if (isset($package) && $package->has_listing_coupons() != 1) {
				return;
			}
		}
		$_opening_hours_status = get_post_meta($post_id, '_coupon_section_status', true);
		if (!$_opening_hours_status) {
			return;
		}
		//get coupon

		$coupon_id =  get_post_meta($post_id, '_coupon_for_widget', true);
		if (!($coupon_id)) {
			return false;
		}

		$coupon_post = get_post($coupon_id);

		if (!$coupon_post) {
			return;
		}

		if ($coupon_post) {
			$coupon_data = new WC_Coupon($coupon_id);
		}




		$coupon_bg = get_post_meta($coupon_id, 'coupon_bg-uploader-id', true);
		$coupon_bg_url = wp_get_attachment_url($coupon_bg);

	?>
		<!-- Coupon Widget -->
		<div class="coupon-widget" style="<?php if ($coupon_bg) : ?>background-image: url(<?php echo esc_url($coupon_bg_url); ?>); <?php endif; ?> margin:20px 0px;">
			<a class="coupon-top">

				<?php $coupon_amount = wc_format_localized_price($coupon_data->get_amount());
				$currency_abbr = truelysell_fl_framework_getoptions('currency');
				$currency_postion = truelysell_fl_framework_getoptions('currency_postion');
				$currency_symbol = Truelysell_Core_Listing::get_currency_symbol($currency_abbr);

				if ($coupon_data->get_discount_type() == 'fixed_product') { ?>
					<h3><?php echo sprintf(esc_html__('Get %1$s%2$s discount!', 'truelysell_core'), $coupon_amount, $currency_symbol); ?></h3>
				<?php } else { ?>
					<h3><?php echo sprintf(esc_html__('Get %1$s%% discount!', 'truelysell_core'), $coupon_amount); ?></h3>
				<?php } ?>


				<?php
				$expiry_date = $coupon_data->get_date_expires();
				if ($expiry_date) : ?>
					<div class="coupon-valid-untill"><?php esc_html_e('Expires', 'truelysell_core'); ?> <?php echo esc_html($expiry_date->date_i18n('F j, Y'));  ?></div>
				<?php endif; ?>
				<?php if ($coupon_data->get_description()) : ?>
					<div class="coupon-how-to-use"><?php echo $coupon_data->get_description(); ?></div>
				<?php endif; ?>
			</a>
			<div class="coupon-bottom">
				<div class="coupon-scissors-icon"></div>
				<div class="coupon-code"><?php echo $coupon_data->get_code(); ?></div>
			</div>
		</div>



	<?php




		$content = ob_get_clean();

		echo $content;
	}
}


/**
 * Booking Widget
 */
class Truelysell_Shop_Vendor_Widget extends Truelysell_Core_Widget
{

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->widget_cssclass    = 'truelysell_core boxed-widget shop-vendor-widget margin-bottom-35';
		$this->widget_description = __('Shows Vendor card on listing.', 'truelysell_core');
		$this->widget_id          = 'widget_truelysell_dokan_vendor';
		$this->widget_name        =  __('Truelysell Dokan Vendor Widget ', 'truelysell_core');
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __('My shop', 'truelysell_core'),
				'label' => __('Title', 'truelysell_core')
			),


		);
		$this->register();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget($args, $instance)
	{

		if (!class_exists('WeDevs_Dokan')) {
			return;
		}
		
		ob_start();
	
		extract($args);
		$title  = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$queried_object = get_queried_object();


		if ($queried_object) {
			$post_id = $queried_object->ID;
		}
		
		$vendor_id = get_post_field('post_author', $post_id);
		$is_vendor = get_user_meta($vendor_id, 'dokan_enable_selling', true);
		
		if(!$is_vendor || $is_vendor == 'no'){
			return;
		}

		// Get the WP_User object (the vendor) from author ID
		$_store_widget_status = get_post_meta($post_id, '_store_widget_status', true);
		
		if (!$_store_widget_status) {
			return;
		}

		$vendor            = dokan()->vendor->get($vendor_id);
		$store_banner_id   = $vendor->get_banner_id();
		$store_name        = $vendor->get_shop_name();
		$store_url         = $vendor->get_shop_url();
		$store_rating      = $vendor->get_rating();
		$is_store_featured = $vendor->is_featured();
		$store_phone       = $vendor->get_phone();
		$store_info        = dokan_get_store_info($vendor_id);
		$store_address     = dokan_get_seller_short_address($vendor_id);
		$store_banner_url  = $store_banner_id ? wp_get_attachment_image_src($store_banner_id, 'full') : DOKAN_PLUGIN_ASSEST . '/images/default-store-banner.png';

		$show_store_open_close    = dokan_get_option('store_open_close', 'dokan_appearance', 'on');
		$dokan_store_time_enabled = isset($store_info['dokan_store_time_enabled']) ? $store_info['dokan_store_time_enabled'] : '';
		$store_open_is_on = ('on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled && !$is_store_featured) ? 'store_open_is_on' : '';

		// Display the seller name linked to the store
		if ($title) {
			echo $args['before_title'] . $title . $args['after_title'];
		} 

		

	?>
		
		<div id="dokan-seller-listing-wrap" class="grid-view truelysell-dokan-widget">
			<div class="seller-listing-content">
				<ul class="dokan-seller-wrap 1">
					<li class="dokan-single-seller woocommerce coloum-1 <?php echo (!$store_banner_id) ? 'no-banner-img' : ''; ?>">
						<a href="<?php echo esc_url($store_url); ?>">
							<div class="store-wrapper">
								<div class="store-header">
									<div class="store-banner">

										<img src="<?php echo is_array($store_banner_url) ? esc_attr($store_banner_url[0]) : esc_attr($store_banner_url); ?>">

									</div>
								</div>

								<div class="store-content <?php echo !$store_banner_id ? esc_attr('default-store-banner') : '' ?>">
									<div class="store-data-container">
										<div class="featured-favourite">
											<?php if ($is_store_featured) : ?>
												<div class="featured-label"><?php esc_html_e('Featured', 'dokan-lite'); ?></div>
											<?php endif ?>

											<?php do_action('dokan_seller_listing_after_featured', $vendor, $store_info); ?>
										</div>

										<?php if ('on' === $show_store_open_close && 'yes' === $dokan_store_time_enabled) : ?>
											<?php if (dokan_is_store_open($vendor_id)) { ?>
												<span class="dokan-store-is-open-close-status dokan-store-is-open-status" title="<?php esc_attr_e('Store is Open', 'dokan-lite'); ?>"><?php esc_html_e('Open', 'dokan-lite'); ?></span>
											<?php } else { ?>
												<span class="dokan-store-is-open-close-status dokan-store-is-closed-status" title="<?php esc_attr_e('Store is Closed', 'dokan-lite'); ?>"><?php esc_html_e('Closed', 'dokan-lite'); ?></span>
											<?php } ?>
										<?php endif ?>

										<div class="store-data <?php echo esc_attr($store_open_is_on); ?>">
											<h2><?php echo esc_html($store_name); ?></h2>

											<?php if (!empty($store_rating['count'])) : ?>
												<?php $rating = dokan_get_readable_seller_rating($vendor_id); ?>
												<div class="dokan-store-rating <?php if (!strpos($rating, 'seller-rating') == '<') {
																					echo "no-reviews-rating";
																				} ?>">
													<i class="fa fa-star"></i>
													<?php echo wp_kses_post($rating); ?>
												</div>
											<?php endif ?>

											<?php if (!dokan_is_vendor_info_hidden('address') && $store_address) : ?>
												<?php
												$allowed_tags = array(
													'span' => array(
														'class' => array(),
													),
													'br' => array()
												);
												?>
												<p class="store-address"><?php echo wp_kses($store_address, $allowed_tags); ?></p>
											<?php endif ?>

											<?php if (!dokan_is_vendor_info_hidden('phone') && $store_phone) { ?>
												<p class="store-phone">
													<i class="fa fa-phone" aria-hidden="true"></i> <?php echo esc_html($store_phone); ?>
												</p>
											<?php } ?>

											<?php do_action('dokan_seller_listing_after_store_data', $vendor, $store_info); ?>
										</div>
									</div>
								</div>

								<div class="store-footer">

									<?php $rating = dokan_get_readable_seller_rating($vendor_id); ?>
									<div class="dokan-store-rating <?php if (!strpos($rating, 'seller-rating') == '<') {
																		echo "no-reviews-rating";
																	} ?>">
										<i class="fa fa-star"></i>
										<?php echo wp_kses_post($rating); ?>
									</div>

									<div class="seller-avatar">

										<img src="<?php echo esc_url($vendor->get_avatar()) ?>" alt="<?php echo esc_attr($vendor->get_shop_name()) ?>" size="150">

									</div>

									<span class="dashicons dashicons-arrow-right-alt2 dokan-btn-theme dokan-btn-round"></span>

									<?php do_action('dokan_seller_listing_footer_content', $vendor, $store_info); ?>
								</div>
							</div>
						</a>
					</li>

				</ul>
			</div>
		</div>
		<!-- Coupon Widget -->




<?php




		$content = ob_get_clean();

		echo $content;
	}
}

register_widget('Truelysell_Core_Featured_Properties');
register_widget('Truelysell_Core_Bookmarks_Share_Widget');
register_widget('Truelysell_Core_Booking_Widget');
register_widget('Truelysell_Core_External_Booking_Widget');
register_widget('Truelysell_Core_Search_Widget');
register_widget('Truelysell_Core_Opening_Widget');
register_widget('Truelysell_Core_Owner_Widget');
register_widget('Truelysell_Core_Classified_Owner_Widget');
register_widget('Truelysell_Core_Contact_Vendor_Widget');
register_widget('Truelysell_Recent_Posts');
register_widget('Truelysell_Coupon_Widget');
//register_widget('Truelysell_Shop_Vendor_Widget');


function custom_get_post_author_email($atts)
{
	$value = '';
	global $post;
	$post_id = $post->ID;
	$email = get_post_meta($post_id, '_email', true);
	if (!$email) {
		$object = get_post($post_id);
		//just get the email of the listing author
		$owner_ID = $object->post_author;
		//retrieve the owner user data to get the email
		$owner_info = get_userdata($owner_ID);
		if (false !== $owner_info) {
			$email = $owner_info->user_email;
		}
	}
	return $email;
}
add_shortcode('CUSTOM_POST_AUTHOR_EMAIL', 'custom_get_post_author_email');
add_shortcode('LISTING_OWNER_EMAIL', 'custom_get_post_author_email');

//_email
function custom_get_post_listing_title($atts)
{
	$value = '';
	global $post;
	$post_id = $post->ID;
	if ($post_id) {
		$value = get_the_title($post_id);
	}
	return $value;
}
add_shortcode('LISTING_TITLE', 'custom_get_post_listing_title');

//_email
function custom_get_post_listing_url($atts)
{
	$value = '';
	global $post;
	$post_id = $post->ID;
	if ($post_id) {
		$value = get_permalink($post_id);
	}
	return $value;
}
add_shortcode('LISTING_URL', 'custom_get_post_listing_url');
