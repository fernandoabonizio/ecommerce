<?php

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

	// constante da session
	const SESSION = "User";

	public static function login($login, $password) {

		// instancia classe Sql
		$sql = new Sql();
		// carrega variavel com o usuario verificando primeiro somente o nome de login
		$results = $sql->select(
			               "SELECT * FROM tb_users 
			                 WHERE deslogin = :LOGIN", 
			               array("LOGIN"=>$login));

		if (count($results) === 0) {

			// contra-barra pega exception padrão do php
			throw new \Exception("Usuário inexistente ou senha inválida!");			
		}

		// carrega em variavel o resultado do sql
		$data = $results[0];
		// valida senha descriptografando
		if (password_verify($password, $data["despassword"]) === true) {

			// instancia classe user
			$user = new User();
			// seta na variavel campo=>valor da tabela aplicando o "set"
			$user->setData($data);
			// cria sessão
			$_SESSION[User::SESSION] = $user->getValues();
			// retorna o resultado
			return $user;

		} else {

			throw new \Exception("Usuário inexistente ou senha inválida!");				
		}
	}

	public static function logout() {

		$_SESSION[User::SESSION] = NULL

	}
 
	// valida login na pagina de admin
	public static function verifyLogin($inadmin = true) {

		// se nao for definida retorna para a tela de login
		if (!isset($_SESSION[User::SESSION]) ||
		    !$_SESSION[User::SESSION] ||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 ||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin)

		{

			header("Location: /admin/login");
			exit;
		}
	}
}

?>