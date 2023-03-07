<h1 align="center">
	PDF API Client
</h1>

<p align="center">
  Working with PDF files in PHP should be headache, because you need Imagick extension, Ghostscript interpreter, etc.<br />
  So we created own API for basic PDF operations like merging, converting and stacking.
</p>

##

## Content

- [Setup](#setup)
- [Retrieve Token](#retrieve-token)
- [Initialization](#initialization)
- [Endpoints](#endpoints)
	- [Merge](#merge)
	- [Rasterize PDF](#rasterize-pdf)

## Setup

Install with [Composer](https://getcomposer.org)

```sh
composer require owly-digital/pdf-api-client
```

## Retrieve Token
You can retrieve token by contacting us at info@owly.cz

## Initialization

```php
use Owly\PdfApiClient\PdfApiClient;

$client = new PdfApiClient('token');
```

## Endpoints
List of available endpoints will change over time.
You can also suggest new features by [creating issue](https://github.com/owly-digital/pdf-api-client/issues).

### Merge
```php
$pdfFiles = [
  'path/to/1.pdf',
  'path/to/2.pdf',
];

$mergedPdfFile = $client->mergePdfFiles($pdfFiles);

file_put_contents('merged.pdf', $mergedPdfFile);
```

### Rasterize PDF
🚧 WIP
