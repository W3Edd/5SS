<?php
require_once("Conexion.php");
class Utileria{

	private $BD;

	public function __construct(){
		// $this->BD = new Conexion();
	}
//---------------------------------------------------------------------------------
	public function ID($Tipo = 1 ,$Long = 10, $M = 1){
		// Tipo 1 Alfanumerico
		// Tipo 2 Numerico
		// M 1 Mayusculas
		// M != 1 Minusculas
		if($Tipo == 1){
			if($M == 1){
				$Sel = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			}
			else{
				$Sel = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			}
		}
		else{
			$Sel = "0123456789";
		}
		return substr(str_shuffle($Sel), 0, $Long);
	}
	//---------------------------------------------------------------------------------
	public function VerificarEmail($Email){
		return (filter_var($Email, FILTER_VALIDATE_EMAIL)) ? 1 : 0;
	}
	//---------------------------------------------------------------------------------
	public function VerificarContrasena($Pass){
		if(strlen($Pass) >= 6){
			if($this->VerificarCaracteres($Pass)){
				if($this->VerificarMayuscula($Pass)){
					if($this->VerificarNumero($Pass)){
						return 1; //OK
					}
					return 2; //No Numero
				}
				return 3; //No mayuscula
			}
			return 4; //No Char
		}
		return 5; //No igual o mayor a 6
	}

	//---------------------------------------------------------------------------------
	public function ElementosArrayUnicos($Array){
		$SinR= array_unique ($Array);
		sort($SinR);
		return $SinR;
	}
	//---------------------------------------------------------------------------------
	public function PrintArreglo($Array){
		if($Array!=1){
			echo '<br>';
			for ($i=0; $i < count($Array) ; $i++){
				echo $Array[$i].'<br>';
			}
			echo '<br>';
			return 1;//Todo bien
		}
		else
		{
			return 2;//Todo mal
		}
	}
	//---------------------------------------------------------------------------------
	public function ArregloVacio($Array){
		/*$flag = false;
		foreach($Array as $Pos)====
		{
			if(!empty($Pos))
			{
				$flag = true;
			}
		}
		//true vacio
		//false no vacio
		return !$flag;
		*/
		if($Array == 1){
			return false;
		}
		return true;
	}
	//---------------------------------------------------------------------------------
	public function DiferenciarArreglos($Array1, $Array2){
		$out = array_diff($Array1, $Array2);
		return sort($out);
	}
	//--------------------------------------------------------------------------------
	public function VerificarCaracteres($String){
		$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for($i=0; $i<strlen($String); $i++){
			if(strpos($permitidos, substr($String, $i, 1))===false){
				return false;
			}
		}
		return true;
	}
	//---------------------------------------------------------------------------------
	public function VerificarMayuscula($String){
		if(ctype_lower($String)){
			return false;
		}
		return true;
	}
	//---------------------------------------------------------------------------------
	public function VerificarNumero($String){
		if(ctype_alpha($String)){
			return false;
		}
		return true;
	}
	//---------------------------------------------------------------------------------
		public function Crypt($Str, $Level = 6)
		{
			if($Level == 1)
			{
				return sha1($Str);
			}
			if($Level%2 == 0)
			{
				return md5(sha1($this->Crypt($Str, $Level-1)));
			}
			return md5($this->Crypt($Str, $Level-1));
		}
	//---------------------------------------------------------------------------------
		public function DimeCuantosN($Tabla, $Campo, $Valor, $Tipo)
		{
			$Campos = explode("~", $Campo);
			$Valores = explode("~", $Valor);
			$Tipos = explode("~", $Tipo);
			if($Tipos[0] == "0"){
				$RestQ = $Campos[0].' = '.$Valores[0];
			}
			else{
				$RestQ = $Campos[0].' = "'.$Valores[0].'"';
			}
			for ($i=0; $i < count($Campos); $i++)
			{
				if($Tipos[$i] == "0")
				{
					$RestQ .= " AND ".$Campos[$i]." = ".$Valores[$i];
				}
				else{
					$RestQ .= ' AND '.$Campos[$i].' = "'.$Valores[$i].'"';
				}
			}

			$X = $this->BD->SELECT("SELECT COUNT(*) AS TOTAL FROM ".$Tabla." WHERE ".$RestQ, "TOTAL");
			echo "SELECT COUNT(*) AS TOTAL FROM ".$Tabla." WHERE ".$RestQ.'<br>';
			$Q=$X[0]["TOTAL"];
			echo $Q.'<br>';
			return $Q;
		}
	//---------------------------------------------------------------------------------
		public function DimeCuantos($Tabla,$Campo, $Valor, $Tipo)
		{
			$Campos = explode("~", $Campo);
			$Valores = explode("~", $Valor);
			$Tipos = explode("~", $Tipo);
			if($Tipos[0] == "0"){
				$RestQ = $Campos[0].' = '.$Valores[0];
			}
			else{
				$RestQ = $Campos[0].' = "'.$Valores[0].'"';
			}
			for ($i=0; $i < count($Campos); $i++)
			{
				if($Tipos[$i] == "0")
				{
					$RestQ .= " AND ".$Campos[$i]." = ".$Valores[$i];
				}
				else{
					$RestQ .= ' AND '.$Campos[$i].' = "'.$Valores[$i].'"';
				}
			}

			$X = $this->BD->SELECT("SELECT COUNT(*) AS TOTAL FROM ".$Tabla." WHERE ".$RestQ, "TOTAL");
			if($X[0]["TOTAL"] > 0)
			{
				return true;
			}
			return false;
		}
	//---------------------------------------------------------------------------------
		public function DimeCuantosT($Tabla, $Cantidad= false)
		{
			$X = $this->BD->SELECT("SELECT COUNT(*) AS TOTAL FROM ".$Tabla."", "TOTAL");


			//SI QUIERO SABER LA CANTIDAD DE REGISTROS EN TOTAL DE LA TABLA.
			if($Cantidad)
			{
				$Q= $X[0]["TOTAL"];
			}
			else //SI SOLO QUIERO SABER SI HAY REGISTROS EN LA TABLA.
			{
				if($X[0]["TOTAL"] > 0)
				{
					return true;
				}
			}

			return $Q;
		}
	//---------------------------------------------------------------------------------
		public function NoRepeat($Tabla, $Campo, $ID, $Tipo)
		{
			if($this->DimeCuantos($Tabla, $Campo, $ID, $Tipo))
			{
				$this->NoRepeat($Tabla, $Campo, $this->DameID(), $Tipo);
			}
			return $ID;
		}
	//---------------------------------------------------------------------------------
		public function QuitarEspacio($String)
		{
			return substr($String, 0,-1);
		}
	//---------------------------------------------------------------------------------
		public function InvertirCadena($Sttring)
		{
			$Inv = "";
			for ($i = strlen($String)-1; $i >= 0 ; $i--)
			{
				$Inv .= substr($String, $i, 1);
			}
			return $Inv;
		}
	//---------------------------------------------------------------------------------
		public function QuitarEspacios($String)
		{
			$Cad = "";
			for($i=0; $i < strlen($String); $i++){
				if(substr($String, $i, 1) != " ")
				{
					$Cad .= substr($String, $i, 1);
				}
			}
			return $Cad;
		}
	//---------------------------------------------------------------------------------
		public function Concatenar($Array)
		{
			$Sal = "";
			foreach($Array as $val)
			{
				$Sal .= $val;
			}
			return $Sal;
		}
	//---------------------------------------------------------------------------------
		public function ID_Faltante($TABLA, $CAMPOID)
		{
			//DECLARAR ARREGLO CON VALORES CONTENIDOS DEL 1 ALA CANTIDAD EXISTENTES DE REGISTROS DE UNA DETERMINADA TABLA.
			//DESPUES DE ALMACENAR VALORES DEL 1 AL N, ENCONTRAR LAS DIFERENCIA ENTRE LOS VALORES DE LOS DOS ARREGLOS.
			//CON ESO EVITAMOS FUGAS DE ID, MANTENIENDO UN CONTEO CONSECUTIVO DE REGISTROS. ESTO EN CASO DE NO EXISTIR UN AUTO_INCEREMENT.

			$ARRAY_NUMBERS= array();
			$REG_IDMATERIAS= array();
			$j=0;

			for($i=1; $i <= (new Utileria())->DimeCuantosT($TABLA, true); $i++)
			{
				$ARRAY_NUMBERS[$j]= $i;
				$j++;
			}

			foreach ((new Conexion())->SELECT("SELECT $CAMPOID FROM $TABLA","$CAMPOID") as $key => $VAL)
			{
				$REG_IDMATERIAS[]= $VAL[$CAMPOID];
			}

			return array_diff($ARRAY_NUMBERS, $REG_IDMATERIAS);
		}

	//-------------------------------AJAX REQUESTS-------------------------------------
	/*if(isset($_POST['QSIDP']) && !empty($_POST['QSIDP']))
	{
		$KEY= "";
		foreach ((new Conexion())->SELECT("SELECT ID_ESPECIALIDAD,ESPECIALIDAD FROM especialidades","ID_ESPECIALIDAD~ESPECIALIDAD") as $value) {
			$KEY.= $value['ID_ESPECIALIDAD']."~".$value['ESPECIALIDAD']."~";
		}
		if($_POST['QSIDP']=== 'true')
		{
			echo $KEY=substr($KEY, 0, strlen($KEY)-1);
		}
	}*/
	//---------------------------------------------------------------------------------
	public function ObtenerCaracter($Posicion = 0, $Tipo = 0, $Valor)
	{
		//0 Es cadena 1 Array
		switch($Tipo)
		 {
			case 0:
				return $Valor[$Posicion];
				break;

			case 1:
				// array
				$C = 0;
				foreach($Valor as $V)
				{
					$Valores[$C] = $V[$Posicion];
					$C++;
				}
				return $Valores;
				break;
		}
	}
	//---------------------------------------------------------------------------------
	public function EliminarPMB($IDENTIFICADOR= null)
	{
		$HTML= "<style type=\"text/css\">";
		$HTML.= "</style>";

		echo $IDENTIFICADOR[0];
	}
	//---------------------------------------------------------------------------------
	public function ObtenerIDEspecifico($TABLA= null, $CAMPOID= null, $CONDICION= null)
	{
		if($TABLA != null)
		{
			$IDF= array();
			$Q= "SELECT $CAMPOID AS ID_ESPECIFICO FROM $TABLA WHERE $CONDICION";

			foreach ((new Conexion())->SELECT($Q, "ID_ESPECIFICO") as $key => $value) {

				return ($value['ID_ESPECIFICO']);
			}


		}
	}
	//---------------------------------------------------------------------------------
	public function UNSET_SESSIONS($SESSION= null, $SESSION_BIDI= null)
	{
		$EXITOSO= false;

		if($SESSION_BIDI != null)
		{
			$SESSIONS_ARRAY_BIDI= explode("~", (string)$SESSION_BIDI);
			$OBJETO_SESSION= (string)$SESSION;

			foreach ($SESSIONS_ARRAY_BIDI as $key => $value) {

				if(!empty($value))
				{
					unset($_SESSION[$OBJETO_SESSION][$value]);
					$EXITOSO= true;
				}
			}
		}
		else
		{
			$SESSIONS_ARRAY= explode("~", (string)$SESSION);

			foreach ($SESSIONS_ARRAY as $key => $value) {

				if(!empty($value))
				{
					unset($_SESSION[$value]);
					$EXITOSO= true;
				}
			}
		}

		return $EXITOSO;
	}
	//---------------------------------------------------------------------------------
	function include_once_get($directorio= null)
	{
		if($directorio != null)
		{
			$parts = explode('?', $directorio);
			if (isset($parts[1]))
			{
				parse_str($parts[1], $output);
				foreach ($output as $key => $value)
				{
					$_GET[$key] = $value;
				}
			}

			include($parts[0]);
		}
	}
	//---------------------------------------------------------------------------------
	public function AñadirRegistro($TABLA= null)
	{
		if($TABLA != null)
		{
			$CAMPOS_SBTR_UND= "";
			$CAMPOS_SBTR= array();
			$valor= "";
			$lolxd= null;
			$ID_SIGUIENTE= 0;
			$PIDELO_PLEASE= false;
			$CAMPOS= "";
			$SQL= "INSERT INTO $TABLA (";

			//OBTENGO LOS CAMPOS DE LA TABLA DADA.
			foreach ((new Conexion())->DESCRIBE_ALL($TABLA, 0) as $CAMPO)
			{
				$CAMPOS_SBTR_UND.= $CAMPO.'~';
			}

			//ELIMINO EL ULTIMO '~' SOBRANTE EN LA CADENA.
			$CAMPOS_SBTR_UND= substr($CAMPOS_SBTR_UND, 0, strlen($CAMPOS_SBTR_UND)-1);
			$CAMPOS_SBTR= explode("~", $CAMPOS_SBTR_UND);

			// IDENTIFICAR QUE EL CAMPO CONTENEDORA DEL ID DE LA TABLA $_POST['TABLA'] SEA AUTO INCREMENTABLE.
			// EN CASO DE QUE NO SEA AUTO_INCREMENTABLE CAPTURAR EL ULTIMO VALOR DEL ID Y CONTINUAR CON ESE CONTEO..
			// SI, SI EXISTE UN AUTO_INCREMENTABLE SIMPLEMENTE AGREGAR EL REGISTRO.

			// NOTA: EL PRIMER CAMPO DE LAS TABLAS SIEMPRE DEBE SER EL DEL ID!.

			if((new Conexion())->IS_AUTOINCREMENT($TABLA, (string)$CAMPOS_SBTR[0]))
			{
				//Ya no obtengas el ultimo id.
				$PIDELO_PLEASE= false;
			}
			else
			{
				//Obten el ultimo id del Campo ID de la tabla $_POST[TABLA]
				$PIDELO_PLEASE= true;
			}

			foreach ($CAMPOS_SBTR as $key => $value)
			{

				$CAMPOS.= $value.', ';

				//CUANDO SEA LA ULTIMA POSICION DEL $Key.
			    end($CAMPOS_SBTR);
			    if ($key === key($CAMPOS_SBTR))
			    {
			    	//CONCATENO LOS CAMPOS QUE SE DIERON POR JAVASCRIPT(ConfiguracionSistema.js) Y ELIMINO LA ULTIMA COMA PARA EVITAR ERRORES SQL.
			        $SQL.=substr($CAMPOS, 0, strlen($CAMPOS)-2).") VALUES (";
			        //VERIFICO SI NO HAY UN ID PERDIDO EN EL QUE SE PUEDA OCUPAR EL LUGAR.
			        $ID_PERDIDO= ((new Utileria())->ID_Faltante((string)$_POST["TABLA"], (string)$CAMPOS_SBTR[0]));
					$ID_PERDIDO= (int) end($ID_PERDIDO);

					//SI HAY UN ID PERDIDO, ENTONCES CONCATENAR EL ID PERDIDO EN EL SQL.
					if(!empty($ID_PERDIDO))
					{
						$SQL.="'".$ID_PERDIDO."',";
					}
					else //SI NO HAY UN ID PERDIDO ENTONCES..
					{
						//VERIFICO SI HAY QUE PEDIR UN ULTIMO ID PARA SEGUIR EL ORDEN
						if($PIDELO_PLEASE)
				        {
				        	foreach ($lolxd= (new Conexion())->SELECT("SELECT $CAMPOS_SBTR[0] FROM $_POST[TABLA]", (string)$CAMPOS_SBTR[0]) as $key => $value)
				        	{
				        		//SI ES LA ULTIMO INCREMENTO "$Key"
				        		end($lolxd);
				        		if($key === key($lolxd))
				        		{
				        			//OBTENER EL ULTIMO ID Y SEGUIR EL CONTEO CONSECUTIVO SIN EL AUTO_INCREMENT.
				        			$ID_SIGUIENTE= $value[$CAMPOS_SBTR[0]];
				        			$ID_SIGUIENTE++;

				        			$SQL.="'".$ID_SIGUIENTE."',";
				        		}
				        	}
				        }
				        else //SI NO HAY QUE PEDIR ULTIMO ID SOLO INSERTAR, DEJAR VACIO, EL AUTO_INCREMENT HARÁ SU TRABAJADO.
				        {
				        	$SQL.="'',";
				        }
					}
			    }
			}

			// ACOMODAR LOS REGISTRO DADOS EN EL FORMULARIO.

			foreach (explode(",", $_POST['GET_DATA']) as $key => $value)
			{
				//LAS POSICION IMPARES DEL ARREGLO CONTIENEN LOS VALORES QUE FUERON SERIALIZADOS DEL FORMULARIO FORM-DATA-TEMP en JS.
				if($key%2==1)
				{
					$SQL.="'".$value."',";
				}
			}

			$SQL= substr($SQL, 0, strlen($SQL)-1).");";

			// AGREGAR REGISTRO.
			if((new Conexion())->EXEQ($SQL))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
	}
	//---------------------------------------------------------------------------------
	public function EliminarRegistro()
	{

	}
	//---------------------------------------------------------------------------------
}





?>
