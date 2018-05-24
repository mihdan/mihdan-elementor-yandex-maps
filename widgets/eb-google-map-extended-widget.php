<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class EB_Google_Map_Extended extends Widget_Base {

	/**
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'eb-google-map-extended';
	}

	/**
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Google Map Extended', 'elementor' );
	}

	/**
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'eb-elementor-extended' ];
	}

	public function get_script_depends() {
		return [ 'eb-google-maps-api', 'eb-google-map' ];
	}


	/**
	 * Register google maps widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_map',
			[
				'label' => __( 'Map', 'elementor' ),
			]
		);

		if (empty(eb_map_get_option( 'eb_google_map_api_key', 'eb_map_general_settings' ))) {
			$this->add_control(
			    'api_notice',
			    [
				    'type'  => Controls_Manager::RAW_HTML,
				    'raw'   => 'Please enter your Google Map API <a target="_blank" href="'.admin_url('admin.php?page=eb_google_map_setting').'">here</a>',
				    'label_block' => true,
			    ]
		    );
		}

		$this->add_control(
		    'map_notice',
		    [
			    'label' => __( 'Find Latitude & Longitude', 'elementor' ),
			    'type'  => Controls_Manager::RAW_HTML,
			    'raw'   => '<form onsubmit="ebMapFindAddress(this);" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="ebMapFindAddress(this)"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
			    'label_block' => true,
		    ]
	    );

		$this->add_control(
			'map_lat',
			[
				'label' => __( 'Latitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '1.2833754',
				'default' => '1.2833754',
			]
		);

		$this->add_control(
			'map_lng',
			[
				'label' => __( 'Longitude', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '103.86072639999998',
				'default' => '103.86072639999998',
				'separator' => true,
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => __( 'Zoom Level', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eb-map' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'map_type',
			[
				'label' => __( 'Map Type', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'roadmap' => __( 'Road Map', 'elementor' ),
					'satellite' => __( 'Satellite', 'elementor' ),
					'hybrid' => __( 'Hybrid', 'elementor' ),
					'terrain' => __( 'Terrain', 'elementor' ),
				],
				'default' => 'roadmap',
			]
		);

		$this->add_control(
			'gesture_handling',
			[
				'label' => __( 'Gesture Handling', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'auto' => __( 'Auto (Default)', 'elementor' ),
					'cooperative' => __( 'Cooperative', 'elementor' ),
					'greedy' => __( 'Greedy', 'elementor' ),
					'none' => __( 'None', 'elementor' ),
				],
				'default' => 'auto',
				'description' => __('Understand more about Gesture Handling by reading it <a href="https://developers.google.com/maps/documentation/javascript/reference/3/#MapOptions" target="_blank">here.</a> Basically it control how it handles gestures on the map. Used to be draggable and scroll wheel function which is deprecated.'),
			]
		);


		/*$this->add_control(
			'scroll_wheel',
			[
				'label' => __( 'Scroll Wheel', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);*/

		$this->add_control(
			'zoom_control',
			[
				'label' => __( 'Show Zoom Control', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'zoom_control_position',
			[
				'label' => __( 'Control Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'RIGHT_BOTTOM' => __( 'Bottom Right (Default)', 'elementor' ),
					'TOP_LEFT' => __( 'Top Left', 'elementor' ),
					'TOP_CENTER' => __( 'Top Center', 'elementor' ),
					'TOP_RIGHT' => __( 'Top Right', 'elementor' ),
					'LEFT_CENTER' => __( 'Left Center', 'elementor' ),
					'RIGHT_CENTER' => __( 'Right Center', 'elementor' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'elementor' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'elementor' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'elementor' ),
				],
				'default' => 'RIGHT_BOTTOM',
				'condition' => [
					'zoom_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'default_ui',
			[
				'label' => __( 'Show Default UI', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'map_type_control',
			[
				'label' => __( 'Map Type Control', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'map_type_control_style',
			[
				'label' => __( 'Control Styles', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'DEFAULT' => __( 'Default', 'elementor' ),
					'HORIZONTAL_BAR' => __( 'Horizontal Bar', 'elementor' ),
					'DROPDOWN_MENU' => __( 'Dropdown Menu', 'elementor' ),
				],
				'default' => 'DEFAULT',
				'condition' => [
					'map_type_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'map_type_control_position',
			[
				'label' => __( 'Control Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'TOP_LEFT' => __( 'Top Left (Default)', 'elementor' ),
					'TOP_CENTER' => __( 'Top Center', 'elementor' ),
					'TOP_RIGHT' => __( 'Top Right', 'elementor' ),
					'LEFT_CENTER' => __( 'Left Center', 'elementor' ),
					'RIGHT_CENTER' => __( 'Right Center', 'elementor' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'elementor' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'elementor' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'elementor' ),
				],
				'default' => 'TOP_LEFT',
				'condition' => [
					'map_type_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'streetview_control',
			[
				'label' => __( 'Show Streetview Control', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'streetview_control_position',
			[
				'label' => __( 'Streetview Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'RIGHT_BOTTOM' => __( 'Bottom Right (Default)', 'elementor' ),
					'TOP_LEFT' => __( 'Top Left', 'elementor' ),
					'TOP_CENTER' => __( 'Top Center', 'elementor' ),
					'TOP_RIGHT' => __( 'Top Right', 'elementor' ),
					'LEFT_CENTER' => __( 'Left Center', 'elementor' ),
					'RIGHT_CENTER' => __( 'Right Center', 'elementor' ),
					'BOTTOM_LEFT' => __( 'Bottom Left', 'elementor' ),
					'BOTTOM_CENTER' => __( 'Bottom Center', 'elementor' ),
					'BOTTOM_RIGHT' => __( 'Bottom Right', 'elementor' ),
				],
				'default' => 'RIGHT_BOTTOM',
				'condition' => [
					'streetview_control' => 'yes',
				],
				'separator' => false,
			]
		);

		$this->add_control(
			'custom_map_style',
			[
				'label' => __( 'Custom Map Style', 'elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'description' => __('Add style from <a href="https://mapstyle.withgoogle.com/" target="_blank">Google Map Styling Wizard</a> or <a href="https://snazzymaps.com/explore" target="_blank">Snazzy Maps</a>. Copy and Paste the style in the textarea.'),
				'condition' => [
					'map_type' => 'roadmap',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		/*Pins Option*/
		$this->start_controls_section(
			'map_marker_pin',
			[
				'label' => __( 'Marker Pins', 'elementor' ),
			]
		);

		$this->add_control(
			'infowindow_max_width',
			[
				'label' => __( 'InfoWindow Max Width', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => '250',
				'default' => '250',
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Pin Item', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'pin_title' => __( 'Pin #1', 'elementor' ),
						'pin_notice' => __( 'Find Latitude & Longitude', 'elementor' ),
						'pin_lat' => __( '1.2833754', 'elementor' ),
						'pin_lng' => __( '103.86072639999998', 'elementor' ),
						'pin_content' => __( 'I am item content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
					],
				], 
				'fields' => [
				    [
				    	'name' => 'pin_notice',
				    	'label' => __( 'Find Latitude & Longitude', 'elementor' ),
					    'type'  => Controls_Manager::RAW_HTML,
					    'raw'   => '<form onsubmit="ebMapFindPinAddress(this);" action="javascript:void(0);"><input type="text" id="eb-map-find-address" class="eb-map-find-address" style="margin-top:10px; margin-bottom:10px;"><input type="submit" value="Search" class="elementor-button elementor-button-default" onclick="ebMapFindPinAddress(this)"></form><div id="eb-output-result" class="eb-output-result" style="margin-top:10px; line-height: 1.3; font-size: 12px;"></div>',
					    'label_block' => true,
				    ],
					[
						'name' => 'pin_lat',
						'label' => __( 'Latitude', 'elementor' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'pin_lng',
						'label' => __( 'Longitude', 'elementor' ),
						'type' => Controls_Manager::TEXT,
					],
					[
						'name' => 'pin_icon',
						'label' => __( 'Marker Icon', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'Default (Google)', 'elementor' ),
							'red' => __( 'Red', 'elementor' ),
							'blue' => __( 'Blue', 'elementor' ),
							'yellow' => __( 'Yellow', 'elementor' ),
							'purple' => __( 'Purple', 'elementor' ),
							'green' => __( 'Green', 'elementor' ),
							'orange' => __( 'Orange', 'elementor' ),
							'grey' => __( 'Grey', 'elementor' ),
							'white' => __( 'White', 'elementor' ),
							'black' => __( 'Black', 'elementor' ),
						],
						'default' => '',
					],
					[
						'name' => 'pin_title',
						'label' => __( 'Title', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => __( 'Pin Title' , 'elementor' ),
						'label_block' => true,
					],
					[
						'name' => 'pin_content',
						'label' => __( 'Content', 'elementor' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => __( 'Pin Content', 'elementor' ),
					],
				],
				'title_field' => '{{{ pin_title }}}',
			]
		);

		$this->end_controls_section();

		/*Main Style*/
		$this->start_controls_section(
			'section_main_style',
			[
				'label' => __( 'Pin Global Styles', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pin_header_color',
			[
				'label' => __( 'Title Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eb-map-container h6' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Title Typography', 'elementor' ),
				'name' => 'pin_header_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eb-map-container h6',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Title Text Shadow', 'elementor' ),
				'name' => 'pin_header_text_shadow',
				'selector' => '{{WRAPPER}} .eb-map-container h6',
				'separator' => true,
			]
		);


		$this->add_control(
			'pin_content_color',
			[
				'label' => __( 'Content Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eb-map-container p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Content Typography', 'elementor' ),
				'name' => 'pin_content_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eb-map-container p',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'label' => __( 'Content Text Shadow', 'elementor' ),
				'name' => 'pin_content_text_shadow',
				'selector' => '{{WRAPPER}} .eb-map-container p',
				'separator' => true,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eb-map-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render google maps widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
	}

	/**
	 * Render google maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();

		$eb_map_styles = $settings['custom_map_style'];
		$eb_replace_code_content = strip_tags($eb_map_styles);
        $eb_new_replace_code_content = preg_replace('/\s/', '', $eb_replace_code_content);

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		$this->add_render_attribute('eb-google-map-extended', 'data-eb-map-style', $eb_new_replace_code_content);

		$mapmarkers = array();

		foreach ( $settings['tabs'] as $index => $item ) :
			$tab_count = $index + 1;
			$mapmarkers[] = array('lat' => $item['pin_lat'], 'lng' => $item['pin_lng'],'title' => $item['pin_title'], 'content' => htmlspecialchars($item['pin_content'], ENT_QUOTES & ~ENT_COMPAT ), 'pin_icon' => $item['pin_icon'] );
		endforeach; 

		?>
		<div id="eb-map-<?php echo esc_attr($this->get_id()); ?>" class="eb-map" data-eb-map-gesture-handling="<?php echo $settings['gesture_handling'] ?>" <?php if ( 'yes' == $settings['zoom_control'] ) { ?> data-eb-map-zoom-control="true" data-eb-map-zoom-control-position="<?php echo $settings['zoom_control_position']; ?>" <?php } else { ?> data-eb-map-zoom-control="false"<?php } ?> data-eb-map-defaultui="<?php if ( 'yes' == $settings['default_ui'] ) { ?>false<?php } else { ?>true<?php } ?>" <?php echo $this->get_render_attribute_string('eb-google-map-extended'); ?> data-eb-map-type="<?php echo $settings['map_type']; ?>" <?php if ( 'yes' == $settings['map_type_control'] ) { ?> data-eb-map-type-control="true" data-eb-map-type-control-style="<?php echo $settings['map_type_control_style']; ?>" data-eb-map-type-control-position="<?php echo $settings['map_type_control_position']; ?>"<?php } else { ?> data-eb-map-type-control="false"<?php } ?> <?php if ( 'yes' == $settings['streetview_control'] ) { ?> data-eb-map-streetview-control="true" data-eb-map-streetview-position="<?php echo $settings['streetview_control_position']; ?>"<?php } else {?> data-eb-map-streetview-control="false"<?php } ?> data-eb-map-lat="<?php echo $settings['map_lat']; ?>" data-eb-map-lng="<?php echo $settings['map_lng']; ?>" data-eb-map-zoom="<?php echo $settings['zoom']['size']; ?>" data-eb-map-infowindow-width="<?php echo $settings['infowindow_max_width']; ?>" data-eb-locations='<?php echo json_encode($mapmarkers);?>'></div>

	<?php }
}

Plugin::instance()->widgets_manager->register_widget_type( new EB_Google_Map_Extended() );