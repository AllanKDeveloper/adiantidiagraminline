<?php
/**
 * DiagramaForm
 * @author Allan Kehl <https://github.com/AllanKDeveloper/>
 */
class DiagramForm extends TPage
{
	protected $form; // form
	
	const DESC = 'Add and edit diagrams';
	const VERS = '1.00.00';
	
	/**
	 * Form constructor
	 * @param $param Request
	 */
	public function __construct( $param )
	{
		parent::__construct();
		
		// div to recieve login message
		$success_message = new TElement('div');
		$this->message = new TElement('div');
		$this->message->id = 'message';
		$this->message->class = 'alert alert-success';
		$this->message->style = 'display:none;';
		$this->message->add('<span class="fa fa-fa fa-success-circle fa-1x" aria-hidden="true" style="margin-right: 10px;" ></span>');
		$success_message->add($this->message);

		// creates the form
		$this->form = new TQuickForm('form_Diagrama');
		$this->form->class = 'tform'; // change CSS class
		$this->form->style = 'display: table;width:100%'; // change style
		
		// define the form title
		$this->form->setFormTitle('Diagram Data - v. '.Functions::getClassVersion(__CLASS__));
		
		// colunas no formulário
		$this->form->setFieldsByRow(1);
		
		// create the form fields
		$id = new TEntry('id');
		
		// desabilita a edição do ID
		$id->setEditable(FALSE);

		// add the fields
		$this->form->addQuickField('ID:', $id,  100 );

		// vertical box container
		$container = new TVBox;
		$container->style = 'width: 100%';
		$container->add($success_message);
		$container->add($this->form);
		// centraliza o conteúdo
		$center = new TElement('center');
		$center->add($container);
		
		parent::add($center);
	}
	

	/**
	 * Load object to form data
	 * @param $param Request
	 */
	public function onEdit( $param )
	{
		try
		{
			// here you load the diagram.js to the page
			TPage::include_js('../js/diagram.js');

			if (isset($param['key']))
			{
				$key = $param['key'];  // get the parameter $key
				TTransaction::open('database_example'); // open a transaction
				$object = new Diagrama($key); // instantiates the Active Record
				$this->form->setData($object); // fill the form

				// new script element
				$script = new TElement('script');
				// here you need pass the Diagram object (the image content) and the key from database to load into image tag
				$script->add("
					edit('$object->texto', '$key');
				");
				$script->show();

				TTransaction::close(); // close the transaction
			}
		}
		catch (Exception $e) // in case of exception
		{
			new TMessage('error', $e->getMessage()); // shows the exception error message
			TTransaction::rollback(); // undo all pending operations
		}
	}
}
