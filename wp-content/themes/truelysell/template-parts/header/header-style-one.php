<header id="masthead" class="header header-one">
    <div class="container">
        <nav class="navbar navbar-expand-lg header-nav">
            <div class="navbar-header">
                <a id="mobile_btn" href="javascript:void(0);">
                    <span class="bar-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </a>

                <div class="navbar-brand logo">
                    <?php
                    $header_logo = truelysell_fl_framework_getoptions('logo_image');
                    if (isset($header_logo) && $header_logo != '') {
                       // $header_logo_url = $header_logo['url'];
                        $header_logo_url = 'https://truelysell-wordpress.dreamstechnologies.com/multipurpose/wp-content/uploads/2024/01/logo-02.svg';
                    } else {
                        $header_logo_url = get_theme_file_uri() . '/assets/images/logo.svg';
                    }
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($header_logo_url); ?>"></a>
                </div>

                <div class="navbar-brand logo-small">
                    <?php
                    $headerm_logo = truelysell_fl_framework_getoptions('logo_image_mobile');
                    if (isset($headerm_logo) && $headerm_logo != '') {
                        $headerm_logo_url = $headerm_logo['url'];
                    } else {
                        $headerm_logo_url = get_theme_file_uri() . '/assets/images/logo.svg';
                    }
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($headerm_logo_url); ?>"></a>
                </div>
            </div>

            <div class="main-menu-wrapper">
                <div class="menu-header">
                    <div class="menu-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($headerm_logo_url); ?>"></a>
                    </div>
                    <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
                </div>

                <?php
                if (has_nav_menu('header_menu')) {
                    wp_nav_menu(array(
                        'container'      => false,
                        'theme_location' => 'header_menu',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'main-nav'
                    ));
                } else {
                    wp_nav_menu(array(
                        'menu_id'    => 'primary-menu1',
                        'menu_class' => 'main-nav1',
                        'items_wrap' => '<ul class="main-nav">%3$s</ul>'
                    ));
                }
                ?>

                <?php if (is_user_logged_in()) { ?>
                <?php } else { ?>
                    <?php
                    $my_account_display = get_option('truelysell_my_account_display', true);
                    $submit_display = get_option('truelysell_submit_display', true);

                    if ($my_account_display != false || $submit_display != false) : ?>
                        <?php
                        if (class_exists('Truelysell_Core_Template_Loader')) :
                            $template_loader = new Truelysell_Core_Template_Loader;
                            $template_loader->get_template_part('account/logged_section_mobile');
                        endif;
                        ?>
                    <?php endif; ?>
                <?php } ?>
            </div>

            <?php
            $my_account_display = get_option('truelysell_my_account_display', true);
            $submit_display = get_option('truelysell_submit_display', true);
            if ($my_account_display != false || $submit_display != false) : ?>
                <ul class="nav header-navbar-rht">
                    <?php if (is_user_logged_in()) { ?>
                        <?php
                        $current_user = wp_get_current_user();
                        $roles = $current_user->roles;
                        $role = array_shift($roles);
                        if (in_array($role, array('owner', 'guest'))) : ?>
                            <li class="nav-item dropdown logged-item noti-nav">
                                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                                    <img src="<?php echo get_theme_file_uri(); ?>/assets/images/bell-icon.svg" alt="">
                                </a>
                                <div class="dropdown-menu notify-blk notifications">
                                    <?php echo do_shortcode('[truelysell_notification]'); ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php } ?>
                    <?php
                    if (class_exists('Truelysell_Core_Template_Loader')) :
                        $template_loader = new Truelysell_Core_Template_Loader;
                        $template_loader->get_template_part('account/logged_section');
                    endif;
                    ?>
                </ul>
            <?php endif; ?>

        </nav>
    </div>
</header><!-- #masthead -->
