<?php
/**
* @package   com_zoo Component
* @file      renderer.php
* @version   2.4.10 June 2011
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2011 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

/*
   Class: RendererHelper
   	  Renderer helper class.
*/
class RendererHelper extends AppHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($app) {
		parent::__construct($app);

		// register paths
		$this->app->path->register($this->app->path->path('classes:renderer'), 'renderer');

	}

	/*
		Function: create
			Creates a Renderer instance

		Parameters:
			$type - Renderer type

		Returns:
			AppRenderer
	*/
	public function create($type = '', $args = array()) {

		// load renderer class
		$class = $type ? $type.'Renderer' : 'AppRenderer';
		if ($type) {
			$this->app->loader->register($class, 'renderer:'.strtolower($type).'.php');
		}

		// prepend app
		array_unshift($args, $this->app);

		return $this->app->object->create($class, $args);

	}

}

/*
	Class: AppRenderer
		The general class for rendering objects.
*/
class AppRenderer {

	protected $_path;
	protected $_layout;
	protected $_folder = 'renderer';
	protected $_separator = '.';
	protected $_extension = '.php';
	protected $_metafile = 'metadata.xml';

    /*
		Variable: app
			App instance.
    */
	public $app;

	const MAX_RENDER_RECURSIONS = 100;

	public function  __construct($app, $path = null) {
		$this->_path = $path ? $path : $app->object->create('PathHelper', array($this->app));
	}

	/*
		Function: render
			Render objects using a layout file.

		Parameters:
			$layout - Layout name.
			$args - Arguments to be passed to into the layout scope.

		Returns:
			String
	*/
	public function render($layout, $args = array()) {

		// prevent render to recurse indefinitely
		static $count = 0;
		$count++;

		if ($count < self::MAX_RENDER_RECURSIONS) {

			// init vars
			$parts = explode($this->_separator, $layout);
			$this->_layout = preg_replace('/[^A-Z0-9_\.-]/i', '', array_pop($parts));

			// render layout
			if ($__layout = $this->_path->path('default:'.implode('/', $parts).'/'.$this->_layout.$this->_extension)) {

				// import vars and layout output
				extract($args);
				ob_start();
				include($__layout);
				$output = ob_get_contents();
				ob_end_clean();

				$count--;

				return $output;
			}

			$count--;

			// raise warning, if layout was not found
			JError::raiseWarning(0, 'Renderer Layout "'.$layout.'" not found. ('.$this->app->utility->debugInfo(debug_backtrace()).')');

			return null;
		}

		// raise warning, if render recurses indefinitly
		JError::raiseWarning(0, 'Warning! Render recursed indefinitly. ('.$this->app->utility->debugInfo(debug_backtrace()).')');

		return null;
	}

	/*
		Function: addPath
			Add layout path(s) to renderer.

		Parameters:
			$paths - String or array of paths.

		Returns:
			Renderer
	*/
	public function addPath($paths) {

		$paths = (array) $paths;

		foreach ($paths as $path) {
			$path = rtrim($path, "\\/") . '/';
			$this->_path->register($path . $this->_folder);
		}

		return $this;

	}

	/*
		Function: getLayouts
			Retrieve an array of layout filenames.

		Returns:
			Array
	*/
	public function getLayouts($dir) {

		// init vars
		$layouts = array();

		// find layouts in path(s)
		$layouts = $this->_path->files("default:$dir", false, '/' . preg_quote($this->_extension) . '$/i');

		return array_map(create_function('$layout', 'return basename($layout, "'.$this->_extension.'");'), $layouts);
	}

	/*
		Function: getLayoutMetaData
			Retrieve metadata array of a layout.

		Returns:
			Array
	*/
	public function getLayoutMetaData($layout) {

		// init vars
		$metadata = $this->app->object->create('AppData');
		$parts    = explode($this->_separator, $layout);
		$name     = array_pop($parts);

		if ($file = $this->_path->path('default:'.implode(DIRECTORY_SEPARATOR, $parts).'/'.$this->_metafile)) {
			if ($xml = $this->app->xml->loadFile($file)) {
				foreach ($xml->children() as $child) {
					$attributes = $child->attributes();
					if ($child->getName() == 'layout' && (string) $attributes->name == $name) {

						foreach ($attributes as $key => $attribute) {
							$metadata[$key] = (string) $attribute;
						}

						$metadata['layout'] = $layout;
						$metadata['name'] = (string) $child->name;
						$metadata['description'] = (string) $child->description;

						break;
					}
				}
			}
		}

		return $metadata;
	}

	/*
		Function: getFolder
			Retrieve the renderers folder.

		Returns:
			String
	*/
	public function getFolder() {
		return $this->_folder;
	}

	/*
		Function: _getPath
			Retrieve paths where to find the layout files.

		Returns:
			Array
	*/
	protected function _getPath($dir = '') {
		return $this->_path->path('default:'.$dir);
	}

}