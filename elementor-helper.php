<?php
namespace Elementor;

add_action( 'elementor/init', function() {
	\Elementor\Plugin::$instance->elements_manager->add_category(
   	'mihdan',
        [
            'title'  => 'Mihdan Widgets',
            'icon' => 'font'
        ],
        1
	);
});