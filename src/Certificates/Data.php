<?php

namespace PdfMaker\Certificates;

use \IntlDateFormatter;
use \DateTime;

class Data
{
    public string $strDate;

    public function __construct(

        public string $initialDate
        {
            set(string $initialDate) {
                $this->initialDate = $initialDate;

                if (preg_match('/\d{4}-\d{2}-\d{2}/', $initialDate, $matches)) {
                    $this->initialDate = (new DateTime($matches[0]))->format('d/m/Y');
                }
            }
        },
        public string $finalDate
        {
            set(string $finalDate) {
                $this->finalDate = $finalDate;

                if (preg_match('/\d{4}-\d{2}-\d{2}/', $finalDate, $matches)) {
                    $this->finalDate = (new DateTime($matches[0]))->format('d/m/Y');
                }
            }
        },
        public string $personName
        {
            set(string $personName) {
                $this->personName = $personName;
            }
        },
        public string $document
        {
            set(string $document) {
                $this->document = $document;
            }
        }
    )
    {
        $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter->setPattern('d \'de\' MMMM \'de\' yyyy');

        $this->strDate = $formatter->format(new DateTime(implode('-', array_reverse(explode('/', $this->finalDate)))));
    }
}
