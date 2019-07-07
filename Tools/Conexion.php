<?php
class Conexion {
    private $Host;
    private $Usuario;
    private $Contrasena;
    private $BD;
    private $Base;

    public function __construct($Host = "localhost", $User = "id5481438_memephy", $Pass = "relojito", $BD = "id5481438_memephy"){
        $this->Host = $Host;
        $this->Usuario = $User;
        $this->Contrasena = $Pass;
        $this->BD = $BD;
    }

    public function conectar(){
        try{
            $this->Base = new PDO('mysql:host='.$this->Host.';dbname='.$this->BD, $this->Usuario, $this->Contrasena);
            $this->Base->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }catch(PDOException $e){
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function cerrar(){
        try {
            $this->Base = null;
			return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
            return false;
        }
    }

    public function select($Q, $Ca){
        if($this->conectar()){
            try {
                $campos = explode(",", $Ca);
                $query = $this->Base->prepare($Q);
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute();
                $x = 0;
                $y = 0;
                while($fila = $query->fetch()){
                    while($y < count($campos)){
                        $tabla[$x][$campos[$y]] = $fila[$campos[$y]];
                        $y++;
                    }
                    $y = 0;
                    $x++;
                }
                return $tabla;
            } catch (PDOException $e) {
                echo $e->getMessage();
                die();
                return false;
            }
        }
	}
	
	public function exeQ($Q){
		if($this->conectar()){
			try {
				$this->Base->exec($Q);
			} catch (PDOException $e) {
                echo $e->getMessage();
                die();
				return false;
			}
		}
		return true;
	}
}
