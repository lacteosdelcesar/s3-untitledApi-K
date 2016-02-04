<?php namespace App\Resources\Empleados\Actions;

use App\Entities\Certificado;
use App\Entities\Contrato;
use App\Entities\Empleado;
use App\Lib\Actions\Action;
use App\Lib\Orm\MySQLEntityManager;
use App\Lib\Orm\OracleEntityManager;
use App\Lib\Responders\PdfResponder;
use fpdf\FPDF;

class GenerateCertificadoAction extends Action
{

    public function __construct()
    {
        $this->responder = PdfResponder::class;
    }

    public function execute(array $args)
    {
        $certificado = $this->getCertificado($args);
        if ($certificado) {
            $this->responseInfo = ['status' => self::STATUS_OK];
            return $this->crearPdf($certificado);
        } else {
            return false;
        }
    }

    public function getCertificado(array $args)
    {
        if (isset($args['crt_id'])) {
            // TODO: implementar metodo para la validacion de los certificados

        } elseif (isset($args['e_id']) && isset($args['tipo_crt'])) {
            $certificado_id= $this->getCertificadoId($args['e_id'], $args['tipo_crt']);
            $certificadosMaper = MySQLEntityManager::createMaper(Certificado::class);
            $certificado = $certificadosMaper->get($certificado_id);
            if ($certificado) {
                return $certificado;
            } else {
                $certificado = $certificadosMaper->create([
                    'id' => $certificado_id,
                    'cedula_empleado' => $args['e_id'],
                    'tipo' => $args['tipo_crt']
                ]);
                return $certificado;
            }
        } else {
            return false;
        }
    }

    public function getCertificadoId($empleado_id, $tipo_certificado)
    {
        return date('y') . date('m') . date('d') . substr('000' . $empleado_id, -12) . $tipo_certificado;
    }

    /**
     * @param Certificado $certificado
     * @throws \Exception
     */
    public function crearPdf(Certificado $certificado)
    {
        $empleado = new Empleado($certificado->cedula_empleado);
        //echo print_r($empleado);
        //$empleado = new Empleado();
        $pdf = new FPDF('P', 'cm', 'Letter');
        $pdf->AddPage();
        $pdf->SetMargins(3, 3, 2);
        $pdf->SetAutoPageBreak(true, 1);
        $pdf->SetLineWidth(1.5);
        $pdf->SetFont('Arial');
        $pdf->Image(realpath(__DIR__.'/../../../../assets/images/logo.png'), 14, 0.3, 5, 'PNG');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 6, $certificado->id, 0, 2, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(0);
        $pdf->Cell(0, 0, 'Valledupar, ' . $this->formatFecha($certificado->fecha) , 0, 2, 'L');
        $pdf->Ln(2);
        $pdf->Cell(0, 0, 'EL DEPARTAMENTO DE TALENTO HUMANO', 0, 2, 'C');
        $pdf->Ln(1);
        $pdf->Cell(0, 0, utf8_decode('DE LÁCTEOS DEL CESAR S.A.'), 0, 2, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B');
        $pdf->Cell(0, 0, 'CERTIFICA:', 0, 2, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('Arial');

        $contratoEmpleado = $empleado->getContrato();
        $salario = '.';
        if($certificado->tipo == 2){
            $salario = ', devengando un salario básico mensual de $'.number_format($contratoEmpleado->salario).'.';
        }
        if($certificado->tipo == 3){
            $salario = ', devengando un salario promedio mensual de $'.number_format($contratoEmpleado->salarioPromedio(), 0, '', '.').'.';
        }

        $labora = 'labora';
        $feca_contratacion = "desde el " . $this->formatFecha($contratoEmpleado->fecha_ingreso);
        if($contratoEmpleado->getEstado() == 'retirado'){
            $labora = 'laboró';
            $feca_contratacion .= ', hasta el ' . $this->formatFecha($contratoEmpleado->fecha_retiro);
        }else{
            $feca_contratacion = ' mediante contrato '.$contratoEmpleado->getTipo().', '.$feca_contratacion;
        }

        $pdf->MultiCell(0, 0.6, utf8_decode('Que el señor(a) '
            .strtoupper($empleado->nombres.' '.$empleado->apellido1.' '.$empleado->apellido2.' ')
            .'con cédula de ciudadanía '.$empleado->cedula
            . ", $labora en esta compañía en el cargo de "
            .$contratoEmpleado->getCargo()->nombre .', '. $feca_contratacion
            .$salario), 0, 'J', false);
        $pdf->Ln(2);
        $pdf->Cell(0, 0, utf8_decode('Esta certificación se expide a solicitud del interesado.'), 0, 2);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B');
        $pdf->Cell(0, 0, 'MAYRA ALEJANDRA CARO DAZA', 0, 2, 'C');
        $pdf->Ln(1);
        $pdf->SetFont('Arial');
        $pdf->Cell(0, 0, 'Jefe de Talento Humano', 0, 2, 'C');
        $pdf->Image(realpath(__DIR__.'/../../../../assets/images/Pie.JPG'), 1, 25.5, 19, 'JPG');
        $pdf->Output($certificado->id.'.pdf', 'I');
        return true;
    }

    public function formatFecha($fecha = null)
    {
        $meses =array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        if($fecha instanceof \DateTime){
            return $fecha->format('d') . " de " . $meses[$fecha->format('n')] . " de " . $fecha->format('Y');
        } elseif(is_string($fecha)){
            $año = substr($fecha, 0, 4);
            $mes = substr($fecha, 4, 2);
            $dia = substr($fecha, 6, 2);
            return $dia . " de " . $meses[(int)$mes] . " de " . $año;
        } else {
            throw new \Exception("Error n la generacion del certificado, fecha no valida");
        }
    }
}