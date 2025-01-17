<?php
    setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
    date_default_timezone_set( 'America/Sao_Paulo' );
    ini_set('display_errors', true);
    require __DIR__ . '/../vendor/autoload.php';

    use Fpdf\Fpdf;
    use \IntlDateFormatter;

    $data = filter_input_array(INPUT_POST);

    $data = array_map(function($field) {
        if (preg_match('/\d{4}-\d{2}-\d{2}/', $field, $matches)) {
            $date = (new \DateTime($matches[0]))->format('d/m/Y');
            return $date;
        }
        return $field;
    }, $data);




    $list = ['nr33', 'nr35'];
    $company = ""; 
    $initialDate = implode('-', array_reverse(explode('/', $data['initial_date'])));
    $finalDate = implode('-', array_reverse(explode('/', $data['final_date'])));
    $workloadNr33 = "40";
    $workloadNr35 = "8";

    $initial = new \DateTime($initialDate);
    $final = new \DateTime($finalDate);
    $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $formatter->setPattern('d \'de\' MMMM \'de\' yyyy'); // Personaliza o padrão

    $certData = [];
    $certData['initial_date'] = $formatter->format($initial);
    $certData['final_date'] = $formatter->format($final);
    $certData['nome'] = $data['name'];
    $certData['cpf'] = $data['cpf'];

    $txt1 = $company;
    $txt3 = "São Paulo, " . $certData['final_date'];

    foreach ($list as $nr) {
        $certData['carga_horaria'] = $nr == 'nr33' ? $workloadNr33 : $workloadNr35;

        $text = [
            'nr33' => "conclui satisfatoriamente o treinamento para entrada e trabalho em espaço confinado, em cumprimento da portaria MTE n°202, de 22 de dezembro de 2006 - publicada no DOU em 27 de dezembro de 2012, que aprova a NR 33 que trata da segurança e saúde nos trabalhos em espaços confinados. Realizado nos dias {$data['initial_date']} a {$data['final_date']} na cidade de São Paulo-SP, com carga horária de {$certData['carga_horaria']} horas.",
            'nr35' => "participou do treinamento de segurança para trabalhos em altura, em cumprimento da portaria SIT nº313, de 23 de março de 2012 - Publicada no DOU em 27 de março de 2012, que aprova a NR-35 que trata da seurança e saúde em trabalhos em altura. Realizado no dia {$data['final_date']} na cidade de São Paulo-SP, com carga horária de {$certData['carga_horaria']} horas."
        ];
        
        $txt2 = $text[$nr];

        $pdf = new FPDF('L','mm','A4');

        $pdf->AddPage('L');
        $pdf->SetLineWidth(1);
        $pdf->Image("fundo_{$nr}.png",0,0,295);

        // Print top text
        $pdf->SetFont('Arial', '', 15); 
        $pdf->SetXY(100,42); 
        $pdf->MultiCell(265, 50, mb_convert_encoding($txt1, 'ISO-8859-1', 'UTF-8'), '', 'L', 0);

        // Print person name
        $pdf->SetFont('Arial', '', 20); 
        $pdf->SetXY(20,73); 
        $pdf->MultiCell(265, 10, mb_convert_encoding($data['nome'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

        // Print body
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(17,90); 
        $pdf->MultiCell(260, 7, mb_convert_encoding($txt2, 'ISO-8859-1', 'UTF-8'), '', 'C', 0); 

        // Print certificate date
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(20,120); 
        $pdf->MultiCell(265, 30, mb_convert_encoding($txt3, 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

        //Print signature name
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(83,154); 
        $pdf->MultiCell(265, 30, mb_convert_encoding($certData['nome'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

        //Print signature document (CPF)
        $pdf->SetFont('Arial', '', 15);
        $pdf->SetXY(83,159.9); 
        $pdf->MultiCell(265, 30, 'CPF: ' . mb_convert_encoding($certData['cpf'], 'ISO-8859-1', 'UTF-8'), '', 'C', 0);

        $pdfdoc = $pdf->Output('', 'S');

        $name = str_replace(" ", '-', $certData['nome']);

        $cert = "arquivos/{$name}-{$nr}.pdf";
        $pdf->Output($cert,'F');
    }
?>
