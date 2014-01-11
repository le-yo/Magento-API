<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Leonard Korir
 * @link		http://le-yo.com
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
	//building the index action
	function index($action = ''){
   	if(empty($action)){
   		//'hapa';
   		die('hapa');
   		//redirect('index/cats/display');}
   		//load home products
   		
   	
   		
   //OTher Code
	}	
	}
	//home products
	function homeProducts_get(){
		$connection = $this->beverage_grade_connection();
		$client = $connection['client'];
		$session  =$connection['session'];
		
		$apicalls = array();
		$i = 0;
		$productList = $client->call($session, 'catalog_category.assignedProducts', 4);
		
		//shuffle($productList);
		$output = array_slice($productList, 0, 6); 
		foreach ($output as $product){
		$apicalls[$i] = array('catalog_product.info', $product['product_id']);
		$i++;
		}

		$list = $client->multiCall($session, $apicalls);
		print_r($list);
		exit();
		
		
	}
	
	//lets try searching a product
	
	function search_get()
    {
     	//check if we have a product id
	    if(!$this->get('query'))
        {
        	$this->response(NULL, 400);
        }
		//do a curl and get product information
        //first connect to beverage grade
        $connection = $this->beverage_grade_connection();
		
		$filters = array(
		'name' => array('like'=>'%'.$this->get('query').'%')
    	//'name' => $this->get('query')
		);

		$result = $connection['client']->call($connection['session'], 'product.list', array($filters));
			
		if($result){
		$output = array_slice($result, 0, 6); 
		$i = 0;
		foreach ($output as $product){
		$apicalls[$i] = array('catalog_product.info', $product['product_id']);
		$i++;
		}
		
		$product = $connection['client']->multiCall($connection['session'], $apicalls);
		
		$this->response($product, 200); // 200 being the HTTP response code
		}
        else
        {
            $this->response(array('error' => 'Your search did not give any results'), 404);
        }
    }
	
	//lets try to get an entry
		function product_get()
    {
     	//check if we have a product id
	    if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }
		//do a curl and get product information
        //first connect to beverage grade
        $connection = $this->beverage_grade_connection();
		
		$filters = array(
    	'product_id' => $this->get('id')
		);

		$result = $connection['client']->call($connection['session'], 'product.list', array($filters));
		if($result){
		$i = 0;
		foreach ($result as $product){
		$apicalls[$i] = array('catalog_product.info', $product['product_id']);
		$i++;
		}
		
		$product = $connection['client']->multiCall($connection['session'], $apicalls);
		
		$this->response($product, 200); // 200 being the HTTP response code
		}
        else
        {
            $this->response(array('error' => 'Product could not be found'), 404);
        }
    }
	
	//connecting to beverage grade
	function beverage_grade_connection(){
		$client = new SoapClient('http://dev.beveragegrades.com/api/?wsdl');
		//$client = new SoapClient('http://dev.beveragegrades.com/api/v2_soap/?wsdl');
		$session = $client->login('KPAPIUser', 'hkjt67hjgGHghjkg478jskhj484khjG5SDd7hgdf');
		$connection['client'] = $client;
		$connection['session'] = $session;
		return $connection;
	}
	
	
	function user_get()
    {
        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
    	$users = array(
			1 => array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'),
			2 => array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', array('hobbies' => array('fartings', 'bikes'))),
		);
		
    	$user = @$users[$this->get('id')];
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    function user_post()
    {
        //$this->some_model->updateUser( $this->get('id') );
        $message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function users_get()
    {
        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = array(
			array('id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'),
			array('id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'),
			3 => array('id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => array('hobbies' => array('fartings', 'bikes'))),
		);
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }


	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}