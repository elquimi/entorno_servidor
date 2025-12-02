<?php


abstract class producto{
	
	public function __construct(
	protected string $SKU,
	protected string $nombre,
	protected int $precioUnitario
	){}
	
	
	
	
	public function tostring(): string{
		return " SKU: " + $SKU + " nombre: " + $nombre + " precioUnitario: " + $precioUnitario;
	}
	
	
	
	//getters y setters-------------------------------------------------------
	
	  public function getSKU(): string{
        return $this->SKU;
    }
	 public function getNombre(): string{
        return $this->nombre;
    }
	
	 public function getPrecioUnitario(): int{
        return $this->precioUnitario;
    }
	
	 public function setSKU(string $SKU): void{
        $this->SKU = $SKU;
    }
	 public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
	 public function setPrecioUnitario(string $precioUnitario): void{
        $this->precioUnitario = $precioUnitario;
    }
	//------------------------------------------------------------------------------------
	
	
}

class producto_fisico extends producto{
	public function __construct(
		private float $peso
	
	
	){}
	public function tostring(): string {
		$accion_padre = parent :: tostring();
		return $accion_padre + " peso: " + peso;
		
	}
	
	public function getPeso(): int{
        return $this->peso;
    }
	
	 public function setPeso(string $peso): void{
        $this->peso = $peso;
    }
	
}


class producto_digital extends producto{
	private string $licencia;
	public function __construct(
	string licencia = '';
	
	){}
	
	public function getLicencia(): String {
		return $this ->licencia;
	}
	
	
	public function setLicencia(string $licencia): void{
        $this->licencia = $licencia;
    }
	
	
	public function tostring(): string {
		$accion_padre = parent :: tostring();
		return $accion_padre + " licencia: " + licencia;
		
	}
	
	
	
	
}




?>