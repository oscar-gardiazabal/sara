<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

			<nav class="navbar navbar-default navbar-static-top" role="navigation">
			<div class="container">
		    <h1 class="site-title"><a title="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="glyphicon glyphicon-home"></span></a> <?php bloginfo('name'); ?></h1>
			</div>
			</nav><!-- /.navbar -->
		
			<div class="clear"></div>
			<div class="wrapperbig">
			<div class="container">
			<div class="masthead">
			<?php if (has_nav_menu('top-right') ) : ?>
			<?php
			wp_nav_menu( array(
			'menu'              => 'top-right',
			'theme_location'    => 'top-right',
			'depth'             => 1,
			'container'         => false,
			'menu_class'        => 'nav nav-pills pull-right',
			'fallback_cb' 		=> 'wp_page_menu',
			'walker'            => new wp_bootstrap_navwalker())
			);
			?>
			<?php else : ?>
			<?php wp_nav_menu( array( 'theme_location' => 'top-right', 'depth' => 1, 'fallback_cb' => false, 'menu_class' => 'nav nav-pills pull-right' ) ); ?>
			<?php endif; ?>
			<img class="img-thumbnail" src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
		</div>
				<div class="clear"></div>
	
				
<!-- /primary navbar -->

<!-- /navigation -->
<?php if (has_nav_menu('primary') ) : ?>
     <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="navbar-collapse collapse">
			<?php
					wp_nav_menu( array(
					'theme_location' => 'primary',
					'depth' => 2,
					'container' => false,
					'fallback_cb' => 'wp_page_menu',
					'menu_class' => 'nav navbar-nav',
					//Process nav menu using our custom nav walker
					'walker' => new wp_bootstrap_navwalker())
					);
					?>
					<?php else : ?>
										<div id="navbar" class="navbar">
					<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<h3 class="menu-toggle"><?php _e( 'Menu', 'plainjane' ); ?></h3>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => 0, 'menu_class' => 'nav-menu' ) ); ?>
					</nav><!-- #site-navigation -->
					</div><!-- #navbar -->
					<?php endif; ?>
			</div>
			</div>
			</div>
			
			</div>
			
			<!-- /main layout divs -->
			
	<div id="wrap">
			
	<div class="container">		

		
	<div id="wrapcontainer">
	
	<!-- /content divs start -->
	
	<div id="container" role="main">

		<?php if (is_404() || is_page_template('public-full.php') || is_page_template('public-subpages.php') || is_page_template('public-subpages-4-column.php') || is_page_template('gallery-public.php')) : ?>
		<div class="col-md-12">
		<?php elseif (function_exists('bp_is_active') && bp_is_activation_page() || function_exists('bp_is_active') && bp_is_register_page() || function_exists('bp_is_active') &&  bp_is_current_component( 'groups' ) || function_exists('bp_is_active') && bp_is_current_component( 'activity' ) || function_exists('bp_is_active') && bp_is_current_component( 'members' ) || function_exists('bp_is_active') && bp_is_user() || class_exists('bbPress') && is_bbpress()) : ?>
		<div class="col-md-12">
		<?php else : ?>

		<!-- /side menu -->
		<div class="row">
		<div class="col-md-3">
	
	<!-- /vertical navbar -->
		<nav role="navigation">
			<?php if (has_nav_menu('vertical') ) : ?>
			<?php
			wp_nav_menu( array(
			'theme_location' => 'vertical',
			'depth' => 1,
			'container' => false,
			'menu_class' => 'nav nav-pills nav-stacked',
			//Process nav menu using our custom nav walker
			'walker' => new wp_bootstrap_navwalker())
			);
			?>
			<?php else : ?>
			<?php wp_nav_menu( array( 'theme_location' => 'vertical', 'depth' => 1, 'fallback_cb' => false, 'menu_class' => 'nav nav-pills nav-stacked' ) ); ?>
			<?php endif; ?>
		</nav>
		
		
		<div class="clear"></div>
			
			<?php if ( !function_exists('dynamic_sidebar')
			|| !dynamic_sidebar('Primary Sidebar') ) : ?>
			<?php endif; ?>
			
			
			<ul class="nav nav-pills nav-stacked">
			<?php wp_list_pages( array('title_li'=>'','include'=>plainjane_get_post_top_ancestor_id()) ); ?>
			<?php wp_list_pages( array('title_li'=>'','depth'=>1,'child_of'=>plainjane_get_post_top_ancestor_id()) ); ?>
			</ul>
			
			<?php global $post;
			
			$children = wp_list_pages('title_li=&depth=1&child_of='.$post->ID.'&echo=0');
			if ($children && is_page() && $post->post_parent) {
			echo "<h3>In this section</h3><ul class=\"nav nav-pills nav-stacked \">";
			echo $children;
			echo "</ul>";
			}
			
			?>
			
		</div>
		
		<!-- /content -->
	
		<div class="col-md-9">
		
		<?php endif; ?>
	
		<?php if (is_404() || is_page_template('public-full.php') || is_page_template('public-subpages-4-column.php') || is_page_template('public-subpages.php') || is_page_template('gallery-public.php')) : ?>
		<div class="full-width">
		<?php elseif (function_exists('bp_is_active') && bp_is_activation_page() || function_exists('bp_is_active') && bp_is_register_page() || function_exists('bp_is_active') &&  bp_is_current_component( 'groups' ) || function_exists('bp_is_active') && bp_is_current_component( 'activity' ) || function_exists('bp_is_active') && bp_is_user() || function_exists('bp_is_active') && bp_is_current_component( 'members' ) || class_exists('bbPress') && is_bbpress()) : ?>
	<div class="full-width">	
	<?php else : ?>
	<div class="post">
	<?php endif; ?>
		