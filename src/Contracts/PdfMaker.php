<?php

namespace PdfMaker\Contracts;

use PdfMaker\Certificates\Data;

interface PdfMaker
{
    public function make(Data $data, array $crtObjects): array;
}
