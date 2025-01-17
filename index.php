<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Gerar Certificado PDF em PHP - Enviando por e-mail</title>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
    <link rel='stylesheet prefetch' href='http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css'>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <form class="form-horizontal" method="post" id="contact_form">
            <fieldset>
                <center>
                    <h1>Gere seu certificado online</h1>
                </center>
                <p>&nbsp;</p>
                <div class="form-group">
                    <label class="col-md-4 control-label">Nome</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input name="name" placeholder="Nome completo" class="form-control" type="text">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">CPF</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                            <input name="cpf" placeholder="CPF" class="form-control" type="text" maxlength="14" onkeypress="formatar('###.###.###-##', this);">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Data Inicial</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                            <input name="initial_date" placeholder="Data Inicial" class="form-control" type="date">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label">Data Final</label>
                    <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                            <input name="final_date" placeholder="Data Final" class="form-control" type="date">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label"></label>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default col-md-12">Gerar Certificado <span class="glyphicon glyphicon-download-alt"></span></button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>

    <script>
        function formatar(mascara, documento) {
            var i = documento.value.length;
            var saida = mascara.substring(0, 1);
            var texto = mascara.substring(i);
            if (texto.substring(0, 1) != saida) {
                documento.value += texto.substring(0, 1);
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#contact_form').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            stringLength: {
                                min: 2,
                            },
                            notEmpty: {
                                message: 'Insira o seu nome'
                            }
                        }
                    },
                    cpf: {
                        validators: {
                            callback: {
                                message: 'CPF Invalido',
                                callback: function(value) {
                                    cpf = value.replace(/[^\d]+/g, '');
                                    if (cpf == '') return false;

                                    if (cpf.length != 11) return false;

                                    var valido = 0;
                                    for (i = 1; i < 11; i++) {
                                        if (cpf.charAt(0) != cpf.charAt(i)) valido = 1;
                                    }
                                    if (valido == 0) return false;

                                    aux = 0;
                                    for (i = 0; i < 9; i++)
                                        aux += parseInt(cpf.charAt(i)) * (10 - i);
                                    check = 11 - (aux % 11);
                                    if (check == 10 || check == 11)
                                        check = 0;
                                    if (check != parseInt(cpf.charAt(9)))
                                        return false;

                                    aux = 0;
                                    for (i = 0; i < 10; i++)
                                        aux += parseInt(cpf.charAt(i)) * (11 - i);
                                    check = 11 - (aux % 11);
                                    if (check == 10 || check == 11)
                                        check = 0;
                                    if (check != parseInt(cpf.charAt(10)))
                                        return false;
                                    return true;
                                }
                            }
                        }
                    }
                }
            })

        });
    </script>
</body>

</html>

<?php

    require __DIR__ . '/vendor/autoload.php';

    use Certificates\Generator;
    use Fpdf\Fpdf;

    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if($data ) {
        $certGen = (new Generator())->setData($data)->make(FPDF::class);
        
        echo "<pre>";
        print_r($certGen);
        echo "</pre>";
    }

?>