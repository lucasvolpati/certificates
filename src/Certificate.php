<?php

namespace Certificates;

use \IntlDateFormatter;
use \DateTime;
use Certificates\Data;

class Certificate  
{
    private const TEMPLATES_PATH = __DIR__ . '/../storage/';

    public function __construct(
        public Data $data
        {
            set(Data $data) {
                $this->data = $data;
            }
        },
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
                        $this->data->initialDate,
                        $this->data->finalDate,
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

}
