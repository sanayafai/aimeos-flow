<?php

/**
 * @license LGPLv3, http://www.gnu.org/copyleft/lgpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package flow
 * @subpackage Controller
 */


namespace Aimeos\Shop\Controller;

use TYPO3\Flow\Annotations as Flow;


/**
 * Aimeos controller for the JSON REST API
 *
 * @package flow
 * @subpackage Controller
 */
class JsonadmController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
	/**
	 * @var \Aimeos\Shop\Base\Aimeos
	 * @Flow\Inject
	 */
	protected $aimeos;

	/**
	 * @var \Aimeos\Shop\Base\Context
	 * @Flow\Inject
	 */
	protected $context;

	/**
	 * @var \Aimeos\Shop\Base\I18n
	 * @Flow\Inject
	 */
	protected $i18n;

	/**
	 * @var \Aimeos\Shop\Base\View
	 * @Flow\Inject
	 */
	protected $viewContainer;


	/**
	 * Deletes the resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function deleteAction( $resource, $site = 'default', $id = '' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->delete( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the requested resource object or list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function getAction( $resource, $site = 'default', $id = '' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->get( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Updates a resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function patchAction( $resource, $site = 'default', $id = '' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->patch( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates a new resource object or a list of resource objects
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique ID of the resource
	 * @return string Generated output
	 */
	public function postAction( $resource, $site = 'default', $id = '' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->post( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Creates or updates a single resource object
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @param integer $id Unique resource ID
	 * @return string Generated output
	 */
	public function putAction( $resource, $site = 'default', $id = '' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->put( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the available HTTP verbs and the resource URLs
	 *
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $sitecode Unique site code
	 * @return string Generated output
	 */
	public function optionsAction( $resource = '', $site = 'default' )
	{
		$request = $this->request->getHttpRequest();
		$header = $request->getHeaders()->getAll();
		$status = 500;

		$cntl = $this->createController( $site, $resource, $request->getArgument( 'lang' ) );
		$result = $cntl->options( $request->getContent(), $header, $status );

		$this->setResponse( $status, $header );
		return $result;
	}


	/**
	 * Returns the resource controller
	 *
	 * @param string $sitecode Unique site code
	 * @param string Resource location, e.g. "product/stock/wareshouse"
	 * @param string $lang Language code
	 * @return \Aimeos\MShop\Context\Item\Iface Context item
	 */
	protected function createController( $sitecode, $resource, $lang )
	{
		$lang = ( $lang ? $lang : 'en' );
		$templatePaths = $this->aimeos->get()->getCustomPaths( 'controller/jsonadm/templates' );

		$context = $this->context->get();
		$context = $this->setLocale( $context, $sitecode, $lang );

		$view = $this->viewContainer->create( $context->getConfig(), $this->uriBuilder, $templatePaths, $this->request, $lang );
		$context->setView( $view );

		return \Aimeos\Controller\JsonAdm\Factory::createController( $context, $templatePaths, $resource );
	}


	/**
	 * Creates a new response object
	 *
	 * @param integer $status HTTP status
	 * @param array $header List of HTTP headers
	 * @return \TYPO3\Flow\Http\Response HTTP response object
	 */
	protected function setResponse( $status, array $header )
	{
		$this->response->setStatus( $status );

		foreach( $header as $key => $value ) {
			$this->response->setHeader( $key, $value );
		}
	}


	/**
	 * Sets the locale item in the given context
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object
	 * @param string $sitecode Unique site code
	 * @param string $lang ISO language code, e.g. "en" or "en_GB"
	 * @return \Aimeos\MShop\Context\Item\Iface Modified context object
	 */
	protected function setLocale( \Aimeos\MShop\Context\Item\Iface $context, $sitecode, $lang )
	{
		$localeManager = \Aimeos\MShop\Factory::createManager( $context, 'locale' );

		try
		{
			$localeItem = $localeManager->bootstrap( $sitecode, '', '', false );
			$localeItem->setLanguageId( null );
			$localeItem->setCurrencyId( null );
		}
		catch( \Aimeos\MShop\Locale\Exception $e )
		{
			$localeItem = $localeManager->createItem();
		}

		$context->setLocale( $localeItem );
		$context->setI18n( $this->i18n->get( array( $lang ) ) );

		return $context;
	}
}