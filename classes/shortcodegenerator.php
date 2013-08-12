<?php

class VP_ShortcodeGenerator
{

	public static $pool = array();

	public $name;

	public $template;

	public $modal_title = '';

	public $button_title = '';

	public $main_image;

	public $sprite_image;

	public $types;

	public $include_pages;

	public function __construct($arr)
	{
		$this->main_image     = VP_PUBLIC_URL . '/img/vp_shortcode_icon.png';
		$this->sprite_image   = VP_PUBLIC_URL . '/img/vp_shortcode_icon_sprite.png';
		$this->types          = array( 'post', 'page' );
		$this->included_pages = array();

		if (is_array($arr))
		{
			foreach ($arr as $n => $v)
			{
				$this->$n = $v;
			}
			if (empty($this->name))     die('Unique name required');
			if (empty($this->template)) die('Template array / path required');
		}

		if( is_string($this->template) and is_file($this->template) )
			$this->template = include $this->template;

		if(!empty($this->template))
		{
			$this->normalize();
			add_action( 'current_screen', array($this, 'init_mce_plugin') );
		}

		self::$pool[$this->name] = $this;
	}

	function init_mce_plugin()
	{
		if( $this->can_output() )
		{
			// print modal dialog dom
			add_action( 'admin_footer', array($this, 'print_modal') );
			// populate scripts and styles dependencies
			$loader = VP_WP_Loader::instance();
			$loader->add_types( $this->get_field_types() );
		}
	}

	function normalize()
	{
		if(is_array($this->template)) foreach ($this->template as &$shortcode)
		{
			foreach ($shortcode['elements'] as &$elements)
			{
				if(isset($elements['attributes'])) foreach ($elements['attributes'] as &$f)
				{
					if( $f['type'] === 'codeeditor' )
					{
						$f['type'] = 'textarea';
					}
				}
			}	
		}
	}

	function get_field_types()
	{
		$field_types = array();
		if(is_array($this->template)) foreach ($this->template as $shortcode)
		{
			foreach ($shortcode['elements'] as $elements)
			{
				if(isset($elements['attributes'])) foreach ($elements['attributes'] as $f)
				{
					if( ! in_array($f['type'], $field_types) )
					{
						$field_types[] = $f['type'];
					}
				}
			}	
		}
		return $field_types;
	}

	public static function get_pool()
	{
		return self::$pool;
	}

	public static function pool_can_output()
	{
		foreach (self::$pool as $sg)
		{
			if( $sg->can_output() )
			{
				return true;
			}
		}
		return false;
	}

	public function can_output()
	{
		$screen = '';
		$can    = true;
		if( function_exists('get_current_screen') )
		{
			$screen = get_current_screen();
			$screen = $screen->id;
		}

		// if in post / page
		if( VP_Metabox::_is_post_or_page() )
		{
			// then consider the types
			$can &= in_array(VP_Metabox::_get_current_post_type(), $this->types);
		}
		else
		{
			// if not, only consider the screen id
			if( !empty($screen) )
			{
				$can &= in_array($screen, $this->included_pages);
			}
		}

		return $can;
	}

	public function print_modal()
	{
		$modal_id = $this->name . '_modal';
		?>
		<div id="<?php echo $modal_id; ?>" class="vp-sc-dialog reveal-modal xlarge">
			<h1><?php echo $this->modal_title; ?></h1>
			<div class="vp-sc-scroll-container">
				<div class="vp-sc-wrapper">
					<ul class="vp-sc-menu">
					<?php foreach ($this->template as $title => $menu): ?>
						<?php if(reset($this->template) == $menu): ?>
						<li class="current"><a href="#<?php echo str_replace(' ', '_', $title); ?>"><?php echo $title ?></li></a>
						<?php else: ?>
						<li><a href="#<?php echo str_replace(' ', '_', $title); ?>"><?php echo $title ?></li></a>
						<?php endif; ?>
					<?php endforeach; ?>
					</ul>
					<div class="vp-sc-menu-content">
						<?php foreach ($this->template as $title => $menu): ?>
							<?php if(reset($this->template) == $menu): ?>
							<ul class="vp-sc-menu-content-<?php echo str_replace(' ', '_', $title); ?> current">
							<?php else: ?>
							<ul class="vp-hide vp-sc-menu-content-<?php echo str_replace(' ', '_', $title); ?>">
							<?php endif; ?>
							<?php foreach ($menu['elements'] as $name => $element): ?>
								<li class="vp-sc-menu-item<?php if(isset($element['attributes'])) echo ' has-menu'; ?>">
									<a href="">
										<?php echo $element['title']; ?>
										<?php if(isset($element['attributes'])) echo '<i class="icon-arrow-down"></i>'; ?>
									</a>
									<div class="hidden vp-sc-code"><?php echo $element['code']; ?></div>
									<?php if(isset($element['attributes'])): ?>
									<form class="vp-sc-form vp-hide">
										<?php echo $this->print_form($element['attributes']); ?>
									</form>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
							</ul>
						<?php endforeach; ?>
					</div>
				</div>
				<a class="close-reveal-modal">&#215;</a>
			</div>
		</div>
		<?php
	}

	function print_form($attributes)
	{
		?>
		<div class="vp-sc-fields">
		<?php
		foreach ($attributes as $attr)
		{
			// create the object
			$make           = VP_Util_Reflection::field_class_from_type($attr['type']);
			// prefix name
			$attr['name']   = '_' . $attr['name'];
			$field          = call_user_func("$make::withArray", $attr);
			$default        = $field->get_default();
			if(!is_null($default))
				$field->set_value($default);
			?>

			<?php if($attr['type'] !== 'notebox'): ?>
				<div class="vp-sc-field vp-<?php echo $attr['type']; ?>" data-vp-type="vp-<?php echo $attr['type']; ?>">
					<div class="label"><label><?php echo $attr['label']; ?></label></div>
					<div class="field"><div class="input"><?php echo $field->render(true); ?></div></div>
				</div>
			<?php else: ?>
				<?php $status = isset($attr['status']) ? $attr['status'] : 'normal'; ?>
				<div class="vp-sc-field vp-<?php echo $attr['type']; ?> note-<?php echo $status; ?>" data-vp-type="vp-<?php echo $attr['type']; ?>">
					<?php echo $field->render(true); ?>
				</div>
			<?php endif; ?>

			<?php
		}
		?>
		</div>
		<div class="vp-sc-action">
			<button class="vp-sc-insert button">insert</button>
			<button class="vp-sc-cancel button">cancel</button>
		</div>
		<?php
	}

	public static function build_localize()
	{
		$localize = array();
		foreach (self::$pool as $sg)
		{
			$localize[] = array(
				'name'         => $sg->name,
				'modal_title'  => $sg->modal_title,
				'button_title' => $sg->button_title,
				'main_image'   => $sg->main_image,
				'sprite_image' => $sg->sprite_image,
			);
		}
		return $localize;
	}

	public static function init_buttons()
	{
		if( VP_Metabox::_is_post_or_page() && !current_user_can( 'edit_posts' ) &&
			!current_user_can( 'edit_pages' ) && get_user_option( 'rich_editing' ) == 'true')
			return;

		add_filter( 'mce_external_plugins' , array(__CLASS__, 'add_buttons') );
		add_filter( 'mce_buttons'          , array(__CLASS__, 'register_buttons') );
		add_filter( 'wp_fullscreen_buttons', array(__CLASS__, 'fullscreen_buttons') );
		add_filter( 'admin_print_styles'   , array(__CLASS__, 'print_styles') );
	}

	public static function print_styles($buttons)
	{
		?>
			<style type="text/css">
				<?php foreach (self::$pool as $sg): ?>
				#qt_content_<?php echo $sg->name; ?>{
					background: url('<?php echo $sg->sprite_image; ?>') 0 -20px no-repeat !important;
					text-indent: -999px;
				}
				span.mce_<?php echo $sg->name; ?>{
					background: url('<?php echo $sg->sprite_image; ?>') 0 0 no-repeat !important;
				}
				<?php endforeach; ?>
			</style>
		<?php
	}

	public static function register_buttons($buttons)
	{
		foreach (self::$pool as $sg)
		{
			if( $sg->can_output() )
				$vp_buttons[] = $sg->name;
		}
		$buttons = array_merge($buttons, $vp_buttons);
		return $buttons;
	}

	public static function add_buttons($plugin_array)
	{
		$plugin_array['vp_sc_button'] = VP_PUBLIC_URL .'/js/shortcodes.js';
		foreach (self::$pool as $sg)
		{
			if( $sg->can_output() )
				$plugin_array[$sg->name] = VP_PUBLIC_URL .'/js/dummy.js';
		}
		return $plugin_array;
	}

	public static function fullscreen_buttons($buttons)
	{
		foreach (self::$pool as $sg)
		{
			if( $sg->can_output() )
			{
				// add a separator
				$buttons[] = 'separator';
				// format: title, onclick, show in both editors
				$buttons[$sg->name] = array(
					// Title of the button
					'title'   => $sg->button_title,
					// Command to execute
					'onclick' => "tinyMCE.execCommand('{$sg->name}_cmd');",
					// Show on visual AND html mode
					'both'    => true
				);	
			}
		}
		return $buttons;
	}

}

/**
 * EOF
 */