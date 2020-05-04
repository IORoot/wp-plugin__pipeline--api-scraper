<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

if ( ! class_exists( 'ACFIM_plugin' ) ) {
	class ACFIM_plugin {

		private $plugin_url;

		private $plugin_path;

		private $plugin_version;


		function __construct() {
			$this->plugin_url 	  = plugins_url( '', __FILE__ );
			$this->plugin_path 	  = plugin_dir_path( __FILE__ );
			$this->plugin_version = '1.0.0';
			
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'import_options' ) );
			add_action( 'admin_init', array( $this, 'exports_options' ) );
		}


		public function admin_menu() {

			$page_hook_suffix = add_submenu_page( 
				'edit.php?post_type=youtube', 
				esc_html__( 'YouTube import/export', 'acfim-plugin' ), 
				esc_html__( 'YouTube import/export', 'acfim-plugin' ), 
				'manage_options', 
				'acf-import-export', 
				array($this, 'admin_page' ) 
			); 
			
		}


		public function admin_page() {
			$plugin_url = menu_page_url( 'acf-import-export', false );
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'YouTube Scrape options import/export', 'acfim-plugin' ); ?></h2>

				<div class="acfim-container">
					<?php if ( ! function_exists( 'get_field' ) ) { ?>
						<p><?php esc_html_e( 'Please install Advanced Custom Fields plugin', 'acfim-plugin' ); ?></p>
					<?php } else { ?>
						<div class="acfim-left">
							<div class="acfim-section">
								<h3><?php esc_html_e( 'Export options', 'acfim-plugin' ); ?></h3>
								<a href="<?php echo $plugin_url ?>&export" class="button"><?php esc_html_e( 'Run Export', 'acfim-plugin' ); ?></a>
							</div>
							<div class="acfim-section">
								<form method="post" enctype="multipart/form-data" action="<?php echo $plugin_url ?>&import">						
									<h3><?php esc_html_e( 'Import options', 'acfim-plugin' ); ?></h3>
									<input type="file" name="backup" required />
									<?php submit_button( esc_html__( 'Upload file and import', 'acfim-plugin' ) ); ?>
									<?php if ( isset( $_GET['imported'] ) && $_GET['imported'] == 1 ): ?>
										<p><?php esc_html_e( 'Options successfully imported.', 'acfim-plugin' ); ?></p>
									<?php endif ?>
									<?php if ( isset( $_GET['imported'] ) && $_GET['imported'] == 2 ): ?>
										<p><?php esc_html_e( 'Some error happened during the upload process.', 'acfim-plugin' ); ?></p>
									<?php endif ?>
									<p class="acfim-info"><?php esc_attr_e( 'Your current options will be overwritten.', 'acfim-plugin' ); ?></p>
								</form>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
		}


		public function import_options() {
			if ( isset( $_GET['import'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'acf-import-export' ) {

				$plugin_url = menu_page_url( 'acf-import-export', false );
				if ( ! empty( $_FILES['backup'] ) ) {
					$target_dir  = $this->plugin_path . '/temp/';
					$target_file = $target_dir . basename( $_FILES['backup']['name'] );
					if ( move_uploaded_file( $_FILES['backup']['tmp_name'], $target_file ) ) {
						WP_Filesystem();
						
						$unzip = unzip_file( $target_file, $target_dir );
						unlink( $target_file );

						if ( is_wp_error( $unzip ) || ! file_exists( $this->plugin_path . '/temp/images.json' ) || ! file_exists( $this->plugin_path . '/temp/options.json' ) ) {
							wp_redirect( $plugin_url . '&imported=2' );
							die();
						}

						$json 	  = file_get_contents( $this->plugin_path . '/temp/options.json' );
						$options  = json_decode( $json, true );
						$img_json = file_get_contents( $this->plugin_path . '/temp/images.json' );
						$img_data = array();

						if ( ! empty( $img_json ) ) {
							$img_data = json_decode( $img_json, true );
						}

						if ( ! empty( $img_data ) ) {
							$upload_dir = wp_upload_dir();
							require_once ABSPATH . 'wp-admin/includes/image.php';
							foreach ( $img_data as $key => $image ) {

								$image_url  = $this->plugin_path . '/temp/' . $image['name'];
								$image_data = file_get_contents( $image_url );
								$filename   = basename( $image_url );

								if ( wp_mkdir_p( $upload_dir['path'] ) ) {
									$file = $upload_dir['path'] . '/' . $filename;
								} else {
									$file = $upload_dir['basedir'] . '/' . $filename;
								}

								file_put_contents( $file, $image_data );

								$wp_filetype = wp_check_filetype( $filename, null );

								$attachment = array(
									'post_mime_type' => $wp_filetype['type'],
									'post_title' 	 => sanitize_file_name( $filename ),
									'post_content' 	 => '',
									'post_status' 	 => 'inherit'
								);

								$attach_id = wp_insert_attachment( $attachment, $file );
								$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
								wp_update_attachment_metadata( $attach_id, $attach_data );

								$img_data[ $key ]['new_id'] = $attach_id;
							}
						}
						
						foreach ( $options as $key => $option ) {
							update_option( '_options_' . $key, $options[ $key ]['key'], 'no' );
							update_option( 'options_' . $key, $options[ $key ]['value'], 'no' );
						}

						$files = glob( $this->plugin_path . '/temp/*' );
						foreach( $files as $file ) {
							if( is_file( $file ) ){
								unlink( $file );
							}
						}
					}
				}
				// unzip_file()
				wp_redirect( $plugin_url . '&imported=1' );
				die();
			}
		}
		
		


		public function exports_options() {
			if ( isset( $_GET['export'] ) && isset( $_GET['page'] ) && $_GET['page'] == 'acf-import-export' ) {	
				$plugin_url = menu_page_url( 'acf-import-export', false );
				$opts = array();
				$imgs = array();

				global $wpdb;
				$keys = $wpdb->get_col($wpdb->prepare(
					"SELECT option_value FROM $wpdb->options WHERE option_name LIKE %s",
					'_options_yt_auth%' 
				));

				foreach ($keys as $key => $option) {
					$opt_data = get_field_object($option, 'options');
					if ( is_array( $opt_data ) ) {
						$option_value = get_option( 'options_' . $opt_data['_name'] );


						$opts[$key][ $opt_data['_name'] ] = array(
							'key' => $opt_data['key'],
							'type' => $opt_data['type'],
							'value' => $option_value
						); 

					}
				}

				$json = '';
				$json = json_encode( $opts );
				file_put_contents( $this->plugin_path . '/temp/options.json', $json );
				$file       = $this->plugin_path . '/temp/options.json';

				$this->send_to_file($file);

			}
		}


		public function send_to_file( $filename ) {
			if(file_exists($filename)){

				//Get file type and set it as Content Type
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				header('Content-Type: ' . finfo_file($finfo, $filename));
				finfo_close($finfo);
			
				//Use Content-Disposition: attachment to specify the filename
				header('Content-Disposition: attachment; filename='.basename($filename));
			
				//No cache
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
			
				//Define file size
				header('Content-Length: ' . filesize($filename));
			
				ob_clean();
				flush();
				readfile($filename);
				exit;
			}
		}



		public function get_files( $plugin_or_theme_path ) {
			$files       = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $plugin_or_theme_path ), RecursiveIteratorIterator::LEAVES_ONLY );
			$files_paths = array();
			foreach ( $files as $name => $file ) {
				if ( ! $file->isDir() ) {
					$file_path = str_replace( '\\', '/', $file->getRealPath() );
					$files_paths[] = $file_path;
				}
			}
			return $files_paths;
		}
	}
}
new ACFIM_plugin();