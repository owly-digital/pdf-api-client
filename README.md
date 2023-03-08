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
	- [Convert](#convert)

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

### Convert
Convert PDF files (also with multiple pages) into various formats.

**Parameters:**
- Formats: `jpg`, `png`, `webp` (default `jpg`)
- Quality: _(detected automatically when omited)_
  - `jpg`: 0 - 100 (default `85`)
  - `png`: 0 - 9 (default `9`)
  - `webp`: (default `80`)
- Resolution (in pixels per inch) (default `600` - suitable for printing)

#### Example

```php
$pdfFiles = [
  'path/to/1.pdf',
  'path/to/2.pdf',
];

// Basic conversion with automatically detected quality and printable resolution
$convertedFiles = $client->convertPdfFiles($pdfFiles, 'jpg');

// With custom quality and resolution
$convertedFiles = $client->convertPdfFiles($pdfFiles, 'jpg', 50, 300);

// Conversion to webp with custom resolution (default quality)
$convertedFiles = $client->convertPdfFiles($pdfFiles, 'webp', null, 300);

foreach ($convertedFiles as $name => $file) {
	file_put_contents('/path/' . $name, base64_decode($file));
}
```

#### PDF with multiple pages
When PDF with multiple pages passed, to each page is added suffix with page number.

```
Input:
awesomePdfFile.pdf (3 pages)

Output:
awesomePdfFile_0.jpg
awesomePdfFile_1.jpg
awesomePdfFile_2.jpg
```
