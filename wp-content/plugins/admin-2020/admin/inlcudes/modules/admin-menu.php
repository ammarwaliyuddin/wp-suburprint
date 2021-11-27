<?php
if (!defined('ABSPATH')) {
    exit();
}

class Admin_2020_module_admin_menu
{
    public function __construct($version, $path, $utilities)
    {
        $this->version = $version;
        $this->path = $path;
        $this->utils = $utilities;
		$this->original_menu = array();
		$this->files = array();
    }

    /**
     * Loads menu actions
     * @since 1.0
     */

    public function start()
    {
		///REGISTER THIS COMPONENT
		add_filter('admin2020_register_component', array($this,'register'));
		
		if(!$this->utils->enabled($this)){
			return;
		}
		
		$info = $this->component_info();
		$optionname = $info['option_name'];
		
		if($this->utils->is_locked($optionname)){
			return;
		}
		
        add_action('admin_enqueue_scripts', [$this, 'add_styles'], 0);
		add_action('admin_enqueue_scripts', [$this, 'add_scripts'], 10);
		add_action('admin_enqueue_scripts', [$this, 'remove_styles'], 99999);
		add_filter('parent_file', array( $this, 'build_admin_menu'),999);
		add_action('adminmenu', array( $this, 'output_admin_menu' ));
		add_filter('admin_body_class', array($this, 'add_body_classes'));
		
		add_action('wp_ajax_a2020_get_menu', array($this,'a2020_get_menu'));
		
		
    }
	
	/**
	* Output body classes
	* @since 1 
	*/
	
	public function add_body_classes($classes) {
		
		$bodyclass = " a2020_admin_menu";
		
		return $classes.$bodyclass;
	}
	

	/**
	 * Register admin bar component
	 * @since 1.4
	 * @variable $components (array) array of registered admin 2020 components
	 */
	public function register($components){
		
		array_push($components,$this);
		return $components;
		
	}
	
	/**
	 * Returns component info for settings page
	 * @since 1.4
	 */
	public function component_info(){
		
		$data = array();
		$data['title'] = __('Menu','admin2020');
		$data['option_name'] = 'admin2020_admin_menu';
		$data['description'] = __('Creates new admin menu.','admin2020');
		return $data;
		
	}
	/**
	 * Returns settings for module
	 * @since 1.4
	 */
	 public function render_settings(){
		  
		wp_enqueue_media();
		
		$info = $this->component_info();
		$optionname = $info['option_name'];
		
		$light_background = $this->utils->get_option($optionname,'light-background');
		$dark_background = $this->utils->get_option($optionname,'dark-background');
		$search_enabled = $this->utils->get_option($optionname,'search-enabled');
		$shrunk_enabled = $this->utils->get_option($optionname,'shrunk-enabled');
		///GET POST TYPES
		$args = array('public'   => true);
		$output = 'objects'; 
		$post_types = get_post_types( $args, $output );
		
		$disabled_for = $this->utils->get_option($optionname,'disabled-for');
		if($disabled_for == ""){
			$disabled_for = array();
		}
		
		?>
		<div class="uk-grid" id="a2020_menu_settings" uk-grid>
		  
		<!-- BACKGROUND COLOUR -->
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5 "><?php _e('Background Color','admin2020')?></div>
		  <div class="uk-text-meta"><?php _e("Sets a background colour for the admin menu.",'admin2020') ?></div>
		</div>
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5"><?php _e('Light','admin2020')?></div>
		  
		  <input class=" a2020_setting" id="light-background" 
		  module-name="<?php echo $optionname?>" 
		  name="light-background" 
		  type="text"
		  data-default-color="#fff"
		  value="<?php echo $light_background?>">
		  
		</div>	
		
		<script>
		  jQuery(document).ready(function($){
			  $('#a2020_menu_settings #light-background').wpColorPicker();
		  });
		</script>
		
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5"><?php _e('Dark','admin2020')?></div>
		  
		  <input class="a2020_setting" id="dark-background" 
		  module-name="<?php echo $optionname?>" 
		  name="dark-background" 
		  type="text"
		  data-default-color="#111"
		  value="<?php echo $dark_background?>">
		  
		</div>	
		
		<script>
		  jQuery(document).ready(function($){
			  $('#a2020_menu_settings #dark-background').wpColorPicker();
		  });
		</script>
		
		<div class="uk-width-1-1">
		  <hr >
		</div>
		
		<!-- LOCKED FOR USERS / ROLES -->
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5 "><?php _e('Menu Disabled for','admin2020')?></div>
		  <div class="uk-text-meta"><?php _e("Admin 2020 menu will be disabled for any users or roles you select",'admin2020') ?></div>
		</div>
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  
		  
		  <select class="a2020_setting" id="a2020-role-types" name="disabled-for" module-name="<?php echo $optionname?>" multiple>
			  
			<?php
			foreach($disabled_for as $disabled) {
				
				?>
				<option value="<?php echo $disabled ?>" selected><?php echo $disabled ?></option>
				<?php
				
			} 
			?>
			
		  </select>
		  
		  <script>
			  jQuery('#a2020_menu_settings #a2020-role-types').tokenize2({
				  placeholder: '<?php _e('Select roles or users','admin2020') ?>',
				  dataSource: function (term, object) {
					  a2020_get_users_and_roles(term, object);
				  },
				  debounce: 1000,
			  });
		  </script>
		  
		</div>
		<div class="uk-width-1-1@ uk-width-1-3@m">
		</div>
		
		<!-- DISABLE MENU SEARCH -->
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5 "><?php _e('Disable Search','admin2020')?></div>
		  <div class="uk-text-meta"><?php _e("Disables admin menu search",'admin2020') ?></div>
		</div>
		<div class="uk-width-1-1@ uk-width-2-3@m">
		  
		  <?php
		  $checked = '';
		  if($search_enabled == 'true'){
			  $checked = 'checked';
		  }
		  ?>
		  
		  <label class="admin2020_switch uk-margin-left">
			  <input class="a2020_setting" name="search-enabled" module-name="<?php echo $optionname?>" type="checkbox" <?php echo $checked ?>>
			  <span class="admin2020_slider constant_dark"></span>
		  </label>
		  
		</div>	
		
		<!-- COLLAPSED MENU -->
		<div class="uk-width-1-1@ uk-width-1-3@m">
		  <div class="uk-h5 "><?php _e('Set collapsed menu as default','admin2020')?></div>
		  <div class="uk-text-meta"><?php _e("If enabled, the menu will default to the shrunk menu for users that haven't set a preference.",'admin2020') ?></div>
		</div>
		<div class="uk-width-1-1@ uk-width-2-3@m">
		  
		  <?php
		  $checked = '';
		  if($shrunk_enabled == 'true'){
			  $checked = 'checked';
		  }
		  ?>
		  
		  <label class="admin2020_switch uk-margin-left">
			  <input class="a2020_setting" name="shrunk-enabled" module-name="<?php echo $optionname?>" type="checkbox" <?php echo $checked ?>>
			  <span class="admin2020_slider constant_dark"></span>
		  </label>
		  
		</div>	
		  
		  	
		</div>	
		
		<?php
	  }
    /**
     * Adds admin bar styles
     * @since 1.0
     */

    public function add_styles()
    {
		
		if(is_rtl()){
			
			//RTL MENU STYLEWS
			wp_register_style('admin2020_admin_menu',$this->path . 'assets/css/modules/admin-menu-rtl.css',array(), $this->version);
			wp_enqueue_style('admin2020_admin_menu');
			
		} else {
			
			//MENU STYLES
			wp_register_style('admin2020_admin_menu',$this->path . 'assets/css/modules/admin-menu.css',array(), $this->version);
			wp_enqueue_style('admin2020_admin_menu');
			
		}
    }
	
	/**
	* Enqueue Admin Bar 2020 scripts
	* @since 1.4
	*/
	
	public function add_scripts(){
		
		global $menu, $submenu, $parent_file, $submenu_file;
		//$parent_file = apply_filters( 'parent_file', $parent_file );
		$newmenu = $this->a2020_format_admin_menu($menu, $submenu);
		
		//echo '<pre>' . print_r( $newmenu['submenu'], true ) . '</pre>';
		
		$formattedMenu = $this->build_top_level_menu_items($newmenu);
		$favs = $this->utils->get_user_preference('a2020_menu_favs');
		$search = $this->utils->get_user_preference('a2020_menu_search');
		$icons = $this->utils->get_user_preference('a2020_menu_icons');
		$subHover = $this->utils->get_user_preference('a2020_sub_hover');
		$menuState = $this->utils->get_user_preference('a2020_menu_collapse');
		$favsOn = $this->utils->get_user_preference('a2020_menu_favs_on');
		$darkmode = $this->utils->get_user_preference('darkmode');
		
		if(!$favs){
			$favs = array();
		}
		
		
		$preferences = array(); 
		$master = array();
		
		$info = $this->component_info();
		$optionname = $info['option_name'];
		
		$forceShrunk = $this->utils->get_option($optionname,'shrunk-enabled');
		
		$master['search'] = $this->utils->get_option($optionname,'search-enabled');
		$master['collapseDefault'] = $this->utils->get_option($optionname,'shrunk-enabled');
		
		
		if ($menuState == '' && $forceShrunk == 'true'){
			$menuState = 'true';
		}
		
		$preferences['favourites'] = $favs; 
		$preferences['menuSearch'] = $search;
		$preferences['menuIcons'] = $icons;
		$preferences['subHover'] = $subHover;
		$preferences['menuShrunk'] = $menuState;
		$preferences['favsOn'] = $favsOn;
		$preferences['darkMode'] = $darkmode;
		
		if(!$favs){
			$favs = array();
		}
		
		
		///MENU APP
		wp_enqueue_script('admin-menu-app', $this->path . 'assets/js/admin2020/admin-menu-app.min.js', array('jquery') ,$this->version, true);
		wp_localize_script('admin-menu-app', 'a2020_menu_ajax', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'security' => wp_create_nonce('a2020-menu-security-nonce'),
			'menu_object' => json_encode($formattedMenu),
			'preferences' => json_encode($preferences),
			'masterPrefs' => json_encode($master),
		));
	  
	}
	
	/**
	* Removes wp default menu styling
	* @since 1.4
	*/
	
	public function remove_styles(){
		
		wp_dequeue_style('admin-menu');
		wp_deregister_style('admin-menu');
		wp_register_style(
			'admin-menu',
			$this->path . 'assets/css/modules/blank.css',
			array(),
			$this->version
		);
		wp_enqueue_style('admin-menu');
		
	}
	/**
	* Scans admin directory for menu links
	* @since 1.4
	*/
	public function get_admin_files(){
		
		$absolutepath = ABSPATH . '/wp-admin'."/";
		$files = array_diff(scandir($absolutepath), array('.', '..'));

		if (is_multisite()){
		  $pathtonetwork = ABSPATH . '/wp-admin'."/network/";
		  $networkfiles = array_diff(scandir($pathtonetwork), array('.', '..'));
		  $files = array_merge($files,$networkfiles);
		}
		
		return $files;
		
	}
	
	/**
	* Builds new admin menu
	* @since 1.4
	*/
	
	public function build_admin_menu($parent_file){
		
		global $menu, $pagenow,$admin2020_menu;
		$this->original_menu = $menu;
		//disable default menu
		$menu = array();
		
		$darkmode = $this->utils->get_user_preference('darkmode');
		$dark_enabled = $this->utils->get_option('admin2020_admin_bar','dark-enabled');
			
		$class = '';
		
		if($darkmode == 'true'){
			$class= 'uk-light';
		} else if ($darkmode == '' && $dark_enabled == 'true'){
			$class = " uk-light";
		}
		
		$info = $this->component_info();
		$optionname = $info['option_name'];
		$light_background = $this->utils->get_option($optionname,'light-background');
		$dark_background = $this->utils->get_option($optionname,'dark-background');
		
		
		
		
		ob_start();
		
		if($light_background != ""){
			
			$light_without_hex = str_replace('#', '', $light_background);
			$hexRGB = $light_without_hex;
			if(hexdec(substr($hexRGB,0,2))+hexdec(substr($hexRGB,2,2))+hexdec(substr($hexRGB,4,2))< 381){
				$class = " uk-light";
			}
			
			?>
			<style type="text/css">
			body:not(.a2020_night_mode) .admin2020_menu {background:<?php echo $light_background?>;}
			body:not(.a2020_night_mode) .admin2020_menu .a2020-settings-panel {background:<?php echo $light_background?>;}
			body:not(.a2020_night_mode) .admin2020_menu .uk-dropdown {background:<?php echo $light_background?>;}
			body:not(.a2020_night_mode) .admin2020_menu .uk-dropdown::after {background:<?php echo $light_background?>;}
			</style>
			<?php
		}
		if($dark_background != ""){
			
			$light_without_hex = str_replace('#', '', $dark_background);
			$hexRGB = $light_without_hex;
			if(hexdec(substr($hexRGB,0,2))+hexdec(substr($hexRGB,2,2))+hexdec(substr($hexRGB,4,2))> 381){
				$class = "";
			}
			?>
			<style type="text/css">
			.a2020_night_mode .admin2020_menu {background:<?php echo $dark_background?>;}
			.a2020_night_mode .admin2020_menu .uk-dropdown {background:<?php echo $dark_background?>;}
			.admin2020_menu .uk-dropdown::after {background:<?php echo $dark_background?>;}
			</style>
			<?php
		}
		?>
		
		<div id="a2020-menu-app">
			
			<?php $this->build_mobile_menu();?>
			<?php $this->build_desktop_menu();?>
			
		</div>
		
		
		
		<?php
		
		$admin2020_menu = ob_get_clean();
		
		return $parent_file;
		
	}
	
	
	public function build_mobile_menu(){
		
		?>
		<!--MOBILE MENU -->
		<!--MOBILE MENU -->
		<!--MOBILE MENU -->
		<template v-if="isSmallScreen()" id="a2020-desktop-menu">
			<div id="a2020-mobile-nav" uk-offcanvas="overlay: true;mode:slide;container:#adminmenu" >
				<div class="uk-offcanvas-bar uk-padding-remove uk-height-viewport uk-overflow-auto" style="padding-top: 61px !important;">
			
			
					
						<div v-if="!loading" class="admin2020_menu a2020_dark_anchor uk-background-default uk-height-1-1 uk-padding-small uk-padding-remove-horizontal"
						:class="{'uk-light' : menuPrefs.darkMode }"
						style="margin-bottom: 0;height: auto;max-height: none !important;overflow: visible;">
							
							
							<div v-if="menuPrefs.searchBar && !master.search" class="uk-padding-small" 
							:class="{ 'extra-padding' : menuPrefs.favsOn}">
								
								<li  class="a2020_menu_searcher_wrap" >
									<div class="uk-inline uk-width-1-1 a2020_menu_search">
										<span class="uk-form-icon material-icons-outlined " style="font-size: 18px;width: 30px;">manage_search</span>
										<input class="uk-input uk-form-small"
										v-model="search" 
										style="border: none;background: #f2f2f2;padding-left:30px !important;"
										type="search" autocomplete="off" placeholder="<?php _e('Search','admin2020') ?>...">
									</div>
								</li>
							
							</div>
							
							<ul class="uk-nav-default uk-nav-parent-icon uk-nav" uk-nav="">
								
								
								<template v-if="menuPrefs.favsOn">
									
									
									<template v-for="item in favourites">
										
										<li class="a2020-favourite menu-top" :class="[item.classes, {'uk-open' : item.open }]" :id="item.id">
											<div class="uk-flex uk-flex-between">
												
												<a class="menu-icon-generic" :href="item.href">
													
													<span class="material-icons-outlined a2020-menu-icon" style="font-size: 18px;">bookmark_border</span>
													<span class="a2020-menu-title wp-menu-name" v-html="item.name"></span>
													
												</a>
												
											</div>
											
										</li>
										
									</template>
									
									<li v-if="menuPrefs.favsEditingMode == true">
										<div class="uk-grid uk-grid-small uk-margin-small-top">
											<div class="uk-width-1-2">
												<button class="uk-button uk-button-small uk-width-1-1" @click="cancelFavourites()" type="button"><?php _e('Cancel','admin2020')?></button>
											</div>
											<div class="uk-width-1-2">
												<button class="uk-button uk-button-secondary uk-button-small uk-width-1-1" @click="saveFavourites()" type="button"><?php _e('Save','admin2020')?></button>
											</div>
											
											<div class="uk-width-1-1 uk-margin-small-top">
												<button class="uk-button uk-button-small uk-text-danger uk-width-1-1" 
												@click="clearFavourites()" type="button"><?php _e('Clear Favourites','admin2020')?></button>
											</div>
										</div>
									</li>
									
								
								</template>
							
							
								
								<li class="uk-nav-divider"></li>
								
								<template v-for="menuItem in filteredMenu">
									
									
									<!--TOP LEVEL MENU ITEM -->
									<li v-if="menuItem.type == 'menu'" :class="[menuItem.classes, {'uk-open' : menuItem.open }]" :id="menuItem.id">
										<div class="uk-flex uk-flex-between ">
											
											
											<a class="menu-icon-generic" :class="menuItem.classes" :href="menuItem.href">
												
												<span v-if="!menuPrefs.icons" v-html="menuItem.icon" ></span>
												<span class="a2020-menu-title wp-menu-name" v-html="menuItem.name"></span>
											</a>
											
											
											<template v-if="menuItem.submenu">
												
												<div class="uk-text-right" @click="menuItem.open = !menuItem.open" style="flex-grow:1;cursor: pointer;">
													<span v-if="menuItem.open"  class="material-icons-outlined uk-text-muted a2020-menu-chev">expand_more</span>
													<span v-if="!menuItem.open"  class="material-icons-outlined uk-text-muted  a2020-menu-chev">chevron_left</span>
												</div>
												
											</template>
											
											<span v-if="menuPrefs.favsEditingMode" style="cursor: pointer;">
												<span v-if="!isIn(menuItem.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right" 
												@click="addFavourite(menuItem)"
												style="font-size: 18px;top: 4px;position: relative;">favorite_border</span>
												<span v-if="isIn(menuItem.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right uk-text-danger" 
												@click="removeFavourite(menuItem)"
												style="font-size: 18px;top: 4px;position: relative;">favorite</span>
											</span>
										
										</div> 
										<!-- SUBMENU -->
										<template v-if="menuItem.submenu">
											
											<ul v-if="menuItem.open" class="uk-nav-sub wp-submenu wp-submenu-wrap">
												
												<template v-for="sub in menuItem.submenu">
													<li :class="sub.classes">
														
														<div class="uk-flex uk-flex-between">
															
															<a :class="sub.classes" :href="sub.href" v-html="sub.name"></a>
															
															<span v-if="menuPrefs.favsEditingMode" style="cursor: pointer;">
																<span v-if="!isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right" 
																@click="addFavourite(sub)"
																style="font-size: 18px;top: 4px;position: relative;">favorite_border</span>
																<span v-if="isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right uk-text-danger" 
																@click="removeFavourite(sub)"
																style="font-size: 18px;top: 4px;position: relative;">favorite</span>
															</span>
														
														</div>
														
														
													</li>
												</template>
												
											</ul>
										
										</template>
									</li>
									
									<!--SEP WITH NAME -->
									<template v-if="menuItem.type == 'sep' && menuItem.name">
										<li class="uk-nav-header uk-text-bold uk-margin-small-bottom" style="text-transform: none">{{menuItem.name}}</li>
										<li class="uk-nav-divider divider-placeholder"></li>
									</template> 
									
									<!-- SEP NO NAME -->
									<li v-if="menuItem.type == 'sep'" class="uk-nav-divider"></li>
									
								</template>
							
							</ul>
							
							
							
							
						</div>
			
						
						
				
					
				
				</div>
				
				
			</div>
		</template>
		
		<?php
		
	}
	
	
	public function build_desktop_menu(){
		
		?>
		
		
		<!--DESKTOP MENU -->
		<!--DESKTOP MENU -->
		<template v-if="!isSmallScreen()" id="a2020-desktop-menu">
		
			<div v-if="!menuPrefs.shrunk" 
			class="admin2020_menu loader a2020_dark_anchor uk-background-default uk-height-1-1 uk-padding-small uk-padding-remove-horizontal" 
			:class="{'hidden' : !loading}">
			<!--LOADING -->
				<ul  class="uk-nav-default uk-nav-parent-icon uk-nav loading" uk-nav="" style="padding-bottom: 0">
					
					<li class="menu-top ">
						<div class="uk-flex uk-flex-between">
							
							<a class="menu-icon-generic" href="#">
								
								<span class="a2020-menu-icon" ></span>
								<span class="a2020-menu-title wp-menu-name" style="width: 80%"></span>
								
							</a>
							
						</div>
						
						<ul class="uk-nav-sub wp-submenu wp-submenu-wrap">
							
							<li><a class="sub-item"></a></li>
							
							<li><a class="sub-item" style="width: 45%"></a></li>
							
							<li><a class="sub-item" style="width: 53%"></a></li>
							
						</ul>
						
					</li>


					<li class="menu-top ">
						<div class="uk-flex uk-flex-between">
							
							<a class="menu-icon-generic" href="#">
								
								<span class="a2020-menu-icon" ></span>
								<span class="a2020-menu-title wp-menu-name" style="width:70%"></span>
								
							</a>
							
						</div>
						
						<ul class="uk-nav-sub wp-submenu wp-submenu-wrap">
							
							<li><a class="sub-item" style="width: 30%"></a></li>
							
							<li><a class="sub-item" style="width: 70%"></a></li>
							
							<li><a class="sub-item" style="width: 40%"></a></li>
							
						</ul>
						
					</li>
					
					<li class="menu-top ">
						<div class="uk-flex uk-flex-between">
							
							<a class="menu-icon-generic" href="#">
								
								<span class="a2020-menu-icon" ></span>
								<span class="a2020-menu-title wp-menu-name"  style="width: 83%"></span>
								
							</a>
							
						</div>
						
						<ul class="uk-nav-sub wp-submenu wp-submenu-wrap">
							
							<li><a class="sub-item"></a></li>
							
							<li><a class="sub-item" style="width: 45%"></a></li>
							
							<li><a class="sub-item" style="width: 53%"></a></li>
							
						</ul>
						
					</li>
										
				</ul>
				<!--LOADING -->
			</div>
			
			<div 
			class="admin2020_menu a2020_dark_anchor uk-background-default uk-height-1-1 uk-padding-small uk-padding-remove-horizontal show-after-load"
				:class="[{'a2020-menu-minified' : menuPrefs.shrunk }, {'loaded' : !loading}, {'no-icons' : menuPrefs.icons}, {'uk-light' : menuPrefs.darkMode }]">
				
				
				
				<div v-if="menuPrefs.searchBar && !menuPrefs.shrunk && !master.search" class="uk-padding-small" 
				:class="{ 'extra-padding' : menuPrefs.favsOn}">
					
					<li  class="a2020_menu_searcher_wrap" >
						<div class="uk-inline uk-width-1-1 a2020_menu_search">
							<span class="uk-form-icon material-icons-outlined " style="font-size: 18px;width: 30px;">manage_search</span>
							<input class="uk-input uk-form-small"
							v-model="search" 
							style="padding-left: 32px !important;"
							type="search" autocomplete="off" placeholder="<?php _e('Search','admin2020') ?>...">
						</div>
					</li>
				
				</div>
				
				<ul class="uk-nav-default uk-nav-parent-icon uk-nav" uk-nav="">
					
					
					<template v-if="menuPrefs.favsOn && !menuPrefs.shrunk">
						
						
						<template v-for="item in favourites">
							
							<li class="a2020-favourite menu-top" :class="[item.classes, {'uk-open' : item.open }]" :id="item.id">
								<div class="uk-flex uk-flex-between">
									
									<a class="menu-icon-generic" :href="item.href">
										
										<span class="material-icons-outlined a2020-menu-icon" style="font-size: 18px;">bookmark_border</span>
										<span class="a2020-menu-title wp-menu-name" v-html="item.name"></span>
										
									</a>
									
								</div>
								
							</li>
							
						</template>
						
						<li v-if="menuPrefs.favsEditingMode == true">
							<div class="uk-grid uk-grid-small uk-margin-small-top">
								<div class="uk-width-1-2">
									<button class="uk-button uk-button-small uk-width-1-1" @click="cancelFavourites()" type="button"><?php _e('Cancel','admin2020')?></button>
								</div>
								<div class="uk-width-1-2">
									<button class="uk-button uk-button-secondary uk-button-small uk-width-1-1" @click="saveFavourites()" type="button"><?php _e('Save','admin2020')?></button>
								</div>
								
								<div class="uk-width-1-1 uk-margin-small-top">
									<button class="uk-button uk-button-small uk-text-danger uk-width-1-1" 
									@click="clearFavourites()" type="button"><?php _e('Clear Favourites','admin2020')?></button>
								</div>
							</div>
						</li>
						
					
					</template>
				
				
					
					<li class="uk-nav-divider"></li>
					
					<template v-for="menuItem in filteredMenu">
						
						
						<!--TOP LEVEL MENU ITEM -->
						<li v-if="menuItem.type == 'menu'" :class="[menuItem.classes, {'uk-open' : menuItem.open }]" :id="menuItem.id">
							<div class="uk-flex uk-flex-between uk-flex-middle">
								
								
								<a class="menu-icon-generic uk-flex uk-flex-middle"  :class="menuItem.classes" :href="menuItem.href">
									
									<span v-if="!menuPrefs.icons || menuPrefs.shrunk" v-html="menuItem.icon" ></span>
									<span v-if="!menuPrefs.shrunk" class="a2020-menu-title wp-menu-name" v-html="menuItem.name"></span>
								</a>
								
								
								<template v-if="menuItem.submenu && !menuPrefs.subHover && !menuPrefs.shrunk">
									
									<div class="uk-text-right" @click="menuItem.open = !menuItem.open" style="flex-grow:1;cursor: pointer;">
										<span v-if="menuItem.open"  class="material-icons-outlined uk-text-muted a2020-menu-chev">expand_more</span>
										<span v-if="!menuItem.open"  class="material-icons-outlined uk-text-muted  a2020-menu-chev">chevron_left</span>
									</div>
									
								</template>
								
								<span v-if="menuPrefs.favsEditingMode" style="cursor: pointer;">
									<span v-if="!isIn(menuItem.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right" 
									@click="addFavourite(menuItem)"
									style="font-size: 18px;top: 4px;position: relative;">favorite_border</span>
									<span v-if="isIn(menuItem.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right uk-text-danger" 
									@click="removeFavourite(menuItem)"
									style="font-size: 18px;top: 4px;position: relative;">favorite</span>
								</span>
							
							</div> 
							<!-- SUBMENU -->
							<template v-if="menuItem.submenu">
								
								<ul v-if="menuItem.open && !menuPrefs.subHover && !menuPrefs.shrunk" class="uk-nav-sub wp-submenu wp-submenu-wrap">
									
									<template v-for="sub in menuItem.submenu">
										<li :class="sub.classes">
											
											<div class="uk-flex uk-flex-between">
												
												<a :class="sub.classes" :href="sub.href" v-html="sub.name"></a>
												
												<span v-if="menuPrefs.favsEditingMode" style="cursor: pointer;">
													<span v-if="!isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right" 
													@click="addFavourite(sub)"
													style="font-size: 18px;top: 4px;position: relative;">favorite_border</span>
													<span v-if="isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right uk-text-danger" 
													@click="removeFavourite(sub)"
													style="font-size: 18px;top: 4px;position: relative;">favorite</span>
												</span>
											
											</div>
											
											
										</li>
									</template>
									
								</ul>
								
								<div v-if="menuPrefs.subHover || menuPrefs.shrunk " class="a2020-dropdown-right-center" uk-dropdown="mode: hover;pos:right-center;offset:20;">
								
									<ul class="uk-nav-sub wp-submenu wp-submenu-wrap" style="padding: 0;margin-bottom: 0;">
										
										
										<li   class="uk-margin-small-bottom">
											<a class="menu-icon-generic" :class="menuItem.classes" :href="menuItem.href">
												
												<span v-if="!menuPrefs.icons" v-html="menuItem.icon" ></span>
												<span  class="a2020-menu-title wp-menu-name" v-html="menuItem.name"></span>
												
											</a>
										</li>
										
										<template v-for="sub in menuItem.submenu">
											<li class="uk-flex uk-flex-between" :class="sub.classes">
												<a :class="sub.classes" :href="sub.href" v-html="sub.name"></a>
												
												<span v-if="menuPrefs.favsEditingMode" style="cursor: pointer;">
													<span v-if="!isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right" 
													@click="addFavourite(sub)"
													style="font-size: 18px;top: 4px;position: relative;">favorite_border</span>
													<span v-if="isIn(sub.id, menuPrefs.favourites)"class="material-icons-outlined uk-margin-small-right uk-text-danger" 
													@click="removeFavourite(sub)"
													style="font-size: 18px;top: 4px;position: relative;">favorite</span>
												</span>
												
											</li>
										</template>
										
									</ul>
									
								</div>
							
							</template>
						</li>
						
						<!--SEP WITH NAME -->
						<template v-if="menuItem.type == 'sep' && menuItem.name">
							<li class="uk-nav-header uk-text-bold uk-margin-small-bottom" style="text-transform: none">{{menuItem.name}}</li>
							<li class="uk-nav-divider divider-placeholder"></li>
						</template> 
						
						<!-- SEP NO NAME -->
						<li v-if="menuItem.type == 'sep'" class="uk-nav-divider"></li>
						
					</template>
				
				</ul>
				
				<div class="uk-position-bottom  a2020-border top  right uk-background-default a2020-settings-panel"  style="padding: 10px 15px;bottom: 0;">
					<div class="uk-flex uk-flex-between">
						<a href="#" class="uk-link-muted">
							<span class="material-icons-outlined" style="font-size: 18px;" @click="switchMenu()">menu_open</span>
						</a>
						
						<a href="#" class="uk-link-muted" v-if="!menuPrefs.shrunk">
							<span class="material-icons-outlined" style="font-size: 18px;">settings</span>
							
							<div uk-dropdown="mode: click; pos: top-center;" class="uk-padding-remove ">
								
								<div class="uk-padding-small" style="width:250px;"> 
									<div class="uk-h5"><?php _e('Menu Preferences','admin2020')?></div>
									
									<div class="uk-margin" v-if="!master.search">
										<div class="uk-grid uk-grid-small">
											
											<div class="uk-text-meta uk-width-2-3"><?php _e('Show search bar','admin2020')?>:</div>
											
											<div class="uk-width-1-3">
												<div class="a2020-checkbox a2020-checkbox-small uk-border-rounded a2020-border all" :class="{'checked' : menuPrefs.searchBar }" >
												  <span class="material-icons-outlined">done</span>
												  <input type="checkbox" v-model="menuPrefs.searchBar"  style="opacity: 0 !important;">
												</div>
											</div>
											
										</div>
									</div>
									
									<div class="uk-margin">
										<div class="uk-grid uk-grid-small">
											
											<div class="uk-text-meta uk-width-2-3"><?php _e('Hide menu icons','admin2020')?>:</div>
											
											<div class="uk-width-1-3">
												<div class="a2020-checkbox a2020-checkbox-small uk-border-rounded a2020-border all" :class="{'checked' : menuPrefs.icons }" >
												  <span class="material-icons-outlined">done</span>
												  <input type="checkbox" v-model="menuPrefs.icons"  style="opacity: 0 !important;">
												</div>
											</div>
											
										</div>
									</div>
									
									<div class="uk-margin">
										<div class="uk-grid uk-grid-small ">
											
											<div class="uk-text-meta uk-width-2-3"><?php _e('Show submenu on hover','admin2020')?>:</div>
											
											<div class="uk-width-1-3">
												<div class="a2020-checkbox a2020-checkbox-small uk-border-rounded a2020-border all" :class="{'checked' : menuPrefs.subHover }" >
												  <span class="material-icons-outlined">done</span>
												  <input type="checkbox" v-model="menuPrefs.subHover"  style="opacity: 0 !important;">
												</div>
											</div>
										</div>
									</div>
									
									<div class="uk-margin">
										<div class="uk-grid uk-grid-small ">
											
											<div class="uk-text-meta uk-width-2-3"><?php _e('Show favourites','admin2020')?>:</div>
											
											<div class="uk-width-1-3">
												<div class="a2020-checkbox a2020-checkbox-small uk-border-rounded a2020-border all" :class="{'checked' : menuPrefs.favsOn }" >
												  <span class="material-icons-outlined">done</span>
												  <input type="checkbox" v-model="menuPrefs.favsOn"  style="opacity: 0 !important;">
												</div>
											</div>
										</div>
									</div>
									
									<div v-if="menuPrefs.favsOn" class="uk-margin">
											<button class="uk-button uk-button-small uk-width-1-1" 
											@click="setFavourites()" type="button"><?php _e('Set Favourites','admin2020')?></button>
									</div>
								
								</div>
								
								
								
							</div>
						</a>
					</div>
					
				</div>
				
				
			</div>
		
		</template>
		
		<?php
		
		
		
		
	}
	
	
	
	/**
	* Loops through top level menu items
	* @since 1.4
	*/
	public function build_top_level_menu_items($themenu){
		
		$thesubmenu = $themenu['submenu'];
		$the_menu = $themenu['menu'];
		
		$this->original_submenu = $thesubmenu;
		
		$formattedMenu = array();
		
		foreach ($the_menu as $menu_item){
			
			$tempMenu = array();
			
			$menu_name = $menu_item[0];
			$menu_link = $menu_item[2];
			$divider = false;
			
			if (strpos($menu_link,"separator") !== false){
				$divider = true;
				$tempMenu['type'] = 'sep';
				
				if(isset($menu_item['name'])){
					
					$tempMenu['name'] = $menu_item['name'];
					
				}
				
				$formattedMenu[] = $tempMenu;
				
				continue;
			}
			
			if(!$menu_name){
				continue;
			}
			
			if(isset($thesubmenu[$menu_link])){
				$sub_menu_items = $thesubmenu[$menu_link];
			} else {
				$sub_menu_items = false;
			}
			
			$link = $menu_item['url'];
			
			$classes = $this->get_menu_clases($menu_item,$thesubmenu);
			
			
			
			
			
			$tempMenu['id'] = $menu_item[5];
			$tempMenu['name'] = $menu_name;
			$tempMenu['icon'] = $this->get_icon($menu_item);
			$tempMenu['classes'] = $classes;
			$tempMenu['href'] = $link;
			$tempMenu['type'] = 'menu';
			$tempMenu['open'] = false;
			
			if (strpos($classes,"uk-open") !== false){
				$tempMenu['open'] = true;
			}
			
			if(is_array($sub_menu_items)){
				$tempMenu['submenu'] = $this->build_sub_level_menu_items($sub_menu_items, $tempMenu['id']);
			}
			
			$formattedMenu[] = $tempMenu;
			
			
		}
		
		return $formattedMenu;
		
	}
	
	
	
	
	/**
	* Gets correct link for menu item
	* @since 1.4
	*/
	
	public function get_menu_link($menu_item){
		
		$menu_link = $menu_item[2];
		
		$gen_link = "";
		$gen_link = menu_page_url($menu_item[2],false);
		
		return json_encode($menu_item);
		
		if($gen_link != ""){
			return $gen_link;
		} else {
		
			$files = $this->get_admin_files();
			$this->files = $files;
			
			if (strpos($menu_link, 'admin.php') !== false) {
				$link = $menu_link;
			} 
			else if (strpos($menu_link, '.php') !== false) {
				
				$link = $menu_link;
				if (strpos($menu_link, '/') !== false) {
					$pieces = explode("/", $menu_link);
					if (strpos($pieces[0], '.php') !== true || !file_exists(get_admin_url().$menu_link)) {
						$link = 'admin.php?page=' . $menu_link;
					}
				}
			
				$querypieces = explode("?", $link);
				$temp = $querypieces[0];
				
				if( !in_array( $temp ,$files )){
					$link = 'admin.php?page=' . $menu_link;
				}
			
			}  else {
				
				$link = 'admin.php?page=' . $menu_link;
			
			}
			
			if (strpos($menu_link, "/wp-content/") !== false) {
				
				$link = 'admin.php?page=' . $menu_link;
				
			}
			
			//CHECK IF INTERNAL URL
			if (strpos($menu_link, get_site_url()) !== false) {
				
				$link = $menu_link;
				
			}
			
			///CHECK IF EXTERNAL LINK
			if(strpos($menu_link, 'https://') !== false || strpos($menu_link, 'http://') !== false) {
				
				$link = $menu_link;
				
			}
			
			///UPDRAFT PLUS WORKAROUND
			if($link == 'admin.php?page=updraftplus'){
				
				$link = 'options-general.php?page=updraftplus';
				
			}
			
			return $link;
		}
		
	}
	
	
	public function a2020_format_admin_menu( $menu, $submenu, $submenu_as_parent = true ) {
		global $self, $parent_file, $submenu_file, $plugin_page, $typenow;
	
		$first = true;
		$returnmenu = array();
		$returnsubmenu = array();
		// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes, 5 = hookname, 6 = icon_url.
		foreach ( $menu as $key => $item ) {
			
			$admin_is_parent = false;
			$class           = array();
			$aria_attributes = '';
			$aria_hidden     = '';
			$is_separator    = false;
	
			if ( $first ) {
				$class[] = 'wp-first-item';
				$first   = false;
			}
	
			$submenu_items = array();
			if ( ! empty( $submenu[ $item[2] ] ) ) {
				$class[]       = 'wp-has-submenu';
				$submenu_items = $submenu[ $item[2] ];
			}
	
			if ( ( $parent_file && $item[2] === $parent_file ) || ( empty( $typenow ) && $self === $item[2] ) ) {
				if ( ! empty( $submenu_items ) ) {
					$class[] = 'wp-has-current-submenu wp-menu-open';
					$item['active'] = true;
				} else {
					$class[]          = 'current';
					$aria_attributes .= 'aria-current="page"';
					$item['active'] = true;
				}
			} else {
				$class[] = 'wp-not-current-submenu';
				$item['active'] = false;
				if ( ! empty( $submenu_items ) ) {
					$aria_attributes .= 'aria-haspopup="true"';
				}
			}
	
			if ( ! empty( $item[4] ) ) {
				$class[] = esc_attr( $item[4] );
			}
	
			$class     = $class ? ' class="' . implode( ' ', $class ) . '"' : '';
			$id        = ! empty( $item[5] ) ? ' id="' . preg_replace( '|[^a-zA-Z0-9_:.]|', '-', $item[5] ) . '"' : '';
			$img       = '';
			$img_style = '';
			$img_class = ' dashicons-before';
	
			if ( false !== strpos( $class, 'wp-menu-separator' ) ) {
				$is_separator = true;
			}
	
			/*
			 * If the string 'none' (previously 'div') is passed instead of a URL, don't output
			 * the default menu image so an icon can be added to div.wp-menu-image as background
			 * with CSS. Dashicons and base64-encoded data:image/svg_xml URIs are also handled
			 * as special cases.
			 */
			if ( ! empty( $item[6] ) ) {
				$img = '<img src="' . $item[6] . '" alt="" />';
	
				if ( 'none' === $item[6] || 'div' === $item[6] ) {
					$img = '<br />';
				} elseif ( 0 === strpos( $item[6], 'data:image/svg+xml;base64,' ) ) {
					$img       = '<br />';
					$img_style = ' style="background-image:url(\'' . esc_attr( $item[6] ) . '\')"';
					$img_class = ' svg';
				} elseif ( 0 === strpos( $item[6], 'dashicons-' ) ) {
					$img       = '<br />';
					$img_class = ' dashicons-before ' . sanitize_html_class( $item[6] );
				}
			}
			$arrow = '<div class="wp-menu-arrow"><div></div></div>';
	
			$title = wptexturize( $item[0] );
	
			// Hide separators from screen readers.
			if ( $is_separator ) {
				$aria_hidden = ' aria-hidden="true"';
			}
	
			//echo "\n\t<li$class$id$aria_hidden>";
	
			if ( $is_separator ) {
				//echo '<div class="separator"></div>';
			} elseif ( $submenu_as_parent && ! empty( $submenu_items ) ) {
				$submenu_items = array_values( $submenu_items );  // Re-index.
				$menu_hook     = get_plugin_page_hook( $submenu_items[0][2], $item[2] );
				$menu_file     = $submenu_items[0][2];
				$pos           = strpos( $menu_file, '?' );
	
				if ( false !== $pos ) {
					$menu_file = substr( $menu_file, 0, $pos );
				}
	
				if ( ! empty( $menu_hook )
					|| ( ( 'index.php' !== $submenu_items[0][2] )
						&& file_exists( WP_PLUGIN_DIR . "/$menu_file" )
						&& ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) )
				) {
					$admin_is_parent = true;
					//echo "<a href='admin.php?page={$submenu_items[0][2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style aria-hidden='true'>$img</div><div class='wp-menu-name'>$title</div></a>";
					$item['url'] = 'admin.php?page='.$submenu_items[0][2];
				} else {
					//echo "\n\t<a href='{$submenu_items[0][2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style aria-hidden='true'>$img</div><div class='wp-menu-name'>$title</div></a>";
					$item['url'] = $submenu_items[0][2];
				}
			} elseif ( ! empty( $item[2] ) && current_user_can( $item[1] ) ) {
				$menu_hook = get_plugin_page_hook( $item[2], 'admin.php' );
				$menu_file = $item[2];
				$pos       = strpos( $menu_file, '?' );
	
				if ( false !== $pos ) {
					$menu_file = substr( $menu_file, 0, $pos );
				}
	
				if ( ! empty( $menu_hook )
					|| ( ( 'index.php' !== $item[2] )
						&& file_exists( WP_PLUGIN_DIR . "/$menu_file" )
						&& ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) )
				) {
					$admin_is_parent = true;
					//echo "\n\t<a href='admin.php?page={$item[2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style aria-hidden='true'>$img</div><div class='wp-menu-name'>{$item[0]}</div></a>";
					$item['url'] = 'admin.php?page='.$item[2];
				} else {
					//echo "\n\t<a href='{$item[2]}'$class $aria_attributes>$arrow<div class='wp-menu-image$img_class'$img_style aria-hidden='true'>$img</div><div class='wp-menu-name'>{$item[0]}</div></a>";
					$item['url'] = $item[2];
				}
			}
	
			if ( ! empty( $submenu_items ) ) {
				//echo "\n\t<ul class='wp-submenu wp-submenu-wrap'>";
				//echo "<li class='wp-submenu-head' aria-hidden='true'>{$item[0]}</li>";
	
				$first = true;
	
				// 0 = menu_title, 1 = capability, 2 = menu_slug, 3 = page_title, 4 = classes.
				$tempsub = array();
				
				foreach ( $submenu_items as $sub_key => $sub_item ) {
					
					$sub_item['active'] = false;
					
					if ( ! current_user_can( $sub_item[1] ) ) {
						continue;
					}
	
					$class           = array();
					$aria_attributes = '';
	
					if ( $first ) {
						$class[] = 'wp-first-item';
						$first   = false;
					}
	
					$menu_file = $item[2];
					$pos       = strpos( $menu_file, '?' );
	
					if ( false !== $pos ) {
						$menu_file = substr( $menu_file, 0, $pos );
					}
	
					// Handle current for post_type=post|page|foo pages, which won't match $self.
					$self_type = ! empty( $typenow ) ? $self . '?post_type=' . $typenow : 'nothing';
	
					if ( isset( $submenu_file ) ) {
						if ( $submenu_file === $sub_item[2] ) {
							$class[]          = 'current';
							$aria_attributes .= ' aria-current="page"';
						}
						// If plugin_page is set the parent must either match the current page or not physically exist.
						// This allows plugin pages with the same hook to exist under different parents.
					} elseif (
						( ! isset( $plugin_page ) && $self === $sub_item[2] )
						|| ( isset( $plugin_page ) && $plugin_page === $sub_item[2]
							&& ( $item[2] === $self_type || $item[2] === $self || file_exists( $menu_file ) === false ) )
					) {
						$class[]          = 'current';
						$aria_attributes .= ' aria-current="page"';
					}
	
					if ( ! empty( $sub_item[4] ) ) {
						$class[] = esc_attr( $sub_item[4] );
					}
	
					$class = $class ? ' class="' . implode( ' ', $class ) . '"' : '';
	
					$menu_hook = get_plugin_page_hook( $sub_item[2], $item[2] );
					$sub_file  = $sub_item[2];
					$pos       = strpos( $sub_file, '?' );
					if ( false !== $pos ) {
						$sub_file = substr( $sub_file, 0, $pos );
					}
	
					$title = wptexturize( $sub_item[0] );
					
					if ($aria_attributes != '') {
						$sub_item['active'] = true;
					}
	
					if ( ! empty( $menu_hook )
						|| ( ( 'index.php' !== $sub_item[2] )
							&& file_exists( WP_PLUGIN_DIR . "/$sub_file" )
							&& ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) )
					) {
						// If admin.php is the current page or if the parent exists as a file in the plugins or admin directory.
						if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$item[2]}" ) ) || file_exists( $menu_file ) ) {
							$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), $item[2] );
						} else {
							$sub_item_url = add_query_arg( array( 'page' => $sub_item[2] ), 'admin.php' );
						}
	
						$sub_item_url = esc_url( $sub_item_url );
						//echo "<li$class><a href='$sub_item_url'$class$aria_attributes>$title</a></li>";
						$sub_item['url'] = $sub_item_url;
					} else {
						//echo "<li$class><a href='{$sub_item[2]}'$class$aria_attributes>$title</a></li>";
						$sub_item['url'] = $sub_item[2];
					}
					
					array_push($tempsub, $sub_item );
				}
				//echo '</ul>';
			}
			//echo '</li>';
			$submenu_items = array();
			if ( ! empty( $submenu[ $item[2] ] ) ) {
				$returnsubmenu[$item[2]] = $tempsub;
			}
			
			array_push($returnmenu, $item);
		}
	
		//echo '<li id="collapse-menu" class="hide-if-no-js">' .
		//	'<button type="button" id="collapse-button" aria-label="' . esc_attr__( 'Collapse Main menu' ) . '" aria-expanded="true">' .
		//	'<span class="collapse-button-icon" aria-hidden="true"></span>' .
		//	'<span class="collapse-button-label">' . __( 'Collapse menu' ) . '</span>' .
		//	'</button></li>';
		$returndata['menu'] = $returnmenu;
		$returndata['submenu'] = $returnsubmenu;
		
		return $returndata;
	}
	
	/**
	* Gets correct classes for top level menu item
	* @since 1.4
	*/
	
	public function get_menu_clases($menu_item,$sub_menu){
		
		$menu_link = $menu_item[2];
		$classes = $menu_item[4];
		
		if(isset($sub_menu[$menu_link])){
			$classes = $classes . ' wp-has-submenu';
			if($menu_item ['active'] == true){
				$classes = $classes . ' ' . 'uk-active uk-open wp-menu-open wp-has-current-submenu';
			}
		} else {
			if($menu_item ['active'] == true){
				$classes = $classes . ' ' . 'uk-active current';
			}
			//$classes = $classes . ' ' . $this->check_if_single_active($menu_item);
		}
		
		return $classes;
		
	}
	
	/**
	* Checks if we are on an active link or sub link
	* @since 1.4
	*/
	
	public function check_if_active($menu_item,$sub_menu){
		
		if(!is_array($sub_menu)){
			return "";
		}
		
		if($menu_item['active'] == true){
			
		}
		
		global $pagenow;
		
		$currentquery = $_SERVER['QUERY_STRING'];
		if ($currentquery) {
			$currentquery = '?' . $currentquery;
		}
		$wholestring = $pagenow . $currentquery;
		$visibility = 'hidden';
		$open = 'wp-not-current-submenu';
		$files = $this->files;
		
		foreach ($sub_menu as $sub) {
			if (strpos($sub[2], '.php') !== false) {
				$link = $sub[2];

				$querypieces = explode("?", $link);
				$temp = $querypieces[0];

				if( !in_array( $temp ,$files )){
					$link = 'admin.php?page=' . $sub[2];
				}
				
			} else {
				$link = 'admin.php?page=' . $sub[2];
			}

			$linkclass = '';
			if ($wholestring == $link) {
				$linkclass = "wp-has-current-submenu wp-menu-open";
				$open = 'uk-active uk-open wp-menu-open wp-has-current-submenu';
				$visibility = '';
				break;
			}
		}
		
		return $open;
		
	}
	
	/**
	* Checks if we are on an active link or sub link
	* @since 1.4
	*/
	
	public function check_if_single_active($sub_menu_item){
		
		global $pagenow;
		
		$currentquery = $_SERVER['QUERY_STRING'];
		if ($currentquery) {
			$currentquery = '?' . $currentquery;
		}
		$wholestring = $pagenow . $currentquery;
		$visibility = 'hidden';
		$open = 'wp-not-current-submenu';
		$files = $this->files;
		
		if (strpos($sub_menu_item[2], '.php') !== false) {
			$link = $sub_menu_item[2];
	
			$querypieces = explode("?", $link);
			$temp = $querypieces[0];
	
			if( !in_array( $temp ,$files )){
				$link = 'admin.php?page=' . $sub_menu_item[2];
			}
			
		} else {
			$link = 'admin.php?page=' . $sub_menu_item[2];
		}
	
		$linkclass = '';
		if ($wholestring == $link) {
			$linkclass = "uk-active current";
		}
		
		
		return $linkclass;
	
	}
	
	/**
	* Builds nav dividers
	* @since 1.4
	*/
	
	public function handle_divider($divider){
		
		
		if(isset($divider['name'])){
			
			?>
			
			<li class="uk-nav-header uk-text-bold uk-margin-small-bottom" style="text-transform: none"><?php echo $divider['name'] ?></li>
			<li class="uk-nav-divider divider-placeholder"></li>
			
			<?php
			
		} else {
			?>
			
			<li class="uk-nav-divider"></li>
			
			<?php
		}
		
	}
	
	/**
	* Gets top level menu item icon
	* @since 1.4
	*/
	
	public function get_icon($menu_item){
		
		/// LIST OF AVAILABLE MENU ICONS
		$icons = array('dashicons-dashboard' => 'grid_view',
		'dashicons-admin-post' => 'article',
		'dashicons-database' => 'perm_media',
		'dashicons-admin-media' => 'collections',
		'dashicons-admin-page' => 'description',
		'dashicons-admin-comments' => 'forum',
		'dashicons-admin-appearance' => 'palette',
		'dashicons-admin-plugins' => 'extension',
		'dashicons-admin-users' => 'people',
		'dashicons-admin-tools' => 'build_circle',
		'dashicons-chart-bar' => 'analytics',
		'dashicons-admin-settings' => 'tune');
		
		// SET MENU ICON
		$theicon = '';
		$wpicon = $menu_item[6];
		
		if(isset($menu_item['icon'])){
			if($menu_item['icon'] != "" ){
				
				ob_start();
				?><span class="uk-icon-button" uk-icon="icon:<?php echo $menu_item['icon'] ?>;ratio:0.8"></span><?php 
				return ob_get_clean();
			}
		}

		if(isset($icons[$wpicon])){
		
			//ICON IS SET BY ADMIN 2020		
			ob_start();
			?><span class="material-icons-outlined a2020-menu-icon"><?php echo $icons[$wpicon] ?></span><?php
			return ob_get_clean();
			
		}

		if (!$theicon) {
			if (strpos($wpicon, 'http') !== false || strpos($wpicon, 'data:') !== false) {
				
				///ICON IS IMAGE 
				ob_start();
				?><span class="uk-icon uk-icon-image uk-icon-button" style="background-image: url(<?php echo $wpicon ?>);"></span><?php
				return ob_get_clean();
				
			} else {
				
				///ICON IS ::BEFORE ELEMENT
				ob_start();
				?><div class="wp-menu-image dashicons-before <?php echo $wpicon ?> uk-icon uk-icon-image uk-icon-button"></div><?php
				return ob_get_clean();
				
			}
		}
		
	}
	
	
	/**
	* Loops through sub menu items and returns object
	* @since 2.0.9
	*/
	
	public function build_sub_level_menu_items($sub_menu, $parentid){
		
		$returnSub = array();
			
		foreach ($sub_menu as $sub_item){
			
			$tempsub = array();
			$class = '';
			
			$sub_menu_name = $sub_item[0]; 
			
			$sub_menu_link = $sub_item[2];
			$link = $sub_item['url'];
			
			
			if($sub_item['active']){
				$class = 'uk-active current';
			}
			
			
			$tempsub['name'] = $sub_menu_name; 
			$tempsub['classes'] = $class;
			$tempsub['href'] = $link;
			$tempsub['type'] = 'submenu';
			$tempsub['id'] = $parentid . $link;
			
			$returnSub[] = $tempsub;
			
		}
		
		return $returnSub;
		
	} 
	
	
	
	/**
	* Outputs Admin menu
	* @since 1.4
	*/
	
	public function output_admin_menu(){
		
		global $admin2020_menu,$menu,$submenu;
		echo $admin2020_menu;
		$menu = $this->original_menu;
		$submenu = $this->original_submenu;
		
	}
	
	
}
