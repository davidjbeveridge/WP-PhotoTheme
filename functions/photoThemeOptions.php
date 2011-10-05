<?php
class photoThemeOptions {
	var $themename;
	var $options;
	var $shortname;
	
	function photoThemeOptions($name,$sname,$opt)	{
		$this->themename = $name;
		$this->shortname = $sname;
		$this->options = array();
		foreach($opt as $field)	{
			$field['id'] = $this->shortname.'_'.$field['id'];
			$this->options[$field['id']] = $field;
		}
		
	}
	function __construct($name,$sname,$opt)	{
		$this->photoThemeOptions($name, $sname, $opt);
	}
	
	function getForm()	{
		$output = '
		<div class="icon32" id="icon-options-general"><br></div>
		<div class="wrap">
		<h2>'.$this->themename.' Options</h2>
			<form enctype="multipart/form-data" method="post" action="'.get_bloginfo('wpurl').'/wp-admin/themes.php?page='.$this->shortname.'">
				<table class="form-table">';
		foreach($this->options as $field)	{
			if(method_exists($this,'output_'.$field['type']))	{
				$output .= $this->{'output_'.$field['type']}($field);
			}
		}
		$output .= 
		'<tr>
			<td></td>
			<td>
			</td>
		</tr>';
		$output .= '
				</table>
				<p>
					<input type="submit" name="save" value="Save Options" class="button-primary" />
					<input type="submit" name="reset" value="Reset Defaults" class="button-secondary" />
				</p>
			</form>
		</div>';
		return $output;
	}
	
	function handleForm()	{
		if(isset($_REQUEST['save']))	{
			$this->saveForm();
		}
		elseif(isset($_REQUEST['reset']))	{
			$this->resetForm();
		}
	}
	
	function saveForm()	{
		foreach($this->options as $option)	{
			if($option['type'] == 'checkbox')	{
				if(!isset($_REQUEST[$option['id']]))	{
					update_option($option['id'],FALSE);
				}
			}
		}
		foreach($_REQUEST as $field => $value)	{
			if(method_exists($this,'handle_'.$this->options[$field]['type']))	{
				$this->{'handle_'.$this->options[$field]['type']}($this->options[$field]);
			}
		}
	}
	
	function resetForm()	{
		foreach($this->options as $option)	{
			if($option['type'] != 'title' && $option['type'] != 'subtitle')	{
				if(isset($option['default']))	{
					update_option($this->shortname.$option['id'],$option['default']);
				}
				else	{
					update_option($this->shortname.$option['id'],'');
				}
			}
		}
	}
	
	function getOption($id)	{
		if(isset($this->options[$id]) && method_exists($this,'get_'.$this->options[$id]['type']))	{
			return $this->{'get_'.$this->options[$id]['type']}($this->options[$id]);
		}
	}
	
	
	/*
	 * Begin Output Functions
	 */
	function output_text($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$output .= '<input type="text" class="regular-text" id="'.$field['id'].'" name="'.htmlentities($field['id']).'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
		if(isset($field['desc']) && !empty($field['desc']))	{
			$output .= '<span class="description">'.$field['desc'].'</span>';
		}
		$output .= '</td></tr>';
		return $output;
	}
	function output_password($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$output .= '<input type="password" id="'.$field['id'].'" name="'.htmlentities($field['id']).'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
		if(isset($field['desc']) && !empty($field['desc']))	{
			$output .= '<span class="description">'.$field['desc'].'</span>';
		}
		$output .= '</td></tr>';
		return $output;
	}
	function output_textarea($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$output .= '<textarea cols="50" rows="10" class="regular-text" id="'.$field['id'].'" name="'.htmlentities($field['id']).'">'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'</textarea>';
		if(isset($field['desc']) && !empty($field['desc']))	{
			$output .= '<span class="description">'.$field['desc'].'</span>';
		}
		$output .= '</td></tr>';
		return $output;
	}
	function output_radio($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$i = 0;
		$currentValue = $this->getOption($field['id']);
		foreach($field['options'] as $rb)	{
			$output .= '<label for="'.$field['id'].++$i.'">';
			$output .= '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].$i.'" value="'.$rb['value'].'"';
			if($rb['value'] == $currentValue)	{
				$output .= ' checked="checked"';
			}
			$output .= ' />';
			$output .= $rb['name'];
			$output .= (isset($rb['desc']) ? '<span class="description">'.$rb['desc'].'</span>' : '').'
			</label><br />';
		}
		$output .= '</td></tr>';
		return $output;
	}
	function output_select($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$i = 0;
		$currentValue = $this->getOption($field['id']);
		$output .= '<select name="'.$field['id'].'">';
		foreach($field['options'] as $rb)	{
			$output .= '<option value="'.$rb['value'].'"';
			if($rb['value'] == $currentValue)	{
				$output .= ' selected="selected"';
			}
			if(isset($rb['desc']))	{
				$output .= ' title="'.$rb['desc'].'"';
			}
			$output .= '>';
			$output .= $rb['name'];
			$output .= "</option>";
		}
		$output .= '</select>';
		$output .= '</td></tr>';
		return $output;
	}
	function output_checkbox($field)	{
		$output = '<tr><th></th><td>';
		$output .= '<label for="'.$field['id'].'"><input type="checkbox" id="'.$field['id'].'" name="'.$field['id'].'"'.($this->getOption($field['id']) ? ' checked="checked"' : '').' />'.$field['name'].'</label>';
		if(isset($field['desc']))	{
			$output .= '<br /><span class="description">'.$field['desc'].'</span>';
		}
		$output .= '</td></tr>';
		return $output;
	}
	function output_file($field)	{
		$output = '<tr><th>';
		$output .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
		$output .= '</th><td>';
		$output .= '<img id="'.$field['id'].'-image" style="width: 100px;float: left; border: solid 1px #ccc; margin-right: 10px;" src="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : get_bloginfo('template_url').'/images/no-image-100.png').'" />';
		$output .= '<input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
		if(isset($field['desc']) && !empty($field['desc']))	{
			$output .= '<span class="description">'.$field['desc'].'</span>';
		}
		$output .= '<br /><input type="button" value="Select Image" class="button-primary" id="'.$field['id'].'-button" />';
		$output .= '
			<script type="text/javascript" />//<![CDATA[
				(function($){
					$(window).load(function(){
						$("#'.$field['id'].'-button").click(function(e){
							window.send_to_editor = function(html){
								var imgUrl = $("img",html).attr("src");
								$("#'.$field['id'].'").val(imgUrl)
								$("#'.$field['id'].'-image").attr("src",imgUrl);
								tb_remove();
							};
							tb_show("","'.get_bloginfo('wpurl').'/wp-admin/media-upload.php?post_id=0&type=image&TB_iframe=true");
							return false;
						});
					});
				})(jQuery)
				//]]>
			</script>';
		$output .= '</td></tr>';
		return $output;
	}
	function output_title($field)	{
		return '<tr><td colspan="2"><h2>'.$field['name'].'</h2></td></tr>';
	}
	function output_subtitle($field)	{
		return '<tr><td colspan="2"><h3>'.$field['name'].'</h3></td></tr>';
	}
	function output_paragraph($field)	{
		return '<tr><td colspan="2"><p>'.$field['name'].'</p></td></tr>';
	}
	
	/*
	 * Begin Handler Functions
	 */
	function handle_text($field)	{
		if(isset($_REQUEST[$field['id']]))	{
			update_option($field['id'],esc_attr($_REQUEST[$field['id']]));
		}
	}
	function handle_password($field)	{
		$this->handle_text($field);
	}
	function handle_textarea($field)	{
		$this->handle_text($field);
	}
	function handle_radio($field)	{
		$this->handle_text($field);
	}
	function handle_select($field)	{
		$this->handle_text($field);
	}
	function handle_checkbox($field)	{
		/**
		 * TODO: fix updating; doesn't work right.
		 */
		if(isset($_REQUEST[$field['id']]))	{
			update_option($field['id'],TRUE);
		}
	}
	function handle_file($field)	{
		$this->handle_text($field);
	}

	/*
	 * Begin Getter Functions
	 */
	function get_text($field)	{
		$default = isset($field['default']) ? $field['default'] : '';
		return $this->unescape(get_option($field['id'],$default));
	}
	function get_password($field)	{
		return $this->get_text($field);
	}
	function get_textarea($field)	{
		return $this->get_text($field);
	}
	function get_radio($field)	{
		return $this->get_text($field);
	}
	function get_select($field)	{
		return $this->get_text($field);
	}
	function get_checkbox($field)	{
		return ($value = $this->get_text($field)) ? TRUE : FALSE;
	}
	function get_file($field)	{
		return $this->get_text($field);
	}
	
	function unescape($string)	{
		//echo 'Magic Quotes GPC: '.(get_magic_quotes_gpc() ? 'On' : 'Off');
		if(get_magic_quotes_gpc() OR get_magic_quotes_runtime())	{
			return stripslashes( stripslashes( esc_attr( $string ) ) );
		}
		return stripslashes( esc_attr( $string ) );
	}
}
