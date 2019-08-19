<?php

namespace Hcode;

class Model {

	private $values = [];

	// metodo magico para saber toda vez que um metodo é chamado
	public function __call($name, $args) {

		// carrega na variavel os 3 primeiros digitos
		$method = substr($name, 0, 3);
		// carrega na variavel do 4 digitos ate o final
		$fieldName = substr($name, 3, strlen($name));

		switch ($method) {

			case "get":
				// retorna o result do get
				return $this->values[$fieldName];
			break;
			
			case "set":
				// seta dado na função set
				$this->values[$fieldName] = $args[0];
			break;
		}
	}

	// função retorna campo=>valor do select efetuado
	public function setData($data = array()) {

		foreach ($data as $key => $value) {

			$this->{"set".$key}($value);			
		}
	}

	// função retorna na session dados do usuario
	public function getValues() {

		return $this->values;
	}
}

?>