<?php defined('SYSPATH') or die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests the Partial class
 *
 * @group kohana
 * @group kohana.view
 *
 * @package
 */
class PartialTest extends Unittest_TestCase {

	protected static $old_modules = array();

	/**
	 * Sets up the filesystem for testing partials.
	 *
	 * @return null
	 */
	public static function setupBeforeClass()
	{
		self::$old_modules = Kohana::modules();

		$new_modules = self::$old_modules+array(
			'test_views' => realpath(__DIR__.'/../test_data/')
		);
		Kohana::modules($new_modules);
	}

	/**
	 * Restores the module list.
	 *
	 * @return null
	 */
	public static function teardownAfterClass()
	{
		Kohana::modules(self::$old_modules);
	}

	/**
	 * Tests that a collection is required before a name can be set.
	 *
	 * @expectedException View_Exception
	 */
	public function test_collection_name_requires_collection()
	{
		$partial = new Partial;
		$partial->collection_name('foo');
	}

	/**
	 * Provider for test_collection
	 *
	 * @return array
	 */
	public function provider_collection()
	{
		return array(
			array(
				'foo/bar',
				array(
					'bar 1',
					'bar 2',
					'bar 3',
				),
				NULL,
				'bar',
			),
			array(
				'foo/baz',
				array(
					'baz 1',
					'baz 2',
					'baz 3',
				),
				'hello',
				'hello',
			),
		);
	}

	/**
	 * Tests collection naming
	 *
	 * @test
	 * @dataProvider provider_collection
	 *
	 * @return void
	 */
	public function test_collection($file, array $collection, $collection_name, $expected_collection_name)
	{
		$partial = Partial::factory($file)
			->collection($collection, $collection_name);

		$partial->render();

		$this->assertSame($expected_collection_name, $partial->collection_name());
	}

	/**
	 * Provider for test_collection
	 *
	 * @return array
	 */
	public function provider_spacer()
	{
		return array(
			array(
				'foo/bar',
				array(
					'bars' => array('one', 'two', 'three'),
				),
				' ',
				'one two three',
			),
		);
	}

	/**
	 * Tests collection spacers.
	 *
	 * @test
	 * @dataProvider provider_spacer
	 *
	 * @return  void
	 */
	public function test_spacer($file, $data, $spacer, $expected_output)
	{
		$partial = Partial::factory($file, $data)
			->spacer($spacer);

		$this->assertEquals($expected_output, $partial->render());
	}

}
