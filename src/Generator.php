<?php

namespace Certificates;

use \IntlDateFormatter;
use \DateTime;
use Fpdf\Fpdf;
use Certificates\Certificate;
use Certificates\Data;

class Generator 
{
    private array $availableCerts = [];

    public function __construct(Certificate ...$objects)
    {
        $this->availableCerts = $objects;
    }

    private function getInstance(string $className)
    {
        return new $className;
    }

    public function make(string $pdfClass, Data $data): array
    {
        $response = [];
        foreach ($this->availableCerts as $cert) {
            $pdfInstance = $this->getInstance($pdfClass);
            
            $pdfInstance->AddPage('L');
            $pdfInstance->SetLineWidth(1);
            $pdfInstance->Image($cert->template,0,0,295);

            // Print top text
            $pdfInstance->SetFont('Arial', '', 22); 
            $pdfInstance->SetXY(78,28); 
            $pdfInstance->MultiCell(265, 50, mb_convert_encoding($cert->companyIntro, 'ISO-8859-1', 'UTF-8'), '', 'L', 0);

            // Print person name
            $pdfInstance->SetFont('Arial', '', 20); 
            $pdfInstance->SetXY(20,73); 
            $pdfInstance->MultiCell(265, 10, mb_convert_encoding($data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Print body
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(17,90); 
            $pdfInstance->MultiCell(260, 7, mb_convert_encoding($cert->certificationText, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Print certificate date
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(20,120); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($data->strDate, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Print signature name
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(83,154); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Print signature document (CPF)
            $pdfInstance->SetFont('Arial', '', 15);
            $pdfInstance->SetXY(83,159.9); 
            $pdfInstance->MultiCell(265, 30, 'CPF: ' . mb_convert_encoding($data->document, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            $pdfdoc = $pdfInstance->Output('', 'S');

            $name = str_replace(" ", '-', $data->personName);

            $nameFormatted = "storage/generated/{$name}-{$cert->name}.pdf";
            $pdfInstance->Output($nameFormatted,'F');

            $response[$cert->name] = 'Criado com sucesso';
        }

        return $response;
    }
}
