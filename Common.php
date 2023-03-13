<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @link: https://codeigniter4.github.io/CodeIgniter4/
 */

 function view(string $name, array $data = [], array $options = [],$theme=''): string
	{
		/**
		 * @var CodeIgniter\View\View $renderer
		 */
		$renderer = \CodeIgniter\Services::renderer();

		$saveData = config(View::class)->saveData;

		if (array_key_exists('saveData', $options))
		{
			$saveData = (bool) $options['saveData'];
			unset($options['saveData']);
		}
		$data['theme_path'] = '' ;
		if($theme!=''){
			$name = $theme.'/'.$name;
			$data['theme_path']=$theme.'/';	
		}
		elseif(defined('THEME')){
			if(THEME!=''){
				$name = THEME.'/'.$name;
				$data['theme_path']= $theme.'/';
			}
		}
		if(!defined('THEME_PATH')){
			define('THEME_PATH',$data['theme_path']);
		}
		
		
		return $renderer->setData($data, 'raw')
						->render($name, $options, $saveData);
	}