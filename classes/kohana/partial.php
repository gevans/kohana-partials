<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Partial extends Kohana_View {

	// Filename of view partial
	protected $_partial = '';

	// Collection to be rendered in partial
	protected $_collection = NULL;

	/**
	 * Prepends a specified partial's name with an underscore and returns a
	 * View object.
	 *
	 *     // Using:
	 *     $view = View::partial('cart/items');
	 *     // Becomes:
	 *     $view = View::factory('cart/_items');
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  View
	 * @throws  Kohana_View_Exception
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new Partial($file, $data);
	}

	public function __construct($file = NULL, array $data = NULL)
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
	 *
	 *     $partial = Partial::factory('products/_product')->collection($products);
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
