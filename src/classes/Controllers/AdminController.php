<?php
/**
 * Created by Jonas Rensfeldt.
 * Date: 22/01/17
 */

namespace App\Controllers;


use App\Entities\RecipeEntity;
use App\Models\Recipe;
use App\Validation\RecipeValidator;
use App\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as v;

class AdminController extends Controller {
	/**
	 * @var Validator
	 */
	protected $validator;

	/**
	 * @var RecipeValidator
	 */
	protected $recipeValidator;

	/**
	 * Logout user
	 *
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function getSignOut( Request $request, Response $response ) {
		$this->auth->logout();

		return $response->withRedirect( $this->ci->get( 'router' )->pathFor( 'admin.login' ) );
	}

	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return mixed
	 */
	public function index( Request $request, Response $response ) {

		$recipeModel = new Recipe( $this->db );

		$recipes = $recipeModel->getList( array(
			'sort' => '-created',
		) );


		return $this->view->render( $response, 'admin/list-recipes.twig',
			array(
				'recipes' => $recipes,
			)
		);
	}

	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function getCreateRecipe( Request $request, Response $response ) {

		//TODO:: Move to DB...
		$categories = array(
			array(
				'id'   => 0,
				'name' => 'Huvudrätt',
				'slug' => 'huvudrätt'
			),
			array(
				'id'   => 1,
				'name' => 'Bakverk',
				'slug' => 'bakverk'
			),
			array(
				'id'   => 2,
				'name' => 'Sylt',
				'slug' => 'sylt'
			),
			array(
				'id'   => 3,
				'name' => 'Sås',
				'slug' => 'sas'
			),
		);

		$units = array(
			array( 'name' => 'st' ),
			array( 'name' => 'krm' ),
			array( 'name' => 'tsk' ),
			array( 'name' => 'msk' ),
			array( 'name' => 'dl' ),
			array( 'name' => 'l' ),
			array( 'name' => 'g' ),
		);

		return $this->view->render( $response, 'admin/add-recipe.twig',
			array(
				'categories' => $categories,
				'units'      => $units
			)
		);
	}

	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function postCreateRecipe( Request $request, Response $response ) {

		$this->recipeValidator = $this->ci->get( 'recipe-validator' );
		$validation            = $this->recipeValidator->validate( $request );

		if ( $validation->failed() ) {
			return $response->withRedirect( $this->ci->get( 'router' )->pathFor( 'admin.add-recipe' ) );
		}

		// Validation OK

		// $this->logger->addDebug( 'postCreate', array( $request->getParams() ) );

		// Create RecipeEntity
		$recipeEntity = new RecipeEntity(
			array_merge(
				$request->getParams(),
				$request->getUploadedFiles()
			)
		);

		// Save Entity
		$recipeModel = new Recipe( $this->db );

		$recipeModel->create( $recipeEntity );

		return $this->view->render( $response, 'admin/edit-recipe.twig' );
	}

	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function editRecipe( Request $request, Response $response ) {
		return $this->view->render( $response, 'admin/edit-recipe.twig' );
	}


	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return mixed
	 */
	public function login( Request $request, Response $response ) {
		return $this->view->render( $response, 'admin/login.twig' );
	}

	/**
	 * @param Request  $request
	 * @param Response $response
	 *
	 * @return Response
	 */
	public function loginAttempt( Request $request, Response $response ) {
		$auth = $this->auth->attempt(
			$request->getParam( 'username' ),
			$request->getParam( 'password' )
		);

		if ( !$auth ) {

			return $response->withRedirect( $this->ci->get( 'router' )->pathFor( 'admin.login' ) );
		}

		return $response->withRedirect( $this->ci->get( 'router' )->pathFor( 'admin.index' ) );
	}
}