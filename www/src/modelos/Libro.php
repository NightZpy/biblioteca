<?php 
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SADO_BOOTSTRAP;
/**
* 
*/

class Libro extends SadoORM
{

}

class Categoria extends SadoORM
{
	protected function __init(){
		$this->modelJoin(new Libro, ['categoria_id' => 'categoria_id']);
	}
}


// create Form class 
class LibroForm extends SadoForm 
{ 
      // initialize the form 
      public function __construct() 
      { 
            // add instance of ORM class 
            $this->model(new Libro); 

            // add fields 
            $this->field('titulo') 
                  ->label('Titulo:'); 

            $this->field('autor') 
                  ->label('Autor:'); 

            // add submit button 
            $this->fieldSubmit() 
                  ->value('Save'); 

            // active form controls (required) 
            $this->activate(); 
      } 

 	  protected function onValidate() 
      { 
            // MD5 hash the user's password 
            $hashed_pwd = md5( $this->field('autor')->getValue() ); 
            echo $hashed_pwd;

            // modify post value so hashed password will be stored in database 
            //$this->field('email')->value( $hashed_pwd ); 
      }       
} 

// create instance of form 
$form = new LibroForm; 

// display form 
echo $form->getForm();
