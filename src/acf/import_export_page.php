<?php

if (! defined('ABSPATH')) {
    return;
} // Exit if accessed directly

class yt_import_export
{
	private $plugin_path;

	public $wp_options_search = '%options_yt_auth%';



    public function __construct()
    {
        $this->plugin_path 	  = plugin_dir_path(__FILE__);
        
        add_action('admin_menu', array( $this, 'admin_menu' ));
        add_action('admin_init', array( $this, 'import_options' ));
        add_action('admin_init', array( $this, 'exports_options' ));
    }


    public function admin_menu()
    {
        $page_hook_suffix = add_submenu_page(
            'edit.php?post_type=youtube',
            esc_html__('YouTube import/export', 'acfim-plugin'),
            esc_html__('YouTube import/export', 'acfim-plugin'),
            'manage_options',
            'acf-import-export',
            array($this, 'admin_page' )
        );
    }


    public function admin_page()
    {
        $plugin_url = menu_page_url('acf-import-export', false); ?>
		<div class="wrap">
			<h2><?php esc_html_e('YouTube Scrape options import/export', 'acfim-plugin'); ?></h2>

			<div class="acfim-container">
				<?php if (! function_exists('get_field')) { ?>
					<p><?php esc_html_e('Please install Advanced Custom Fields plugin', 'acfim-plugin'); ?></p>
				<?php } else { ?>
					<div class="acfim-left">
						<div class="acfim-section">
							<h3><?php esc_html_e('Export options', 'acfim-plugin'); ?></h3>
							<a href="<?php echo $plugin_url ?>&export" class="button"><?php esc_html_e('Run Export', 'acfim-plugin'); ?></a>
						</div>
						<div class="acfim-section">
							<form method="post" enctype="multipart/form-data" action="<?php echo $plugin_url ?>&import">						
								<h3><?php esc_html_e('Import options', 'acfim-plugin'); ?></h3>
								<input type="file" name="backup" required />
								<?php submit_button(esc_html__('Upload file and import', 'acfim-plugin')); ?>
								<?php if (isset($_GET['imported']) && $_GET['imported'] == 1): ?>
									<p><?php esc_html_e('Options successfully imported.', 'acfim-plugin'); ?></p>
								<?php endif ?>
								<?php if (isset($_GET['imported']) && $_GET['imported'] == 2): ?>
									<p><?php esc_html_e('Some error happened during the upload process.', 'acfim-plugin'); ?></p>
								<?php endif ?>
								<p class="acfim-info"><?php esc_attr_e('Your current options will be overwritten.', 'acfim-plugin'); ?></p>
							</form>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
    }

    

    public function exports_options()
    {
        if (isset($_GET['export']) && isset($_GET['page']) && $_GET['page'] == 'acf-import-export') {

			// list of all _options
            $acf_fields = $this->export_from_db();

            $json = '';
            $json = json_encode($acf_fields);

            file_put_contents($this->plugin_path . '/temp/options.json', $json);
            $file       = $this->plugin_path . '/temp/options.json';

			$this->send_to_file($file);
			
			$this->delete_files();

			exit;
        }
    }



    public function export_from_db()
    {
        global $wpdb;

        $acf_fields = $wpdb->get_results($wpdb->prepare(
            "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE %s", $this->wp_options_search
        ));

        return $acf_fields;
    }




    public function send_to_file($filename)
    {
        if (file_exists($filename)) {

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
            // exit;
        }
	}
	


	public function delete_files()
	{
		$files = glob($this->plugin_path . '/temp/*');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
	}



	public function import_options()
    {
        if (isset($_GET['import']) && isset($_GET['page']) && $_GET['page'] == 'acf-import-export') {

			$plugin_url = menu_page_url('acf-import-export', false);
			
            if (! empty($_FILES['backup'])) {


                $target_dir  = $this->plugin_path . 'temp/';
				$target_file = $target_dir . basename($_FILES['backup']['name']);
				
                if (move_uploaded_file($_FILES['backup']['tmp_name'], $target_file)) {

                    WP_Filesystem();
						
                    if (is_wp_error($target_file)) { wp_redirect($plugin_url . '&imported=2');  die(); }
                    if (! file_exists($this->plugin_path . '/temp/options.json')) { wp_redirect($plugin_url . '&imported=2');  die(); }

                    $json 	  = file_get_contents($this->plugin_path . '/temp/options.json');
                    $options  = json_decode($json, true);

                    foreach ($options as $option) {
                        $this->insert_option($option);
					}

					$this->delete_files();
                }
			}
			
            // unzip_file()
            wp_redirect($plugin_url . '&imported=1');
            die();
        }
	}
	
	

	public function insert_option($option)
	{
		if(!get_option($option['option_name'])){
			update_option($option['option_name'], $option['option_value'], 'no');
		}
	}

}

new yt_import_export();
