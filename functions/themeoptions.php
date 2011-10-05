<?php
$shortname = 'phototheme';
require_once(dirname(__FILE__).'/photoThemeOptions.php');

global $PTopts,$PTOptionHandler;

$PTopts = array(
	//array('name' => '','desc' => '','id' => '','default' => '','type' => '')
	array('name' => 'Title Goes Here','id' => 'testTitle','type' => 'title'),
	array('name' => 'Text Input','desc' => 'This is what a text input will look like.','id'=>'textInput','default' => 'Yo momma', 'type' => 'text'),
	array('name' => 'Password Input','desc' => 'This is what a password input will look like.','id'=>'passwordInput','default' => '222555111', 'type' => 'password'),
	array('name' => 'SubTitle Goes Here','id' => 'testSubtitle','type' => 'subtitle'),
	array('name' => 'Text Area','desc' => 'This is what a textarea will look like.','id'=>'textArea','default' => 'Some text.', 'type' => 'textarea'),
	array('name' => 'Radio Option','id' => 'radioTest','type' => 'radio','default' => 'Value 2','options' => array(
		array('name' => 'Option 1','value' => 'Value 1'),
		array('name' => 'Option 2','value' => 'Value 2'),
		array('name' => 'Option 3','value' => 'Value 3')
	)),
	array('name' => 'Select Option','id' => 'selectTest','type' => 'select','default' => 'Value 2','options' => array(
		array('name' => 'Option 1','value' => 'Value 1'),
		array('name' => 'Option 2','value' => 'Value 2'),
		array('name' => 'Option 3','value' => 'Value 3')
	)),
	array('id' => 'checkboxTest','name' => 'Checkbox Test','desc' => 'This is what a checkbox will look like','default' => TRUE,'type' => 'checkbox'),
	array('id' => 'fileUploadTest','name' => 'File Upload','desc' => 'This is what a file upload looks like.','type' => 'file'),
	array('id' => 'fileUploadTest2','name' => 'File Upload2','desc' => 'This is what a file upload looks like.','type' => 'file')
);

//print_r($PTopts);

$PTOptionHandler = new photoThemeOptions('Photo Theme',$shortname,$PTopts);


function phototheme_admin()	{
	global $shortname;
	add_theme_page('PhotoTheme Options','PhotoTheme Options','update_themes',$shortname,'phototheme_options');
}

function phototheme_options()	{
	global $PTOptionHandler;
	
	//print_r($PTOptionHandler);
	
	$PTOptionHandler->handleForm();
	echo $PTOptionHandler->getForm();
}

function photoTheme_option($name)	{
	global $PTOptionHandler;
	return $PTOptionHandler->getOption($name);
}

function phototheme_table_style()	{
	?>
	<style>
		table.form-table *	{vertical-align: top;}	
	</style>
	<?
}


add_action('admin_menu','phototheme_admin');

add_action('admin_head','phototheme_table_style');
