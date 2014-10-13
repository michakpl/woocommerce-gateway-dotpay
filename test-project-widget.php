<?php
/*
Plugin Name: Test Project Widget
Version: 1.0
Author: Michal Groele
*/
if ( ! defined( 'WPINC' ) ) exit; // Exit if accessed directly

class Test_Project_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'social-butttons', 'description' => 'A widget that displays social buttons');

		parent::__construct(
			'social_buttons_widget',
			'Social Buttons Widget',
			$widget_ops
		);
	}
}