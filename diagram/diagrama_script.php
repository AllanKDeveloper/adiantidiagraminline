<?php
// Here you need to change the directory to your application dir
chdir('../../');
// Load the init to load the database sets and Adianti Framework
require_once 'init.php';

// Example of save and check if user is logged (security)
if (SystemUser::isLogged()) {
	if ($_POST) {
		if ($_POST['save']) {
			TTransaction::open('database_example'); // open a transaction
			$object = new Diagrama($_POST['key']); // instantiates the Active Record
			$object->texto = $_POST['diagrama'] // diagram image;
			$object->editor = 'ID' // ID of user or whatever
			$object->versao = 'Version' // version of your doc
			$object->store(); // store
			TTransaction::close(); // close the transaction
		} else if ($_POST['vazio']) {
			TTransaction::open('database_example'); // open a transaction
			$object = new Diagrama($_POST['key']); // instantiates the Active Record
			$object->delete(); // delete
			TTransaction::close(); // close the transaction
		}
	}
}