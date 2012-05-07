<?php
/*
 Plugin Name: Auto SyntaxHighlighter
 Plugin URI: http://www.akii.org/auto-syntaxhighlighter.html
 Description: Autoload SyntaxHighlighter only requisite js files and display you code. Uses Alex Gorbatchev's SyntaxHighlighter: <a href="http://alexgorbatchev.com/SyntaxHighlighter/manual/brushes/">SyntaxHighlighter Brushes</a>. Full Support for : AppleScript, ActionScript3, Bash/shell, C#, C++, CSS, Delphi, Diff, Groovy, JavaScript, Java, Perl, PHP, Plain Text, Python, Ruby, Sass, Scala, SQL, Visual Basic and XML/HTML...
 Version: 2.3.3
 Author: digihero
 Author URI: http://www.akii.org
 */
define('ASH_VERSION', "2.3.3");
define('ASH_PLUGIN_URL', plugin_dir_url( __FILE__ ));
class AutoSyntaxHighlighter {
	private $_shlver = '3.0.83';
	private $_settings = array();
	private $_brushes = array();
	private $_themes = 'default';
	private $_post_brushes = array();
	private $_html_script = false; // for other SyntaxHighlighter plugins

	public function __construct(){

		// Register brush scripts
		wp_register_script( 'ash_autoloader',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/core-min.js',			false,				  $this->_shlver, true );
		wp_register_script( 'ash_brush_xml',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/shBrushXml-min.js',			array('ash_autoloader'),  $this->_shlver, true );
//		wp_register_script( 'ash_autoloader',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/shAutoloader.js',			array('ash_shcore'),	   $this->_shlver, true );
//		wp_register_script( 'ash_brush_applescript',ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/shBrushAppleScript.js',	array('ash_autoloader'), $this->_shlver, true );
//		wp_register_script( 'ash_brush_xml',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/shBrushXml.js',			array('ash_autoloader'), $this->_shlver, true );
//		wp_register_script( 'ash_shcore',			ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/shCore.js',				array('ash_xregexp'),	   $this->_shlver, true );
//		wp_register_script( 'ash_xregexp',			ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/XRegExp.js',				false, $this->_shlver, true );

		// Register theme stylesheets
		wp_register_style(  'ash_core',				ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shCore-min.css',				array(),		   $this->_shlver );
		wp_register_style(  'ash_theme_default',	ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeDefault-min.css',		array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_django',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeDjango-min.css',		array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_eclipse',	ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeEclipse-min.css',		array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_emacs',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeEmacs-min.css',		array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_fadetogrey',	ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeFadeToGrey-min.css',	array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_mdultra',	ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeMDUltra-min.css',		array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_midnight',	ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeMidnight-min.css',	array('ash_core'), $this->_shlver );
		wp_register_style(  'ash_theme_rdark',		ASH_PLUGIN_URL.'SyntaxHighlighter/build/styles/shThemeRDark-min.css',		array('ash_core'), $this->_shlver );

		$this->_brushes = apply_filters('ash_brushes',array(
			array('applescript', 'shBrushAppleScript-min.js'),
			array('actionscript3 as3', 'shBrushAS3-min.js'),
			array('bash shell', 'shBrushBash-min.js'),
			array('coldfusion cf', 'shBrushColdFusion-min.js'),
			array('c# c-sharp csharp', 'shBrushCSharp-min.js'),
			array('cpp c', 'shBrushCpp-min.js'),
			array('css', 'shBrushCss-min.js'),
			array('delphi pas pascal', 'shBrushDelphi-min.js'),
			array('diff patch', 'shBrushDiff-min.js'),
			array('erl erlang', 'shBrushErlang-min.js'),
			array('groovy', 'shBrushGroovy-min.js'),
			array('js jscript javascript', 'shBrushJScript-min.js'),
			array('java', 'shBrushJava-min.js'),
			array('jfx javafx', 'shBrushJavaFX-min.js'),
			array('objective-c objc cocoa', 'shBrushObjC-min.js'),
			array('perl pl', 'shBrushPerl-min.js'),
			array('php', 'shBrushPhp-min.js'),
			array('text plain', 'shBrushPlain-min.js'),
			array('ps powershell', 'shBrushPowerShell-min.js'),
			array('py python', 'shBrushPython-min.js'),
			array('rails ror ruby', 'shBrushRuby-min.js'),
			array('scala', 'shBrushScala-min.js'),
			array('sql', 'shBrushSql-min.js'),
			array('vb vbnet', 'shBrushVb-min.js'),
			array('xml xhtml xslt html', 'shBrushXml-min.js'),
		));
		add_filter('the_content', array($this, 'getContentLang'));
		add_action('wp_footer', array($this,'outputScripts'));
	}

	public function getContentLang($content){
		if (preg_match_all('/<pre[\S|\s]*class=\"brush:[\s]*([\w-]*)(;[\S|\s]*|)\">/isU', $content, $post_lang)){
			foreach ($post_lang[1] as $k => $v){
				$v = strtolower($v);
				if (!isset($this->_post_brushes[$v])) $this->_post_brushes[$v]=$v;
			}
			// for html-script
			foreach ($post_lang[2] as $k => $v)
			{
				if (!$this->_html_script && strpos($v,'html-script') && preg_match('/html\-script:[\s]?true/i',$v))
					$this->_html_script = true;
			}
		}
		return $content;
	}

	public function outputScripts(){
		if (empty($this->_post_brushes)) return;
		echo "<!-- Auto SyntaxHighlighter -->\n";
		if (!$this->_html_script)
			wp_print_scripts('ash_autoloader');
		else
			wp_print_scripts('ash_brush_xml');
		wp_print_styles('ash_theme_'.$this->_themes);
		?>
<script type='text/javascript'>
SyntaxHighlighter.autoloader(
<?php
	$brushes_script = '';
	foreach ($this->_brushes as $k => $v)
	{
		$brushes_script .= "\t".'\''.$v[0].'	'.ASH_PLUGIN_URL.'SyntaxHighlighter/build/scripts/'.$v[1].'\','."\n";
	}
	$brushes_script = substr($brushes_script, 0,-2);
	echo $brushes_script."\n";
?>
);
SyntaxHighlighter.defaults['auto-links'] = false;
SyntaxHighlighter.defaults['toolbar'] = false;
SyntaxHighlighter.all();
</script>
<!-- /Auto SyntaxHighlighter -->
<?php
	}
}
// init plugins for display code
add_action( 'wp_head', create_function('','$ash = new AutoSyntaxHighlighter();'));

class AddQuickTagsButtons
{
	public function __construct()
	{
		if ( version_compare( get_bloginfo('version'), '3.2', '>=' ) ) {
			add_action( 'admin_footer-post.php', array( $this, 'quicktag_buttons' ) );
			add_action( 'admin_footer-post-new.php', array( $this, 'quicktag_buttons' ) );
			
		}
	}
	public function quicktag_buttons()
	{
		echo '<script type="text/javascript">';
		echo 'QTags.addButton( \'Ash Editor\', \'Ash Editor\', function(){tinyMCE.execCommand(\'mceash\');}, false, false, \'Auto SyntaxHighlighter Source Editor\' );';
		echo '</script>';
	}
}
// Adding buttons to the HTML editor in WordPress 3.3+
//add_action('init', create_function('' , 'new AddQuickTagsButtons;') );

/**
 * add a button on tinymce
 * @author digihero
 *
 */
class WpAsh {
	function __construct()
	{
		// Check for WP2.6 installation, This works only in WP2.6 or higher
		if ( version_compare(get_bloginfo('version'), '2.6', '<')) {
			add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error fade"><p><strong>Sorry, The Wordpress Plugins "Auto Syntax Highlighter" works only under WordPress 2.6 or higher.</strong></p></div>\';'));
			return;
		} else {
			include_once (plugin_basename('/tinymce/tinymce.php'));
			
			// Filters for editor, save pre "class"
			if (!current_user_can('unfiltered_html'))
			{
				global $allowedposttags; 
				$allowedposttags['pre']['class'] = array();
			}
		}
		
	}
	
}

add_action( 'plugins_loaded', create_function( '', '$wpash = new WpAsh;' ) );
