<?php declare(strict_types=1);

namespace Owly\PdfApiClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

class PdfApiClient
{

	private string $url = 'https://pdf.owly.digital/api/v1/';

	private string $token;

	public function __construct(string $token)
	{
		$this->token = $token;
	}

	/**
	 * Returns merged PDF file (not base64 encoded - can be directly forwared for download)
	 *
	 * @param array $files path to files
	 * @return string|null PDF file when success, null when failed
	 */
	public function mergePdfFiles(array $files): ?string
	{
		if (empty($files)) {
			return null;
		}

		$client = HttpClient::create();
		$formDataPart = $this->prepareFormDataPart($files);
		$headers = $formDataPart->getPreparedHeaders()->toArray();
		$headers['X-Auth-Token'] = $this->token;

		$response = $client->request('POST', $this->url . 'pdf/merge', [
			'headers' => $headers,
			'body' => $formDataPart->bodyToString(),
		]);

		$content = json_decode($response->getContent(false), true);

		if (isset($content['errors'])) {
			return null;
		}

		return $content;
	}

	/**
	 * Returns array of base64 encoded files while respecting file names
	 * Ads '_$pageNumber.$format' suffix to filename when PDF with multiple pages uploaded
	 *
	 * @param array $files path to files
	 * @param string $format [jpeg, jpg, png, webp] (default jpeg)
	 * @param int|null $quality may vary for different formats (jpeg / jpg: 0 - 100 (default 85), png: 0 - 9, webp: (default 80))
	 * @param int|null $resolution in pixels per inch (default 600)
	 * @return array|null array<name, base64 encoded file> when success, null when failed
	 */
	public function convertPdfFiles(array $files, string $format = 'jpeg', ?int $quality = null, ?int $resolution = null): ?array
	{
		if (empty($files)) {
			return null;
		}

		$client = HttpClient::create();
		$formDataPart = $this->prepareFormDataPart($files, [
			'format' => $format,
			'quality' => (string) $quality,
			'resolution' => (string) $resolution
		]);
		$headers = $formDataPart->getPreparedHeaders()->toArray();
		$headers['X-Auth-Token'] = $this->token;

		$response = $client->request('POST', $this->url . 'pdf/convert', [
			'headers' => $headers,
			'body' => $formDataPart->bodyToString(),
		]);

		$content = json_decode($response->getContent(false), true);

		if (isset($content['errors'])) {
			return null;
		}

		return $content;

	}

	private function prepareFormDataPart(array $files, array $options = []): FormDataPart
	{
		$dataParts = [];

		foreach ($files as $file) {
			if (file_exists($file)) {
				$dataParts[pathinfo($file, PATHINFO_FILENAME)] = DataPart::fromPath($file, basename($file), 'application/pdf');
			}
		}

		return new FormDataPart($dataParts + $options);
	}

}
