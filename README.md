# Gerador de PDF em PHP

Muitas vezes precisamos gerar um PDF com imagem no fundo, seja ele algum convite, algum comprovante e demais situações. Eis o projeto que você buscava.

## Requerimentos
```
    php >= 8.4
```

## Instalação / Execução

1. Crie seu próprio template para o fundo do certificado, conforme exemplo em storage/certificado-default.jpg
2. Precisa modificar a localização dos textos a serem impressos, no arquivo ./pdfconfig.json estão todas as medidas
3. Veja o exemplo no ./index.php e repare que ao configurar o objeto Certificate é possivel passar no texto os labels (:initialDate:, :finalDate: e :workload:) que serão substituídos pelos mesmos, criados no objeto Data.
4. Subir o servidor php
```
    php -S localhost:8000
```
5. Acesse o index.php
6. Preencha o formulário e clique no botão de gerar certificado

Os certificados serão gerados em /storage/generated

## Créditos

* <a href="https://github.com/LincolnBorges/gerador-certificado-php" target="_blank">Projeto original</a>

## Licença

 MIT License
