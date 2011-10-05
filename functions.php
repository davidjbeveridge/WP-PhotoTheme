<?php
$includefiles = glob(dirname(__FILE__).'/functions/*.php');
foreach($includefiles as $include)	{
	include_once($include);
}
