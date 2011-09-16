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

	// Variable name of a single item when rendering a collection
	protected $_collection_name = NULL;

	// Collection to be rendered in partial
	protected $_collection = NULL;

	// Spacer to be rendered between items in a collection
	protected $_spacer = NULL;

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

	public function __construct($file = NULL, array $data = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if ($data !== NULL AND count($data) === 1 AND Arr::is_array($data[key($data)]))
		{
			$this->_collection_name = Inflector::singular(key($data));
			$this->_collection      = $data[key($data)];
		}
		else
		{
			parent::__construct(NULL, $data);
		}
	}

	public function set_filename($file)
	{
		// Prepend an underscore to the filename of the view
		return parent::set_filename(dirname($file).DIRECTORY_SEPARATOR.'_'.basename($file));
	}

	/**
	 * Allows rendering a partial between each item in a collection.
	 *
	 * @param   string  partial filename
	 * @return  $this
	 * @throws  Kohana_View_Exception
	 */
	public function spacer($file)
	{
		$this->_spacer = (string) Partial::factory($file);

		return $this;
	}

	/**
	 * Allows rendering a partial for each item in a provided collection.
	 * This is the same as wrapping `Partial::factory()` in a `foreach`
	 * and setting a variable for each item.
	 *
	 *     $partial = Partial::factory($file)->collection($collection);
	 *
	 * You can access the variable in your views by the base filename (e.g. if
	 * your partial is named `product` the variable is `$product`).
	 *
	 * [!!] Collections must contain one or more items.
	 *
	 * @param   array  collection
	 * @return  $this
	 * @throws  Kohana_View_Exception
	 */
	public function collection($collection = NULL)
	{
		if ( ! Arr::is_array($collection))
		{
			throw new Kohana_View_Exception('A collection must be iteratable.');
		}
		else
		{
			$this->_collection_name = substr(basename($this->_file, EXT), 1);
			$this->_collection      = $collection;
		}

		return $this;
	}

	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
		}

		if ($this->_collection_name !== NULL AND $this->_collection !== NULL)
		{
			$output = '';

			$collection_count = count($this->_collection);

			$i = 0;

			foreach ($this->_collection as $item)
			{
				$view_data = array(
					$this->_collection_name            => $item,
					$this->_collection_name.'_counter' => $i,
				);

				$output .= Partial::capture($this->_file, Arr::merge($this->_data, $view_data));

				if ($i !== $collection_count AND $this->_spacer !== NULL)
				{
					// Capture the spacer
					$output .= $this->_spacer;
				}

				$i++;
			}

			return $output;
		}

		return parent::render();
	}

} // End Partial
