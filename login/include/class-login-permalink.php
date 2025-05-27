<?php
/*
 * @Author: Amirhossein Hosseinpour <https://amirhp.com>
 * @Date Created: 2023/02/06 01:05:22
 * @Last modified by: amirhp-com <its@amirhp.com>
 * @Last modified time: 2025/05/28 03:00:34
 * @Based on: PeproDev Ultimate Profile Solutions - 1.9.17.2
 * @Based on: https://wordpress.org/plugins/peprodev-ups/
*/

// don't load directly
if (! defined('ABSPATH')) { die('-1'); }
define('WPS_HIDE_LOGIN_VERSION', '1.9.17.2');
define('WPS_HIDE_LOGIN_URL', plugin_dir_url(__FILE__));
define('WPS_HIDE_LOGIN_DIR', plugin_dir_path(__FILE__));
define('WPS_HIDE_LOGIN_BASENAME', plugin_basename(__FILE__));

if (defined('ABSPATH') && ! class_exists('WPS_Hide_Login')) {
	class WPS_Hide_Login {
		private $wp_login_php;

		public function __construct() {
			global $wp_version;


			if (is_multisite()) {
				add_action('wp_before_admin_bar_render', array($this, 'modify_mysites_menu'), 999);
			}

			add_action('admin_init', array($this, 'admin_init'));
			add_action('plugins_loaded', array($this, 'plugins_loaded'), 9999);
			add_action('admin_notices', array($this, 'admin_notices'));
			add_action('network_admin_notices', array($this, 'admin_notices'));
			add_action('wp_loaded', array($this, 'wp_loaded'));
			add_action('setup_theme', array($this, 'setup_theme'), 1);

			add_filter('site_url', array($this, 'site_url'), 10, 4);
			add_filter('network_site_url', array($this, 'network_site_url'), 10, 3);
			add_filter('wp_redirect', array($this, 'wp_redirect'), 10, 2);
			add_filter('site_option_welcome_email', array($this, 'welcome_email'));

			remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);

			add_action('template_redirect', array($this, 'redirect_export_data'));
			add_filter('login_url', array($this, 'login_url'), 10, 3);
			add_filter('user_request_action_email_content', array($this, 'user_request_action_email_content'), 999, 2);
			add_filter('site_status_tests', array($this, 'site_status_tests'));
			add_filter('manage_sites_action_links', array($this, 'manage_sites_action_links'), 10, 3);
		}

		public function site_status_tests($tests) {
			unset($tests['async']['loopback_requests']);
			return $tests;
		}

		public function user_request_action_email_content($email_text, $email_data) {
			$email_text = str_replace('###CONFIRM_URL###', esc_url_raw(str_replace($this->new_login_slug() . '/', 'wp-login.php', $email_data['confirm_url'])), $email_text);
			return $email_text;
		}

		private function use_trailing_slashes() {
			return ('/' === substr(get_option('permalink_structure'), -1, 1));
		}

		private function user_trailingslashit($string) {

			return $this->use_trailing_slashes() ? trailingslashit($string) : untrailingslashit($string);
		}

		private function wp_template_loader() {

			global $pagenow;

			$pagenow = 'index.php';

			if (! defined('WP_USE_THEMES')) {

				define('WP_USE_THEMES', true);
			}

			wp();

			require_once(ABSPATH . WPINC . '/template-loader.php');

			die;
		}

		public function modify_mysites_menu() {
			global $wp_admin_bar;
			$all_toolbar_nodes = $wp_admin_bar->get_nodes();
			foreach ($all_toolbar_nodes as $node) {
				if (preg_match('/^blog-(\d+)(.*)/', $node->id, $matches)) {
					$blog_id = $matches[1];
					if ($login_slug = $this->new_login_slug($blog_id)) {
						if (! $matches[2] || '-d' === $matches[2]) {
							$args       = $node;
							$old_href   = $args->href;
							$args->href = preg_replace('/wp-admin\/$/', "$login_slug/", $old_href);
							if ($old_href !== $args->href) {
								$wp_admin_bar->add_node($args);
							}
						} elseif (strpos($node->href, '/wp-admin/') !== false) {
							$wp_admin_bar->remove_node($node->id);
						}
					}
				}
			}
		}

		private function new_login_slug($blog_id = '') {
			if ($blog_id) {
				if ($slug = get_blog_option($blog_id, 'whl_page')) {
					return $slug;
				}
			} else {
				if ($slug = get_option('whl_page')) {
					return $slug;
				} else if ((is_multisite() && ($slug = get_site_option('whl_page', 'login')))) {
					return $slug;
				} else if ($slug = 'login') {
					return $slug;
				}
			}
		}

		private function new_redirect_slug() {
			if ($slug = get_option('whl_redirect_admin')) {
				return $slug;
			} else if ((is_multisite() && ($slug = get_site_option('whl_redirect_admin', '404')))) {
				return $slug;
			} else if ($slug = '404') {
				return $slug;
			}
		}

		public function new_login_url($scheme = null) {

			$url = apply_filters('wps_hide_login_home_url', home_url('/', $scheme));

			if (get_option('permalink_structure')) {

				return $this->user_trailingslashit($url . $this->new_login_slug());
			} else {

				return $url . '?' . $this->new_login_slug();
			}
		}

		public function new_redirect_url($scheme = null) {

			if (get_option('permalink_structure')) {

				return $this->user_trailingslashit(home_url('/', $scheme) . $this->new_redirect_slug());
			} else {

				return home_url('/', $scheme) . '?' . $this->new_redirect_slug();
			}
		}

		/**
		 * Plugin activation
		 */
		public static function activate() {
			//add_option( 'whl_redirect', '1' );

			do_action('wps_hide_login_activate');
		}

		public function admin_init() {
			global $pagenow;
			?>
			<style>
			.peprodev-profile-edit {
				padding: 1rem;
				background: #fbf6f2;
				border-radius: 0.5rem;
				margin: 1rem 0;
				border: 2px solid orange;
				position: relative;
			}
			.peprodev-profile-edit h3 {
				margin: 0 auto 1rem auto;
				font-weight: 800;
				font-size: large;
			}
			.peprodev-profile-edit::after {
				position: absolute;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				content: "";
				background: url('<?php echo plugins_url("/core/assets/", dirname(dirname(__FILE__))) . "img/peprodev.svg";?>') no-repeat 100% center / 41%;
				pointer-events: none;
				z-index: 0;
				filter: opacity(0.1);
			}
			</style>
			<?php
			add_settings_section(
				'peprodev-ups-section',
				"<h2 style='margin-bottom: 0.5rem;'>" . __('PeproDev Ultimate Profile Solutions', 'peprodev-ups') . "</h2><i>" . sprintf(__("This setting is provieded by %s.", "peprodev-ups"), "<a target='_blank' href='".admin_url("admin.php?page=peprodev-ups&section=loginregister#tab_wp_login")."'>".__("PeproDev Ultimate Profile Solutions", "peprodev-ups")."</a>") . "</i>",
				array($this, 'whl_section_desc'),
				'general',
				[
					'before_section' => '<div class="peprodev-profile-edit">',
					'after_section' => '</div>',
				]
			);
			add_settings_field(
				'whl_page',
				'<label for="whl_page">' . __('Login url', 'peprodev-ups') . '</label>',
				array($this, 'whl_page_input'),
				'general',
				'peprodev-ups-section'
			);
			add_settings_field(
				'whl_redirect_admin',
				'<label for="whl_redirect_admin">' . __('Redirection url', 'peprodev-ups') . '</label>',
				array($this, 'whl_redirect_admin_input'),
				'general',
				'peprodev-ups-section'
			);
			register_setting('general', 'whl_page', 'sanitize_title_with_dashes');
			register_setting('general', 'whl_redirect_admin', 'sanitize_title_with_dashes');

			if (get_option('whl_redirect')) {
				delete_option('whl_redirect');
				if ( is_multisite() && is_super_admin() ) {
					$redirect = network_admin_url('settings.php#whl_settings');
				} else {
					$redirect = admin_url('options-general.php#whl_settings');
				}

				wp_safe_redirect($redirect);
				die();
			}
		}

		public function whl_section_desc() {
			$out = '';
			if ( is_multisite() && is_super_admin() ) {
				$out .= '<p>' . sprintf(__('To set a networkwide default, go to <a href="%s">Network Settings</a>.', 'peprodev-ups'), network_admin_url('settings.php#whl_settings')) . '</p>';
			}
			echo $out;
		}

		public function whl_page_input() {

			if (get_option('permalink_structure')) {
				echo '<code>' . trailingslashit(home_url()) . '</code> <input id="whl_page" type="text" name="whl_page" value="' . $this->new_login_slug() . '">' . ($this->use_trailing_slashes() ? ' <code>/</code>' : '');
			} else {
				echo '<code>' . trailingslashit(home_url()) . '?</code> <input id="whl_page" type="text" name="whl_page" value="' . $this->new_login_slug() . '">';
			}

			echo '<p class="description">' . __('Protect your website by changing the login URL and preventing access to the wp-login.php page and the wp-admin directory to non-connected people.', 'peprodev-ups') . '</p>';
		}

		public function whl_redirect_admin_input() {
			if (get_option('permalink_structure')) {

				echo '<code>' . trailingslashit(home_url()) . '</code> <input id="whl_redirect_admin" type="text" name="whl_redirect_admin" value="' . $this->new_redirect_slug() . '">' . ($this->use_trailing_slashes() ? ' <code>/</code>' : '');
			} else {

				echo '<code>' . trailingslashit(home_url()) . '?</code> <input id="whl_redirect_admin" type="text" name="whl_redirect_admin" value="' . $this->new_redirect_slug() . '">';
			}
			echo '<p class="description">' . __('Redirect URL when someone tries to access the wp-login.php page and the wp-admin directory while not logged in.', 'peprodev-ups') . '</p>';
		}

		public function admin_notices() {
			global $pagenow;
			if ( ! is_network_admin() && $pagenow === 'options-general.php' && isset($_GET['settings-updated']) && ! isset($_GET['page']) ) {
				echo '<div class="updated notice is-dismissible"><p>' . sprintf(__('Your login page is now here: <strong><a href="%1$s">%2$s</a></strong>. Bookmark this page!', 'peprodev-ups'), $this->new_login_url(), $this->new_login_url()) . '</p></div>';
			}
		}

		public function redirect_export_data() {
			if (! empty($_GET) && isset($_GET['action']) && 'confirmaction' === $_GET['action'] && isset($_GET['request_id']) && isset($_GET['confirm_key'])) {
				$request_id = (int) $_GET['request_id'];
				$key        = sanitize_text_field(wp_unslash($_GET['confirm_key']));
				$result     = wp_validate_user_request_key($request_id, $key);
				if (! is_wp_error($result)) {
					wp_redirect(add_query_arg(
						array(
							'action'      => 'confirmaction',
							'request_id'  => $_GET['request_id'],
							'confirm_key' => $_GET['confirm_key']
						),
						$this->new_login_url()
					));
					exit();
				}
			}
		}

		public function plugins_loaded() {

			global $pagenow;

			if (
				! is_multisite()
				&& (strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-signup') !== false
					|| strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-activate') !== false) && apply_filters('wps_hide_login_signup_enable', false) === false
			) {

				wp_die(__('This feature is not enabled.', 'peprodev-ups'));
			}

			$request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));

			if ((strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-login.php') !== false
					|| (isset($request['path']) && untrailingslashit($request['path']) === site_url('wp-login', 'relative')))
				&& ! is_admin()
			) {

				$this->wp_login_php = true;

				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/' . str_repeat('-/', 10));

				$pagenow = 'index.php';
			} elseif ((isset($request['path']) && untrailingslashit($request['path']) === home_url($this->new_login_slug(), 'relative'))
				|| (! get_option('permalink_structure')
					&& isset($_GET[$this->new_login_slug()])
					&& empty($_GET[$this->new_login_slug()]))
			) {

				$_SERVER['SCRIPT_NAME'] = $this->new_login_slug();

				$pagenow = 'wp-login.php';
			} elseif ((strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-register.php') !== false
					|| (isset($request['path']) && untrailingslashit($request['path']) === site_url('wp-register', 'relative')))
				&& ! is_admin()
			) {

				$this->wp_login_php = true;

				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit('/' . str_repeat('-/', 10));

				$pagenow = 'index.php';
			}
		}

		public function setup_theme() {
			global $pagenow;

			if (! is_user_logged_in() && 'customize.php' === $pagenow) {
				wp_die(__('This has been disabled', 'peprodev-ups'), 403);
			}
		}

		public function wp_loaded() {
			global $pagenow;

			$request = parse_url(rawurldecode($_SERVER['REQUEST_URI']));

			do_action('wps_hide_login_before_redirect', $request);

			if (! (isset($_GET['action']) && $_GET['action'] === 'postpass' && isset($_POST['post_password']))) {

				if (is_admin() && ! is_user_logged_in() && ! defined('WP_CLI') && ! defined('DOING_AJAX') && ! defined('DOING_CRON') && $pagenow !== 'admin-post.php' && $request['path'] !== '/wp-admin/options.php') {
					wp_safe_redirect($this->new_redirect_url());
					die();
				}

				if (! is_user_logged_in() && isset($_GET['wc-ajax']) && $pagenow === 'profile.php') {
					wp_safe_redirect($this->new_redirect_url());
					die();
				}

				if (! is_user_logged_in() && isset($request['path']) && $request['path'] === '/wp-admin/options.php') {
					header('Location: ' . $this->new_redirect_url());
					die;
				}

				if ($pagenow === 'wp-login.php' && isset($request['path']) && $request['path'] !== $this->user_trailingslashit($request['path']) && get_option('permalink_structure')) {
					wp_safe_redirect($this->user_trailingslashit($this->new_login_url())
						. (! empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

					die;
				} elseif ($this->wp_login_php) {

					if (($referer = wp_get_referer())
						&& strpos($referer, 'wp-activate.php') !== false
						&& ($referer = parse_url($referer))
						&& ! empty($referer['query'])
					) {

						parse_str($referer['query'], $referer);

						@require_once WPINC . '/ms-functions.php';

						if (
							! empty($referer['key'])
							&& ($result = wpmu_activate_signup($referer['key']))
							&& is_wp_error($result)
							&& ($result->get_error_code() === 'already_active'
								|| $result->get_error_code() === 'blog_taken')
						) {

							wp_safe_redirect($this->new_login_url()
								. (! empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));

							die;
						}
					}

					$this->wp_template_loader();
				} elseif ($pagenow === 'wp-login.php') {
					global $error, $interim_login, $action, $user_login;

					$redirect_to = admin_url();

					$requested_redirect_to = '';
					if (isset($_REQUEST['redirect_to'])) {
						$requested_redirect_to = $_REQUEST['redirect_to'];
					}

					if (is_user_logged_in()) {
						$user = wp_get_current_user();
						if (! isset($_REQUEST['action'])) {
							$logged_in_redirect = apply_filters('whl_logged_in_redirect', $redirect_to, $requested_redirect_to, $user);
							wp_safe_redirect($logged_in_redirect);
							die();
						}
					}

					@require_once ABSPATH . 'wp-login.php';

					die;
				}
			}
		}

		public function site_url($url, $path, $scheme, $blog_id) {
			return $this->filter_wp_login_php($url, $scheme);
		}

		public function network_site_url($url, $path, $scheme) {
			return $this->filter_wp_login_php($url, $scheme);
		}

		public function wp_redirect($location, $status) {
			if (strpos($location, 'https://wordpress.com/wp-login.php') !== false) {
				return $location;
			}

			return $this->filter_wp_login_php($location);
		}

		public function filter_wp_login_php($url, $scheme = null) {
			global $pagenow;

			$origin_url = $url;

			if (strpos($url, 'wp-login.php?action=postpass') !== false) {
				return $url;
			}

			if (is_multisite() && 'install.php' === $pagenow) {
				return $url;
			}

			/*if ( strpos( $url, 'wp-admin/?action=postpass' ) === false ) {
			return $url;
		}*/

			if (strpos($url, 'wp-login.php') !== false && strpos(wp_get_referer(), 'wp-login.php') === false) {

				if (is_ssl()) {
					$scheme = 'https';
				}

				$args = explode('?', $url);

				if (isset($args[1])) {

					parse_str($args[1], $args);

					if (isset($args['login'])) {
						$args['login'] = rawurlencode($args['login']);
					}

					$url = add_query_arg($args, $this->new_login_url($scheme));
				} else {

					$url = $this->new_login_url($scheme);
				}
			}

			if (isset($_POST['post_password'])) {
				global $current_user;
				if (! is_user_logged_in() && is_wp_error(wp_authenticate_username_password(null, $current_user->user_login, $_POST['post_password']))) {
					return $origin_url;
				}
			}

			if (! is_user_logged_in()) {
				if (file_exists(WP_CONTENT_DIR . '/plugins/gravityforms/gravityforms.php') && isset($_GET['gf_page'])) {
					return $origin_url;
				}
			}

			return $url;
		}

		public function welcome_email($value) {
			return $value = str_replace('wp-login.php', trailingslashit(get_site_option('whl_page', 'login')), $value);
		}

		public function forbidden_slugs() {

			$wp = new \WP;

			return array_merge($wp->public_query_vars, $wp->private_query_vars);
		}

		/**
		 *
		 * Update url redirect : wp-admin/options.php
		 *
		 * @param $login_url
		 * @param $redirect
		 * @param $force_reauth
		 *
		 * @return string
		 */
		public function login_url($login_url, $redirect, $force_reauth) {
			if (is_404()) {
				return '#';
			}

			if ($force_reauth === false) {
				return $login_url;
			}

			if (empty($redirect)) {
				return $login_url;
			}

			$redirect = explode('?', $redirect);

			if ($redirect[0] === admin_url('options.php')) {
				$login_url = admin_url();
			}

			return $login_url;
		}


		/**
		 * Load scripts
		 */
		public function admin_enqueue_scripts_notifs($hook) {

			/*if ( 'options-general.php' != $hook ) {
			return false;
		}*/

			wp_enqueue_script('peprodev-ups-functions', WPS_HIDE_LOGIN_URL . 'assets/js/functions.js', array(
				'jquery',
			), false, true);

			wp_localize_script(
				'peprodev-ups-functions',
				'dismissible_notice',
				array(
					'nonce' => wp_create_nonce('peprodev-ups-dismissible-notice'),
				)
			);
		}

		/**
		 * Is admin notice active?
		 *
		 * @param string $arg data-dismissible content of notice.
		 *
		 * @return bool
		 */
		public static function is_admin_notice_active($arg) {
			$array       = explode('-', $arg);
			$option_name = implode('-', $array);
			$db_record   = get_site_transient($option_name);

			if ('forever' == $db_record) {
				return false;
			} elseif (absint($db_record) >= time()) {
				return false;
			} else {
				return true;
			}
		}

		public function manage_sites_action_links($actions, $blog_id, $blogname) {

			$actions['backend'] = sprintf(
				'<a href="%1$s" class="edit">%2$s</a>',
				esc_url(get_site_url($blog_id, $this->new_login_slug())),
				__('Dashboard')
			);

			return $actions;
		}
	}
	add_action("plugins_loaded", function () { new WPS_Hide_Login; });
}
