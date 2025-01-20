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

    private function getConfig()
    {
        $configFile = __DIR__ . '/../pdfConfig.json';
        $config = json_decode(file_get_contents($configFile));

        return $config;
    }

    public function make(string $pdfClass, Data $data): array
    {
        $config = $this->getConfig();

        $response = [];
        foreach ($this->availableCerts as $cert) {
            $pdfInstance = $this->getInstance($pdfClass);
            
            $pdfInstance->AddPage('L');
            $pdfInstance->SetLineWidth(1);
            $pdfInstance->Image($cert->template,0,0,295);

            // Company intro
            $pdfInstance->SetFont($config->companyIntro->fontName, '', $config->companyIntro->fontSize); 
            $pdfInstance->SetXY($config->companyIntro->x, $config->companyIntro->y); 
            $pdfInstance->MultiCell(265, 50, mb_convert_encoding($cert->companyIntro, 'ISO-8859-1', 'UTF-8'), '', 'L', 0);

            // Person name
            $pdfInstance->SetFont($config->personName->fontName, '', $config->personName->fontSize); 
            $pdfInstance->SetXY($config->personName->x, $config->personName->y); 
            $pdfInstance->MultiCell(265, 10, mb_convert_encoding($data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Certification text
            $pdfInstance->SetFont($config->certificationText->fontName, '', $config->certificationText->fontSize);
            $pdfInstance->SetXY($config->certificationText->x, $config->certificationText->y); 
            $pdfInstance->MultiCell(260, 7, mb_convert_encoding($cert->certificationText, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

            // Certification Date
            $pdfInstance->SetFont($config->certificationDate->fontName, '', $config->certificationDate->fontSize);
            $pdfInstance->SetXY($config->certificationDate->x, $config->certificationDate->y); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($data->strDate, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Person signature name
            $pdfInstance->SetFont($config->signatureName->fontName, '', $config->signatureName->fontSize);
            $pdfInstance->SetXY($config->signatureName->x, $config->signatureName->y); 
            $pdfInstance->MultiCell(265, 30, mb_convert_encoding($data->personName, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

            //Person signature document (CPF)
            $pdfInstance->SetFont($config->signatureDocument->fontName, '', $config->signatureDocument->fontSize);
            $pdfInstance->SetXY($config->signatureDocument->x, $config->signatureDocument->y); 
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
