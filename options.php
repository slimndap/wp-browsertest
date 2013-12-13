<?php
class Mailinglijst_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
		$this->options = get_option( 'mailinglijst' );
		$this->admin_link = '<a href="options-general.php?page=mailinglijst-admin">Settings</a>';
		
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		
		add_action('admin_notices', array($this, 'admin_notices'));
		
		add_filter("plugin_action_links_".plugin_basename(__FILE__), array($this, 'plugin_action_links_mailinglijst'));
		
   }

	function plugin_action_links_mailinglijst($links) {
		array_unshift($links, $this->admin_link); 
		return $links;		
	}

	function admin_notices() {
		global $mailinglijst;
		if (!$mailinglijst->ready()) {
	        printf(
	            '<div class="error"><p><strong>Mailinglijst</strong></p><p>%s.</p><p>%s</p></div>',
	            __('Almost ready! Please enter you lijstnummer','mailinglijst'),
	            $this->admin_link
	        );
		}
	}

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Mailinglijst', 
            'manage_options', 
            'mailinglijst-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Mailinglijst Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'mailinglijst_group' );   
                do_settings_sections( 'mailinglijst-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'mailinglijst_group', // Option group
            'mailinglijst', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            __('Settings','mailinglijst'), // Title
            array( $this, 'print_section_info' ), // Callback
            'mailinglijst-admin' // Page
        );  

        add_settings_field(
            'lijstnummer', // ID
            __('Lijstnummer','mailinglijst'), // Title 
            array( $this, 'lijstnummer_callback' ), // Callback
            'mailinglijst-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'formtype', 
            __('Formtype','mailinglijst'), 
            array( $this, 'formtype_callback' ), 
            'mailinglijst-admin', 
            'setting_section_id'
        );  
            
        add_settings_field(
            'customcss', 
            __('I want to use my own CSS','mailinglijst'), 
            array( $this, 'customcss_callback' ), 
            'mailinglijst-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['lijstnummer'] ) )
            $new_input['lijstnummer'] = $input['lijstnummer'];

        if( isset( $input['formtype'] ) )
            $new_input['formtype'] = sanitize_text_field( $input['formtype'] );

        if( isset( $input['customcss'] ) )
            $new_input['customcss'] = $input['customcss'];

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function lijstnummer_callback()
    {
        printf(
            '<input type="text" id="lijstnummer" name="mailinglijst[lijstnummer]" value="%s" />',
            isset( $this->options['lijstnummer'] ) ? esc_attr( $this->options['lijstnummer']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function formtype_callback()
    {
    	$options = array(
    		'popup'=>__('popup','mailinglijst'),
    		'iframe' => __('iframe','mailinglijst'),
    		'fast' => __('FAST','mailinglijst')
    	);
    	echo '<select id="formtype" name="mailinglijst[formtype]">';
    	foreach($options as $key=>$val) {
	    	printf(
	    		'<option value="'.$key.'" %s>'.$val.'</option>',
	    		(isset( $this->options['formtype'] ) && (esc_attr( $this->options['formtype'])==$key)) ? 'selected="selected"' : ''
	    	);
    	}
    	echo '</select>';
    }

    public function customcss_callback()
    {
        printf(
            '<input type="checkbox" id="customcss" name="mailinglijst[customcss]" value="yes" %s />',
    		(isset( $this->options['customcss'] ) && (esc_attr( $this->options['customcss'])=='yes')) ? 'checked="checked"' : ''
        );
    }

}

if( is_admin() )
    $mailinglijst_settings_page = new Mailinglijst_Settings();