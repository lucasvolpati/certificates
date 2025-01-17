<?php

namespace Certificates;

use \IntlDateFormatter;
use \DateTime;
use Fpdf\Fpdf;

class Generator 
{
    private const NR33_BACKGROUND = __DIR__ . '/../storage/fundo_nr33.png';
    private const NR35_BACKGROUND = __DIR__ . '/../storage/fundo_nr35.png';

    private array $availableCerts = [
        'nr33', 
        'nr35'
    ];

    private array $availableWorkloads = [
        'nr33' => '40', 
        'nr35' => '8'
    ];

    private $data = [
        'initial_date' => '',
        'final_date' => '',
        'str_date' => '',
        'principal_text' => '',
        'name' => '',
        'cpf' => ''
    ];

    private $companyText = "";

    private function getInstance(string $className)
    {
        return new $className;
    }

    public function make(string $pdfClass): array
    {
        $response = [];
        foreach ($this->availableCerts as $cert) {
            $this->setPrincipalText($cert);
            $background = $cert == 'nr33' ? self::NR33_BACKGROUND : self::NR35_BACKGROUND;

            $pdfInstance = $this->getInstance($pdfClass);
            
            $pdfInstance->AddPage('L');
            $pdfInstance->SetLineWidth(1);
            $pdfInstance->Image($background,0,0,295);

            // Print top text
            $pdfInstance->SetFont('Arial', '', 15); 
            $pdfInstance->SetXY(100,42); 
            $pdfInstance->MultiCell(265, 50, mb_convert_encoding($this->companyText, 'ISO-8859-1', 'UTF-8'), '', 'L', 0);

            // Print person name
            $pdfInstance->SetFont('Arial', '', 20); 
            $pdfInstance->SetXY(20,73); 
            $pdfInstance->MultiCell(265, 10, mb_convert_encoding($this->data['name'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Print body
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(17,90); 
            $pdfInstance->MultiCell(260, 7, mb_convert_encoding($this->data['principal_text'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Print certificate date
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(20,120); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($this->data['str_date'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Print signature name
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(83,154); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($this->data['name'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Print signature document (CPF)
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(83,159.9); 
            $pdfInstance->MultiCell(265, 30, 'CPF: ' . mb_convert_encoding($this->data['cpf'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            $pdfdoc = $pdfInstance->Output('', 'S');

            $name = str_replace(" ", '-', $this->data['name']);

            $nameFormatted = "storage/generated/{$name}-{$cert}.pdf";
            $pdfInstance->Output($nameFormatted,'F');

            $response[$cert] = 'Criado com sucesso';
        }

        return $response;
    }

    private function formatDates(string $initialDate, string $finalDate): array
    {
        $initial = new DateTime(implode('-', array_reverse(explode('/', $initialDate))));
        $final = new DateTime(implode('-', array_reverse(explode('/', $finalDate))));
        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter->setPattern('d \'de\' MMMM \'de\' yyyy');

        return [
            'finalStrDate' => $formatter->format($final),
            'initialBrFormat' => $initialDate,
            'finalBrFormat' => $finalDate,
        ];
    }

    public function setData(array $request): self
    {
        $data = array_map(function($field) {
            if (preg_match('/\d{4}-\d{2}-\d{2}/', $field, $matches)) {
                $date = (new \DateTime($matches[0]))->format('d/m/Y');
                return $date;
            }
            return $field;
        }, $request);

        $dates = $this->formatDates($data['initial_date'], $data['final_date']);

        $this->data['initial_date'] = $dates['initialBrFormat'];
        $this->data['final_date'] = $dates['finalBrFormat'];
        $this->data['str_date'] = $dates['finalStrDate'];
        $this->data['name'] = $data['name'];
        $this->data['cpf'] = $data['cpf'];

        return $this;
    }

    public function setPrincipalText(string $cert): self
    {
        $text = [
            'nr33' => "conclui satisfatoriamente o treinamento para entrada e trabalho em espaço confinado, em cumprimento da portaria MTE n°202, de 22 de dezembro de 2006 - publicada no DOU em 27 de dezembro de 2012, que aprova a NR 33 que trata da segurança e saúde nos trabalhos em espaços confinados. Realizado nos dias {$this->data['initial_date']} a {$this->data['final_date']} na cidade de São Paulo-SP, com carga horária de {$this->availableWorkloads[$cert]} horas.",
            'nr35' => "participou do treinamento de segurança para trabalhos em altura, em cumprimento da portaria SIT nº313, de 23 de março de 2012 - Publicada no DOU em 27 de março de 2012, que aprova a NR-35 que trata da seurança e saúde em trabalhos em altura. Realizado no dia {$this->data['final_date']} na cidade de São Paulo-SP, com carga horária de {$this->availableWorkloads[$cert]} horas."
        ];

        $this->data['principal_text'] = $text[$cert];

        return $this;
    }
}
