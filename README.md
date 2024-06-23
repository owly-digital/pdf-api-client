<h1 align="center">
	PDF API Client
</h1>

<p align="center">
	Working with PDF files in PHP can be a significant challenge. It requires not only the integration of multiple technologies but also the ability to configure the server. To address this, we have developed our own PDF server that handles the most critical operations seamlessly.
</p>

##

## Content

- [Setup](#setup)
- [Retrieve Token](#retrieve-token)
- [Initialization](#initialization)
- [Endpoints](#endpoints)
	- [Merge](#merge)
	- [Convert](#convert)
	- [HTML to PDF](#html-to-pdf)

## Setup

Install with [Composer](https://getcomposer.org)

```sh
composer require owly-digital/pdf-api-client
```

## Retrieve Token
You can retrieve token by contacting us at [info@owly.digital](mailto:info@owly.digital)

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

### HTML to PDF

#### Basic conversion
```php
$html = "<html><body><h1>Look At Me, I'm PDF now!</h1></body></html>";

// $pdf can be directly forwarded for download
$pdf = $client->convertHtmlToPdf($html);

// or with custom filename
$pdf = $client->convertHtmlToPdf($html, 'awesomePdfFile.pdf');
```

#### Advanced conversion
You can customize conversion with [DevTools Protocol Options](https://chromedevtools.github.io/devtools-protocol/tot/Page/#method-printToPDF)
```php
// Show footer with page numbers
$options = [
	'displayHeaderFooter' => true,
	'headerTemplate' => '<div></div>',
	'footerTemplate' => '<div>Page: <span class="pageNumber"></span> / <span class="totalPages"></span></div>'
];
$pdf = $client->convertHtmlToPdf($html, null, $options);
```
