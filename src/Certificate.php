<?php

namespace Certificates;

use \IntlDateFormatter;
use \DateTime;
use Certificates\Data;

class Certificate  
{
    private const TEMPLATES_PATH = __DIR__ . '/../storage/';

    public array $data = [];

    public function __construct(
        public string $name 
        {
            set(string $name) {
                $this->name = $name;
            }
        }, 
        public int|float $workload
        {
            set(int|float $workload) {
                $this->workload = $workload;
            }
        }, 
        string $template
        {
            set(string $fileName) {
                $this->template = $fileName;
            }

            get => self::TEMPLATES_PATH . $this->template;
        },
        string $certificationText
        {
            set(string $certificationText) {
                $text = str_replace(
                    [
                        ':initial_date:',
                        ':final_date:',
                        ':workload:'
                    ],
                    [
                        $this->data['initial_date'],
                        $this->data['final_date'],
                        $this->workload
                    ],
                    $certificationText
                    );
                $this->certificationText = $text;
            }
        },
        ?string $companyIntro = null
        {
            set(?string $companyIntro) {
                $this->companyIntro = $companyIntro;
            }
        }
        )
        {}

    public function setData(Data $data): self
    {
        $this->data['initial_date'] = $data->initialDate;
        $this->data['final_date'] = $data->finalDate;

        return $this;
    }

    // private function formatDates(string $initialDate, string $finalDate): array
    // {
    //     $initial = new DateTime(implode('-', array_reverse(explode('/', $initialDate))));
    //     $final = new DateTime(implode('-', array_reverse(explode('/', $finalDate))));
    //     $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    //     $formatter->setPattern('d \'de\' MMMM \'de\' yyyy');

    //     return [
    //         'finalStrDate' => $formatter->format($final),
    //         'initialBrFormat' => $initialDate,
    //         'finalBrFormat' => $finalDate,
    //     ];
    // }
}