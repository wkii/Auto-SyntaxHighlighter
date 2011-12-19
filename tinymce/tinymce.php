<?php

class AshButton
{
	public $pluginname = "ash";

	public function __construct()
	{
		add_action('init', array($this,'addButtons'));
	}

	function addButtons()
	{
		// Don't bother doing this stuff if the current user lacks permissions
		if (!is_user_logged_in() || ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) )
			return;
		
		// Add only in Rich Editor mode
		if (get_user_option('rich_editing') == true)
		{
			// add the button for wp2.5 in a new way
			add_filter("mce_external_plugins", array($this, 'addTinymcePlugin'));
			add_filter('mce_buttons', array($this, 'registerButton'));
			
			// Load style for fullscreen mode for wp 3.2+
			if (version_compare(get_bloginfo('version'), "3.2", ">="))
			{
				add_filter('wp_fullscreen_buttons', array($this,'addFullscreenButton'));
				add_action('admin_print_styles-post.php', array($this, 'wpAshEditStyle'));
				add_action('admin_print_styles-post-new.php', array($this, 'wpAshEditStyle'));
				add_action('admin_print_styles-page.php', array($this, 'wpAshEditStyle'));
				add_action('admin_print_styles-page-new.php', array($this, 'wpAshEditStyle'));
				
				
			}
		}
		
	}

	// used to insert button in wordpress 2.5x editor
	function registerButton($buttons)
	{
		array_push($buttons, 'separator', $this->pluginname);
		return $buttons;
	}

	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function addTinymcePlugin($plugin_array)
	{
		$plugin_array[$this->pluginname] = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)) . '/ash/editor_plugin.js';
		return $plugin_array;
	}
	
	// To fullscreen mode
	function addFullscreenButton($buttons)
	{
		$buttons[] = 'separator';
		$buttons[$this->pluginname] = array(
			'title' => __('Auto SyntaxHighlighter CodeBox', 'wp_'.$this->pluginname),
			'onclick' => "tinyMCE.execCommand('mceash');",
			'both' => false); // if true to use fullscreen mce and html
		return $buttons;
	}
	
	public function wpAshEditStyle()
	{
		wp_enqueue_style('ash-editor', plugins_url('/auto-syntaxhighlighter/tinymce/ash/ash_fullscreen.css'), false, ASH_VERSION);
	}

}

// Call it now
$tinymce_button = new AshButton;