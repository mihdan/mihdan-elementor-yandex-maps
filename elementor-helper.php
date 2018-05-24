<?php
namespace Elementor;

add_action( 'elementor/init', function() {
	\Elementor\Plugin::$instance->elements_manager->add_category( 
   	'eb-elementor-extended',
        [
            'title'  => 'Elementor Extended',
            'icon' => 'font'
        ],
        1
	);
});