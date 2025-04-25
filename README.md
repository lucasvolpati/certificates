# Gerador de certificado em PHP para treinamentos / workshops / cursos e etc.

Muitas vezes precisamos gerar um PDF com imagem no fundo, seja ele algum convite, algum comprovante e demais situações. Nesse exemplo vai ser gerado um certificado de workshop, claro que aplicando o mesmo principio pode ser usado para qualquer fim.

## Requerimentos
```
    php >= 8.4
```

## Instalação / Execução

1. Crie seu próprio template para o fundo do certificado, conforme exemplo em storage/certificado-default.jpg
2. Subir o servidor php
```
    php -S localhost:8000
```
3. Acesse o index.php
4. Preencha o formulário e clique no botão de gerar certificado

Os certificados serão gerados em /storage/generated

## Créditos

* <a href="https://github.com/LincolnBorges/gerador-certificado-php" target="_blank">Projeto original</a>

## Licença

 MIT License
