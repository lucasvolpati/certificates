<?php

namespace Certificates;

use Certificates\Certificate;
use Certificates\Data;
use Certificates\Contracts\PdfMaker;

class Generator 
{
    private array $crtObjects;

    public function __construct(
        protected PdfMaker $pdfMaker
    )
    {}

    public function make(Data $data): array
    {
        return $this->pdfMaker->make($data, $this->crtObjects);
    }

    public function setCertificates(Certificate ...$crtObjects)
    {
        $this->crtObjects = $crtObjects;

        return $this;
    }
}
