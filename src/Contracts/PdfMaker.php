<?php

namespace Certificates\Contracts;

use Certificates\Data;

interface PdfMaker
{
    public function make(Data $data, array $crtObjects): array;
}
