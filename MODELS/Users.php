<?php
require_once('Tools/Conexion.php');
require_once('Tools/Utileria.php');
class Users{
	private $Con;
	private $Util;
	private $BD;

	/**
	 * __construct
	 * <pre>
	 * Constructor de la clase Users
	 * Inicializa el atributo $Con con una nueva conexion predeterminada
	 * Inicializa el atributo $Util con una nueva utileria
	 * </pre>
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct($Host = "", $User = "", $Pass = "", $BD = ""){
		if($Host == ""){
			$this->Con = new Conexion();
		} else {
			$this->Con = new Conexion($Host, $User, $Pass, $BD);
			$this->BD = $BD;
		}
		$this->Util = new Utileria();
	}

	/**
	 * setConexion
	 * Establece los parametros de la conexion a la base de datos
	 */
	public function setConexion($Host, $User, $Pass, $BD){
		$this->Con = new Conexion($Host, $User, $Pass, $BD);
		$this->BD = $BD;
	}

	public function initDataBase(){
		// $Q = 'CREATE DATABASE IF NOT EXISTS'.$this->BD;
		$Q = 'CREATE TABLE IF NOT EXISTS tbinfo(
			T_TBNAME VARCHAR(30) NOT NULL UNIQUE,
			T_TBID VARCHAR(30) NOT NULL UNIQUE
		);';
		$this->Con->exeQ($Q);
	}

	public function initUsersTable($TableName = "users"){
		$this->initDataBase();
		$Q = 'INSERT INTO tbinfo (T_TBNAME, T_TBID) VALUES ("'.$TableName.'", "USERS")';
		$this->Con->exeQ($Q);

		$Q = 'CREATE TABLE IF NOT EXISTS '.$TableName.' (
			U_IND SMALLINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
			U_ID VARCHAR(20) NOT NULL UNIQUE,
			U_PRIMARY VARCHAR(100) NOT NULL UNIQUE,
			U_SECONDARY VARCHAR(32) NOT NULL,
			U_STATUS BOOL NOT NULL
		);';
		$this->Con->exeQ($Q);
	}

	public function signUpUser($key1, $key2){
		$tableName = $this->getTableName("users");
		$ID = $this->Util->ID(1, 20, 2);
		$key2 = $this->Util->Crypt($key2);
		$Q = 'INSERT INTO '.$tableName.' (U_ID, U_PRIMARY, U_SECONDARY, U_STATUS) VALUES ("'.$ID.'", "'.$key1.'", "'.$key2.'", 1)';
		$this->Con->exeQ($Q);
	}

	public function logIn($key1, $key2){
		$tableName = $this->getTableName("users");
		$key2 = $this->Util->Crypt($key2);
		$Q = 'SELECT COUNT(U_PRIMARY) AS TOTAL FROM '.$tableName.' WHERE U_PRIMARY = "'.$key1.'"';
		$R = $this->Con->select($Q, "TOTAL")[0]["TOTAL"];
		if($R == 1){
			$Q = 'SELECT COUNT(U_PRIMARY) AS TOTAL FROM '.$tableName.' WHERE U_PRIMARY = "'.$key1.'" AND U_SECONDARY = "'.$key2.'"';
			$R = $this->Con->select($Q, "TOTAL")[0]["TOTAL"];
			if($R == 1){
				$Q = 'SELECT COUNT(U_PRIMARY) AS TOTAL FROM '.$tableName.' WHERE U_PRIMARY = "'.$key1.'" AND U_SECONDARY = "'.$key2.'" AND U_STATUS = 1';
				$R = $this->Con->select($Q, "TOTAL")[0]["TOTAL"];
				if($R == 1){
					$Q = 'SELECT U_ID FROM '.$tableName.' WHERE U_PRIMARY = "'.$key1.'" AND U_SECONDARY = "'.$key2.'"';
					return $this->Con->select($Q, "U_ID")[0]["U_ID"];
				} else {
					return 1;
					// La cuenta ha sido "eliminada"
				}
			} else {
				return 2;
				// La clave primaria existe pero la secundaria no coincide
			}
		} else {
			return 3;
			// No existe la clave primaria en la tabla de usuarios
		}
	}

	/**
	 * Establece el estado del usuario a 0.
	 * No se elimina la informacion de la tabla
	 */
	public function deleteUser($key1, $key2 = ""){
		$tableName = $this->getTableName("users");
		$Q = 'UPDATE '.$tableName.' SET U_STATUS = 0 ';
		if($key2 == ""){
			$Q .= 'WHERE U_ID = "'.$key1.'"';
		} else {
			$key2 = $this->Util->Crypt($key2);
			$Q .= 'WHERE U_PRIMARY = "'.$key1.'" AND U_SECONDARY = "'.$key2.'"';
		}
		return $this->Con->exeQ($Q);
	}

	/**
	 * Retorna el nombre otorgado de la tabla de acuerdo al ID proporcionado
	 */
	public function getTableName($tableId){
		$Q = 'SELECT T_TBNAME FROM tbinfo WHERE T_TBID = "'.$tableId.'"';
		return $this->Con->select($Q, "T_TBNAME")[0]["T_TBNAME"];
	}
}
?>