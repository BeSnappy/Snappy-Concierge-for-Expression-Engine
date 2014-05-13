<?php


if ( !defined('BASEPATH') ) exit('No direct script access allowed');


class Snappy_concierge_ext
{
	public $name 			= 'Snappy Concierge';
	public $version 		= '1.0';
	public $description 	= 'Snappy Concierge makes it easy for agencies and consultancies to organize ongoing change and support requests across multiple clients.';
	public $docs_url		= 'http://besnappy.com/concierge';
	public $settings_exist	= 'y';
	public $settings 		= array();


	public $added = false;


	function __construct ( $settings = '' )
	{
		ee()->lang->loadfile('snappy_concierge');

		// Get existing settings.
		ee()->db->select('settings')
			->from('extensions')
			->where('class', __CLASS__)
			->limit(1);

		$query = ee()->db->get();
		if ( $query->num_rows() > 0 ) {
			$settings = unserialize($query->row('settings'));
		}
		
		$this->settings = $settings;
	
		// We intercept cp_menu_array to get our constructor called
		// on every page load, but it can get called multiple times per
		// page as well. So, we need to make a check to make sure we
		// haven't already placed the snappy code into the page.

		if ( !empty($this->settings['sc_widget_code']) && !$this->page_has_snappy() ) {
			ee()->cp->add_to_foot($this->settings['sc_widget_code']);
		}
	}


	private function page_has_snappy()
	{
		foreach ( ee()->cp->footer_item as $footer_item ) {
			if ( strpos($footer_item, 'snappy') !== false ) {
				return true;
			}
		}

		return false;
	}


	
	function activate_extension()
	{
		$this->settings = array (
			'sc_widget_code' => ''
		);

		$data = array(
			'class' => __CLASS__,
			'method' => 'cp_menu_array',
			'hook' => 'cp_menu_array',
			'settings' => serialize($this->settings),
			'priority' => 10,
			'version' => $this->version,
			'enabled' => 'y'
		);

		ee()->db->insert('extensions', $data);
	}


	
	function update_extension ( $current = '' )
	{
		if ( $current == '' || $current == $this->version )
		{
			return FALSE;
		}

		if ( $current < '1.0' )
		{
		}

		ee()->db->where('class', __CLASS__);
		ee()->db->update('extensions', array('version' => $this->version));
	}



	function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}



	/*
	function settings()
	{
		return array('sc_widget_code' => array('t', array('rows' => '20'), ''));
	}
	*/



	function settings_form ( $current )
	{
		ee()->load->helper('form');
		ee()->load->library('table');

		$vars = array();

		$vars['sc_widget_code'] = (isset($current['sc_widget_code'])) ? $current['sc_widget_code'] : '';
	
		return ee()->load->view('settings', $vars, TRUE);
	}



	function save_settings()
	{
		if ( empty($_POST) )
		{
			show_error(lang('unauthorized_access'));
		}

		unset($_POST['submit']);

		ee()->lang->loadfile('snappy_concierge');

		$sc_widget_code = ee()->input->post('sc_widget_code');

		ee()->db->where('class', __CLASS__);
		ee()->db->update('extensions', array('settings' => serialize($_POST)));

		//ee()->session->set_flashdata(
		//	'message_success',
		//	lang('preferences_updated')
		//);
	}



	function cp_menu_array($menu)
	{
		// We don't really care about the menu. We just needed
		// a hook that is called on every page load of the 
		// control panel so that our constructor is called,
		// which is where we add the Snappy widget code.
		
		// Note: The cp_js_end hook wasn't cutting it.
		// Adding '<script>'s into the page in the cp_js_end
		// didn't give us the results we wanted, and calling
		// add_to_foot() in a hook callback results in 
		// internal server errors! How does anything work, ever?

		if ( ee()->extensions->last_call !== FALSE ) {
			$menu = ee()->extensions->last_call;
		}

		return $menu;
	}

}

