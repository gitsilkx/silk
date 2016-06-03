<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		
		<?php echo $title_for_layout; ?>
	</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		echo $this->Html->meta('favicon.ico','http://www.silkrouters.com/demo/app/webroot/img/favicon.ico',array('type' => 'icon'));
	
        echo $this->Html->css(array('/bootstrap/css/bootstrap.min',
									'todc-bootstrap.min',
									'font-awesome/css/font-awesome.min',
									'/img/flags/flags',
									'retina',
									'/js/lib/jvectormap/jquery-jvectormap-1.2.2',
									'/js/lib/bootstrap-switch/stylesheets/bootstrap-switch',
									'/js/lib/bootstrap-switch/stylesheets/ebro_bootstrapSwitch',
									'/js/lib/FooTable/css/footable.core',
									'style',
									'theme/color_1',
									'/js/lib/magnific-popup/magnific-popup',
									'/js/lib/fullcalendar/fullcalendar',
									'/js/lib/datepicker/css/datepicker',
									'/js/lib/timepicker/css/bootstrap-timepicker.min',
									'/js/lib/multi-select/css/multi-select',
                                                                        '/js/lib/multi-select/css/ebro_multi-select',
                                                                        '/js/lib/select2/select2',
                                                                        '/js/lib/select2/ebro_select2'
									
									)
							);
		echo $this->Html->script(array('jquery.min',
									'/bootstrap/js/bootstrap.min',
									'jquery.ba-resize.min',
									'jquery_cookie.min',
									'retina.min',
									'tooltip',
									'tinynav',
									'jquery.sticky',
									'lib/navgoco/jquery.navgoco.min',
									'lib/jMenu/js/jMenu.jquery',
									'lib/typeahead.js/typeahead.min',
									'lib/typeahead.js/hogan-2.0.0',
									'ebro_common',
									'jquery.quicksearch',
									'pages/ebro_contact_list',
									'lib/peity/jquery.peity.min',
									'lib/jvectormap/jquery-jvectormap-1.2.2.min',
									'lib/jvectormap/maps/jquery-jvectormap-world-mill-en',
									'lib/flot/jquery.flot.min',
									'lib/flot/jquery.flot.pie.min',
									'lib/flot/jquery.flot.time.min',
									'lib/flot/jquery.flot.tooltip.min',
									'lib/flot/jquery.flot.resize',
									'lib/easy-pie-chart/jquery.easy-pie-chart',
									'lib/typeahead.js/typeahead.min',
									'lib/typeahead.js/hogan-2.0.0',
									'lib/bootstrap-switch/js/bootstrap-switch.min',
									'lib/bootbox/bootbox.min',
									'pages/ebro_user_profile',
									'pages/ebro_wizard',
									'lib/FooTable/js/footable',
									'lib/FooTable/js/footable.sort',
									'lib/FooTable/js/footable.filter',
									'lib/FooTable/js/footable.paginate',
									'pages/ebro_responsive_table',
									'lib/magnific-popup/jquery.magnific-popup.min',
									'lib/magnific-popup/jquery.magnific-popup',
									'pages/ebro_gallery',
									'lib/fullcalendar/fullcalendar',
									'lib/fullcalendar/fullcalendar.min',
									'common',
									'lib/jquery-steps/jquery.steps.min',
									
									'lib/chained/jquery.chained.remote.min',
									'lib/jquery.inputmask/jquery.inputmask.bundle.min',
									'lib/parsley/parsley.min',
									'pages/ebro_form_validate',
									'lib/datepicker/js/bootstrap-datepicker',
									'lib/timepicker/js/bootstrap-timepicker.min',
									'lib/jasny_plugins/bootstrap-fileupload',
									'pages/ebro_form_extended',
									'pages/ebro_notifications',
									'lib/select2/select2.min',
                                                                        
									)
							);
			//echo $this->Html->script('jquery.accordion');							

		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');


?>

<script>
$(window).load(function() {
<?php if($mode == 1){?>
			$('.user_form .editable p').hide();
			$('.user_form .editable div').hide();
			$('.edit_form').hide();
			$('.view_form').show();
			$('#mycl-det').addClass('view');
			$('.user_form .editable .hidden_control,.user_form .form_submit').show();
	
	<?php }?>
	
	
	
});
</script>
     <link href='http://fonts.googleapis.com/css?family=Roboto:300,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	
</head>
<body class="boxed pattern_1">
<div id="wrapper_all">
<input type="hidden" id="hidden_site_baseurl" value="<?php echo $this->request->base . ((!is_null($this->params['language'])) ? '/' . $this->params['language'] : ''); ?>"  />
<input type="hidden" id="hidden_site_webroot" value="<?php echo $this->webroot; ?>" />
<input type="hidden" id="hidden_site_public_url" value="<?php echo $this->Html->url('/', true); ?>" />
<input type="hidden" id="hidden_site_admin_url" value="<?php echo $this->Html->url(array('controller' => 'dashboard', 'action' => 'index', 'admin' => true), true); ?>" />    

    <?php echo $this->element('header'); 
			
		
	?>




<nav id="mobile_navigation"></nav>
			
			

  
  <section class="container clearfix main_section">
  		<div id="main_content_outer" class="clearfix">
        	<div id="main_content">
            
            
            <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">
                        
                         <?php
							echo $this->Html->getCrumbs(' <i class="icon-double-angle-right"></i> ', array(
								'text' => '<span aria-hidden="true" class="icon icon-home"></span>',
								'class' => 'current',
								'url' => array('controller' => 'users', 'action' => 'dashboard'),
								
								'escape' => false
							));
							?>
                        
                        </h3>
                       
                    </div>
                </div>
              	<?php echo $this->Session->flash(); ?>
            	<?php echo $this->fetch('content'); ?>
            </div>
        </div>
        
        <?php  echo $this->element('sidebar'); 	?>
  </section> 
  <div id="footer_space"></div>            
</div> <!-- wrapper_all -->
 <?php echo $this->element('footer'); ?>

	
    
	<!-- Js writeBuffer -->
<?php
	if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer();
	// Writes cached scripts
?>
<?php echo $this->element('sql_dump')

?>

</body>
</html>