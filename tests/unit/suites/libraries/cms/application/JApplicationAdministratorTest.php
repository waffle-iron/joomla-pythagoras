<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\CMS\Application\Administrator;
use Joomla\Registry\Registry;

/**
 * Test class for Joomla\CMS\Application\Administrator.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Application
 * @since       3.2
 */
class JApplicationAdministratorTest extends TestCaseDatabase
{
	/**
	 * Value for test host.
	 *
	 * @var    string
	 * @since  3.2
	 */
	const TEST_HTTP_HOST = 'mydomain.com';

	/**
	 * Value for test user agent.
	 *
	 * @var    string
	 * @since  3.2
	 */
	const TEST_USER_AGENT = 'Mozilla/5.0';

	/**
	 * Value for test user agent.
	 *
	 * @var    string
	 * @since  3.2
	 */
	const TEST_REQUEST_URI = '/index.php';

	/**
	 * An instance of the class to test.
	 *
	 * @var    Administrator
	 * @since  3.2
	 */
	protected $class;

	/**
	 * Backup of the SERVER superglobal
	 *
	 * @var    array
	 * @since  3.4
	 */
	protected $backupServer;

	/**
	 * Data for fetchConfigurationData method.
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public function getRedirectData()
	{
		return array(
			// Note: url, base, request, (expected result)
			array('/foo', 'http://mydomain.com/', 'http://mydomain.com/index.php?v=3.2', 'http://mydomain.com/foo'),
			array('foo', 'http://mydomain.com/', 'http://mydomain.com/index.php?v=3.2', 'http://mydomain.com/foo'),
		);
	}

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		JFactory::$document = $this->getMockDocument();
		JFactory::$language = $this->getMockLanguage();
		JFactory::$session  = $this->getMockSession();

		$this->backupServer = $_SERVER;

		$_SERVER['HTTP_HOST'] = self::TEST_HTTP_HOST;
		$_SERVER['HTTP_USER_AGENT'] = self::TEST_USER_AGENT;
		$_SERVER['REQUEST_URI'] = self::TEST_REQUEST_URI;
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		// Set the config for the app
		$config = new Registry;
		$config->set('session', false);

		// Get a new Administrator instance.
		$this->class = new Administrator($this->getMockInput(), $config);
		TestReflection::setValue('Joomla\\CMS\\Application\\AbstractCMS', 'instances', array('administrator' => $this->class));
	}

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   3.2
	 */
	protected function tearDown()
	{
		// Reset the application instance.
		TestReflection::setValue('Joomla\\CMS\\Application\\AbstractCMS', 'instances', array());

		$_SERVER = $this->backupServer;

		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  PHPUnit_Extensions_Database_DataSet_CsvDataSet
	 *
	 * @since   3.2
	 */
	protected function getDataSet()
	{
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');

		$dataSet->addTable('jos_extensions', JPATH_TEST_DATABASE . '/jos_extensions.csv');
		$dataSet->addTable('jos_menu', JPATH_TEST_DATABASE . '/jos_menu.csv');
		$dataSet->addTable('jos_menu_types', JPATH_TEST_DATABASE . '/jos_menu_types.csv');
		$dataSet->addTable('jos_template_styles', JPATH_TEST_DATABASE . '/jos_template_styles.csv');
		$dataSet->addTable('jos_usergroups', JPATH_TEST_DATABASE . '/jos_usergroups.csv');
		$dataSet->addTable('jos_users', JPATH_TEST_DATABASE . '/jos_users.csv');
		$dataSet->addTable('jos_viewlevels', JPATH_TEST_DATABASE . '/jos_viewlevels.csv');

		return $dataSet;
	}

	/**
	 * Tests the AbstractCMS::getClientId method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetClientId()
	{
		$this->assertSame(1, $this->class->getClientId());
	}

	/**
	 * Tests the AbstractCMS::getName method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetName()
	{
		$this->assertSame('administrator', $this->class->getName());
	}

	/**
	 * Tests the AbstractCMS::getMenu method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetMenu()
	{
		$this->assertInstanceOf('JMenuAdministrator', $this->class->getMenu());
	}

	/**
	 * Tests the AbstractCMS::getPathway method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetPathway()
	{
		$this->assertNull($this->class->getPathway());
	}

	/**
	 * Tests the Administrator::getRouter method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetRouter()
	{
		$this->assertInstanceOf('\\Joomla\\CMS\\Router\\Administrator', $this->class->getRouter());
	}

	/**
	 * Tests the Administrator::getTemplate method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testGetTemplate()
	{
		$this->markTestSkipped('Test fails due to JPATH_THEMES being defined for site.');

		$template = $this->class->getTemplate(true);

		$this->assertInstanceOf('\\Joomla\\Registry\\Registry', $template->params);

		$this->assertEquals('isis', $template->template);
	}

	/**
	 * Tests the AbstractCMS::isAdmin method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testIsAdmin()
	{
		$this->assertTrue($this->class->isAdmin());
	}

	/**
	 * Tests the AbstractCMS::isSite method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testIsSite()
	{
		$this->assertFalse($this->class->isSite());
	}

	/**
	 * Tests the AbstractCMS::render method.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function testRender()
	{
		JFactory::$application = $this->class;

		$document = $this->getMockDocument();

		$this->assignMockReturns($document, array('render' => 'JWeb Body'));

		// Manually inject the document.
		TestReflection::setValue($this->class, 'document', $document);

		TestReflection::invoke($this->class, 'render');

		$this->assertEquals(array('JWeb Body'), TestReflection::getValue($this->class, 'response')->body);
	}
}
