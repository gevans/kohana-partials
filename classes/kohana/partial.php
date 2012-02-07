<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Partials are similar to views and allow you to separate view logic into more
 * manageable chunks. Like views, variables can be assigned with the partial
 * object and referenced locally within partials.
 *
 * @package    Kohana
 * @category   Partials
 * @author     Gabriel Evans <gabriel@codeconcoction.com>
 * @copyright  (c) 2011 Gabriel Evans
 * @license    http://www.opensource.org/licenses/mit-license.php
 */
class Kohana_Partial extends Kohana_View {

	/**
	 * @var  string  Variable name of a single item when rendering a collection
	 */
	protected $_collection_name = NULL;

	/**
	 * @var  string  Collection to be rendered in the partial
	 */
	protected $_collection = NULL;

	/**
	 * @var  string  Content to be inserted between each item in the collection
	 */
	protected $_spacer = NULL;

	/**
	 * Returns a new Partial object. The base filename will be prefixed with an
	 * underscore (e.g. `path/to/file` becomes `path/to/_file`).
	 *
	 *     $partial = Partial::factory($file);
	 *
	 * @param   string  $file  view filename
	 * @param   array   $data  array of values
	 * @return  Partial
	 * @throws  View_Exception
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new Partial($file, $data);
	}

	/**
	 * Sets the initial view filename and local data. Partials should almost
	 * always only be created using [Partial::factory].
	 *
	 *     $partial = new Partial($file);
	 *
	 * @param   string  $file  view filename
	 * @param   array   $data  array of values
	 * @return  void
	 * @uses    Partial::set_filename
	 */
	public function __construct($file = NULL, array $data = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if ($data !== NULL AND count($data) === 1 AND Arr::is_array($data[key($data)]))
		{
			// Automatically set the collection
			$this->collection($data[key($data)], Inflector::singular(key($data)));
		}
		elseif ($data !== NULL)
		{
			// Add the values to the current data
			$this->_data = $data + $this->_data;
		}
	}

	/**
	 * Sets the filename of the partial.
	 *
	 *     $partial->set_filename($file);
	 *
	 * @param   string  $file  partial filename
	 * @return  Partial
	 * @throws  View_Exception
	 */
	public function set_filename($file)
	{
		// Prepend an underscore to the filename of the view
		return parent::set_filename(dirname($file).DIRECTORY_SEPARATOR.'_'.basename($file));
	}

	/**
	 * Allows rendering a partial between each item in a collection.
	 *
	 * @param   string  $spacer  partial filename
	 * @return  string  spacer, if `$spacer` is `NULL`
	 * @return  $this
	 * @throws  View_Exception
	 */
	public function spacer($spacer = NULL)
	{
		if ($spacer === NULL)
		{
			return $this->_spacer;
		}

		$this->_spacer = (string) $spacer;

		return $this;
	}

	/**
	 * Gets or sets the collection name of the partial.
	 *
	 * @param   string  $collection_name  Collection name
	 * @return  mixed
	 */
	public function collection_name($collection_name = NULL)
	{
		if ($collection_name === NULL)
		{
			return $this->_collection_name;
		}

		if ($this->_collection === NULL)
		{
			throw new View_Exception('You must set the collection to use within your partial before setting a collection name');
		}

		$this->_collection_name = $collection_name;
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
	 * @param   array   $collection       collection
	 * @param   string  $collection_name  collection name
	 * @return  $this
	 * @throws  View_Exception
	 */
	public function collection($collection = NULL, $collection_name = NULL)
	{
		if ($collection === NULL)
		{
			return $this->_collection;
		}

		if ( ! Arr::is_array($collection))
		{
			throw new View_Exception('A collection must be iterable.');
		}

		if ($collection_name !== NULL)
		{
			$this->_collection_name = $collection_name;
		}

		$this->_collection = $collection;

		return $this;
	}

	/**
	 * Renders the partial object to a string. Global and local data are merged
	 * and extracted to create local variables within the view file.
	 *
	 *     $output = $partial->render();
	 *
	 * When rendering a collection, additional variables are set that allow
	 * access to the current item (e.g. `$product`) and the count of the
	 * iteration (e.g. `$product_counter`).
	 *
	 * @param    string  $file  partial filename
	 * @return   string
	 * @throws   View_Exception
	 * @uses     Partial::capture
	 * @uses     View::capture
	 */
	public function render($file = NULL)
	{
		if ($this->_collection !== NULL AND ! empty($this->_collection))
		{
			return $this->render_collection();
		}

		return parent::render($file);
	}

	/**
	 * Iterates over a collection and returns the combined, rendered output of
	 * each item.
	 *
	 * @return   string
	 * @throws   View_Exception
	 * @uses     Partial::capture
	 */
	protected function render_collection()
	{
		$output = '';

		if ($this->_collection_name === NULL)
		{
			// Default to the basename of the partial filename
			$this->_collection_name = substr(basename($this->_file, EXT), 1);
		}

		$collection_name  = $this->_collection_name;
		$collection_count = count($this->_collection);

		$i = 0;

		foreach ($this->_collection as $item)
		{
			$view_data = array(
				$collection_name            => $item,
				$collection_name.'_counter' => $i,
			);

			$output .= Partial::capture($this->_file, Arr::merge($this->_data, $view_data));

			if ($collection_count !== $i + 1 AND $this->_spacer !== NULL)
			{
				// Insert the spacer
				$output .= $this->_spacer;
			}

			$i++;
		}

		return $output;
	}

} // End Partial
