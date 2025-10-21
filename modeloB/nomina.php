<?php

class nomina{
    public function __construct(
        private string $nombre,
        private string $fecha_nacimiento,
        private string $horas_semanales,
        private float $salario_hora,
        private float $retencion,
    ){}

    public function __toString(): string{
      return '
    <div style="
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.2rem;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    ">
        <h2 style="
            margin-top: 0;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 1.2rem;
            color: #333;
        ">📋 Información del Empleado</h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; row-gap: 0.6rem;">
            <div><strong>👤 Nombre:</strong></div>
            <div>' . htmlspecialchars($this->nombre) . '</div>

            <div><strong>🎂 Nacimiento:</strong></div>
            <div>' . htmlspecialchars($this->fecha_nacimiento) . '</div>

            <div><strong>⏱ Horas:</strong></div>
            <div>' . htmlspecialchars($this->horas_semanales) . '</div>

            <div><strong>💶 Salario/hora:</strong></div>
            <div>' . number_format($this->salario_hora, 2) . ' €</div>

            <div><strong>📉 Retención:</strong></div>
            <div>' . number_format($this->retencion, 2) . ' %</div>
        </div>
    </div>';
}
    

   
    public function getNombre(): string{
        return $this->nombre;
    }
    public function getFecha_nacimiento(): string{
        return $this->fecha_nacimiento;
    }
    public function getHoras_semanales(): string{
        return $this->horas_semanales;
    }
    public function getSalario_hora(): float{
        return $this->salario_hora;
    }
    public function getRetencion(): float{
        return $this->retencion;
    }
    public function setNombre(string $nombre): void{
        $this->nombre = $nombre;
    }
    public function setFecha_nacimiento(string $fecha_nacimiento): void{
        $this->fecha_nacimiento = $fecha_nacimiento;
    }
    public function setHoras_semanales(string $horas_semanales): void{
        $this->horas_semanales = $horas_semanales;
    }
    public function setSalario_hora(float $salario_hora): void{
        $this->salario_hora = $salario_hora;
    }
    public function setRetencion(float $retencion): void{
        $this->retencion = $retencion;
    }
   
}








?>