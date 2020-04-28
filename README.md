# PDFMerger for PHP 7

Original written by http://pdfmerger.codeplex.com/team/view

Forked from https://github.com/clegginabox/pdf-merger

## Installation

```composer require jmleroux/pdf-merger:dev-master```

## Example Usage
```php

$pdf = new \Jmleroux\PDFMerger\PDFMerger;

$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4');
$pdf->addPDF('samplepdfs/two.pdf', '1-2');
$pdf->addPDF('samplepdfs/three.pdf', 'all');

//You can optionally specify a different orientation for each PDF
$pdf->addPDF('samplepdfs/one.pdf', '1, 3, 4', 'L');
$pdf->addPDF('samplepdfs/two.pdf', '1-2', 'P');

$pdf->merge('file', 'samplepdfs/TEST2.pdf', 'P');

// REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
// Last parameter is for orientation (P for protrait, L for Landscape). 
// This will be used for every PDF that doesn't have an orientation specified
```

## Development

This repo is shipped with a docker-compose file so that you don't need a local version of PHP.

Use make commands to install and run tests:

To install dependencies:

```
make vendor
```

To run tests:

```
make tests
```
