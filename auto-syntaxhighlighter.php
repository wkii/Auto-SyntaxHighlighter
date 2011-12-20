<?php
/*
 Plugin Name: Auto SyntaxHighlighter
 Plugin URI: http://www.akii.org/auto-syntaxhighlighter.html
 Description: Autoload SyntaxHighlighter only requisite js files and display you code. Uses Alex Gorbatchev's SyntaxHighlighter: <a href="http://alexgorbatchev.com/SyntaxHighlighter/manual/brushes/">SyntaxHighlighter Brushes</a>. Full Support for : AppleScript, ActionScript3, Bash/shell, C#, C++, CSS, Delphi, Diff, Groovy, JavaScript, Java, Perl, PHP, Plain Text, Python, Ruby, Sass, Scala, SQL, Visual Basic and XML/HTML...
 Version: 2.1
 Author: digihero
 Author URI: http://www.akii.org
 */
define('ASH_VERSION', 2.1);

class AutoSyntaxHighlighter {
	private $_shlver = '3.0.83';
	private $_settings = array();
	private $_brushes = array();
	private $_themes = 'default';
	private $_post_brushes = array();
	private $_used_burshes = array();

	function __construct(){

		// Register brush scripts
		wp_register_script( 'syntaxhighlighter-core',				plugins_url('auto-syntaxhighlighter/scripts/shCore.js'),			array(),                         $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-applescript',	plugins_url('auto-syntaxhighlighter/scripts/shBrushAppleScript.js'),array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-as3',			plugins_url('auto-syntaxhighlighter/scripts/shBrushAS3.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-bash',			plugins_url('auto-syntaxhighlighter/scripts/shBrushBash.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-coldfusion',	plugins_url('auto-syntaxhighlighter/scripts/shBrushColdFusion.js'),	array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-csharp',		plugins_url('auto-syntaxhighlighter/scripts/shBrushCSharp.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-cpp',			plugins_url('auto-syntaxhighlighter/scripts/shBrushCpp.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-css',			plugins_url('auto-syntaxhighlighter/scripts/shBrushCss.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-delphi',		plugins_url('auto-syntaxhighlighter/scripts/shBrushDelphi.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-diff',			plugins_url('auto-syntaxhighlighter/scripts/shBrushDiff.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-erlang',		plugins_url('auto-syntaxhighlighter/scripts/shBrushErlang.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-groovy',		plugins_url('auto-syntaxhighlighter/scripts/shBrushGroovy.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-jscript',		plugins_url('auto-syntaxhighlighter/scripts/shBrushJScript.js'),	array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-java',			plugins_url('auto-syntaxhighlighter/scripts/shBrushJava.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-javafx',		plugins_url('auto-syntaxhighlighter/scripts/shBrushJavaFX.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-perl',			plugins_url('auto-syntaxhighlighter/scripts/shBrushPerl.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-php',			plugins_url('auto-syntaxhighlighter/scripts/shBrushPhp.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-plain',		plugins_url('auto-syntaxhighlighter/scripts/shBrushPlain.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-powershell',	plugins_url('auto-syntaxhighlighter/scripts/shBrushPowerShell.js'),	array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-python',		plugins_url('auto-syntaxhighlighter/scripts/shBrushPython.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-ruby',			plugins_url('auto-syntaxhighlighter/scripts/shBrushRuby.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-sass',			plugins_url('auto-syntaxhighlighter/scripts/shBrushSass.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-scala',		plugins_url('auto-syntaxhighlighter/scripts/shBrushScala.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-sql',			plugins_url('auto-syntaxhighlighter/scripts/shBrushSql.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-vb',			plugins_url('auto-syntaxhighlighter/scripts/shBrushVb.js'),			array('syntaxhighlighter-core'), $this->_shlver, true );
		wp_register_script( 'syntaxhighlighter-brush-xml',			plugins_url('auto-syntaxhighlighter/scripts/shBrushXml.js'),		array('syntaxhighlighter-core'), $this->_shlver, true );

		// Register theme stylesheets
		wp_register_style(  'syntaxhighlighter-core',				plugins_url('auto-syntaxhighlighter/styles/shCore.css'),			array(),                         $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-default',		plugins_url('auto-syntaxhighlighter/styles/shThemeDefault.css'),	array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-django',		plugins_url('auto-syntaxhighlighter/styles/shThemeDjango.css'),		array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-eclipse',		plugins_url('auto-syntaxhighlighter/styles/shThemeEclipse.css'),	array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-emacs',		plugins_url('auto-syntaxhighlighter/styles/shThemeEmacs.css'),		array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-fadetogrey',	plugins_url('auto-syntaxhighlighter/styles/shThemeFadeToGrey.css'),	array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-mdultra',		plugins_url('auto-syntaxhighlighter/styles/shThemeMDUltra.css'),	array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-midnight',		plugins_url('auto-syntaxhighlighter/styles/shThemeMidnight.css'),	array('syntaxhighlighter-core'), $this->_shlver );
		wp_register_style(  'syntaxhighlighter-theme-rdark',		plugins_url('auto-syntaxhighlighter/styles/shThemeRDark.css'),		array('syntaxhighlighter-core'), $this->_shlver );

		$this->_brushes = apply_filters('syntaxhighlighter_brushes',array(
			'applescript'	=> 'applescript',
			'as3'           => 'as3',
			'actionscript3' => 'as3',
			'bash'          => 'bash',
			'shell'         => 'bash',
			'cf'			=> 'coldfusion',
			'coldfusion'	=> 'coldfusion',
			'c-sharp'		=> 'csharp',
			'csharp'		=> 'csharp',
			'cpp'			=> 'cpp',
			'c'				=> 'cpp',
			'css'			=> 'css',
			'delphi'		=> 'delphi',
			'pas'			=> 'delphi',
			'pascal'		=> 'delphi',
			'diff'			=> 'diff',
			'patch'			=> 'diff',
			'erl'			=> 'erlang',
			'erlang'		=> 'erlang',
			'groovy'		=> 'groovy',
			'js'			=> 'jscript',
			'jscript'		=> 'jscript',
			'javascript'	=> 'jscript',
			'java'			=> 'java',
			'jfx'			=> 'javafx',
			'javafx'		=> 'javafx',
			'perl'			=> 'perl',
			'pl'			=> 'perl',
			'php'			=> 'php',
			'plain'			=> 'plain',
			'text'			=> 'plain',
			'ps'			=> 'powershell',
			'powershell'	=> 'powershell',
			'py'			=> 'python',
			'python'		=> 'python',
			'rails'			=> 'ruby',
			'ror'			=> 'ruby',
			'ruby'			=> 'ruby',
			'sass'			=> 'sass',
			'scss'			=> 'sass',
			'scala'			=> 'scala',
			'sql'			=> 'sql',
			'vb'			=> 'vb',
			'vbnet'			=> 'vb',
			'xml'			=> 'xml',
			'xhtml'			=> 'xml',
			'xslt'			=> 'xml',
			'html'			=> 'xml',
		));

		add_filter('the_content', array($this, 'getContentLang'));
		add_action('wp_footer', array($this,'outputScripts'));
	}

	function getContentLang($content){
		if (preg_match_all('/<pre[\S|\s]*class=\"brush:[\s]?([\w-]*)(;[\S|\s]*|)\">/isU', $content, $post_lang)){
			foreach ($post_lang[1] as $k => $v){
				$v = strtolower($v);
				if (!in_array($v,$this->_post_brushes)) $this->_post_brushes[]=$v;
			}
		}
		return $content;
	}

	function outputScripts(){
		global $wp_styles;
		if (empty($this->_post_brushes)) return;
		$brushes = array();
		foreach ($this->_post_brushes as $k => $v){
			$brushes[] = 'syntaxhighlighter-brush-' . $this->_brushes[$v];
		}
		$this->_used_burshes = array_unique($brushes);
		echo "<!-- Auto SyntaxHighlighter -->\n";
		wp_print_scripts($this->_used_burshes);
		?>
<script type='text/javascript'>
	(function(){
		var corecss = document.createElement('link');
		var themecss = document.createElement('link');
<?php 
		if ( !$wp_styles instanceof WP_Styles )
			$wp_styles = new WP_Styles();
		$theme = 'syntaxhighlighter-theme-' . $this->_themes;
		$core_css_url = apply_filters('syntaxhighlighter_corecssurl' , add_query_arg( 'ver', $this->_shlver, $wp_styles->registered['syntaxhighlighter-core']->src ));
		$theme_css_url = apply_filters('syntaxhighlighter_cssthemeurl', add_query_arg( 'ver', $this->_shlver, $wp_styles->registered[$theme]->src ));
?>
		var corecssurl = "<?php echo esc_js( $core_css_url ); ?>";
		if ( corecss.setAttribute ) {
				corecss.setAttribute( "rel", "stylesheet" );
				corecss.setAttribute( "type", "text/css" );
				corecss.setAttribute( "href", corecssurl );
		} else {
				corecss.rel = "stylesheet";
				corecss.href = corecssurl;
		}
		document.getElementsByTagName("head")[0].appendChild(corecss);
		
		var themecssurl = "<?php echo esc_js( $theme_css_url) ?>";
		if ( themecss.setAttribute ) {
				themecss.setAttribute( "rel", "stylesheet" );
				themecss.setAttribute( "type", "text/css" );
				themecss.setAttribute( "href", themecssurl );
		} else {
				themecss.rel = "stylesheet";
				themecss.href = themecssurl;
		}
		document.getElementsByTagName("head")[0].appendChild(themecss);
		})();
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