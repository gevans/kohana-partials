<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Similar to views, allows you to separate view logic into more manageable
 * chunks. Like views, variables can be assigned with the partial object and
 * referenced locally within partials.
 *
 * @package    Kohana
 * @category   Partials
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2011 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Kohana_Partial extends Kohana_View {

	// Filename of partial
	protected $_partial = '';

	// Collection to be rendered in partial
	protected $_collection = NULL;

	/**
	 * Returns a new Partial object. You must define the "file" parameter.
	 * The base filename will be prefixed with an underscore.
	 *
	 *     $partial = Partial::factory($file);
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  Partial
	 * @throws  Kohana_View_Exception
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new Partial($file, $data);
	}

	protected function __construct($file = NULL, array $data = NULL)
	{
		if ($file === NULL)
		{
			throw new Kohana_View_Exception('You must specify a filename for the partial');
		}

		$this->_partial = $file;

		return parent::__construct(dirname($file).DIRECTORY_SEPARATOR.'_'.basename($file), $data);
	}

	/**
	 * Allows rendering a partial for each item in a provided collection.
	 * This is the same as wrapping `Partial::factory()` in a `foreach`
	 * and setting a variable for each item.
	 *
	 *     $partial = Partial::factory($file)->collection($collection);
	 *
	 * You can access the variable in your views by the base filename (e.g. if
	 * your partial is named `item` the variable is `$item`).
	 *
	 * [!!] Collections must contain one or more items.
	 *
	 * @param   array  collection
	 * @return  $this
	 * @throws  Kohana_View_Exception
	 */
	public function collection(array $collection = NULL)
	{
		if (empty($collection))
		{
			throw new Kohana_View_Exception('A collection cannot be empty');
		}

		$this->_collection = $collection;

		return $this;
	}

	public function render($file = NULL)
	{
		if ($this->_collection !== NULL)
		{
			$output = '';

			foreach ($this->_collection as $item)
			{
				// Render the partial for each item and store it in output
				$output .= Partial::factory($this->_partial, array(basename($this->_partial) => $item))->render();
			}

			return $output;
		}
		else
		{
			return parent::render();
		}
	}

} // End Partial
