<?php declare(strict_types=1);

namespace Owly\PdfApiClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PdfApiClient
{

	private string $url = 'https://pdf.owly.digital/';

	private string $token;

	public function __construct(string $token)
	{
		$this->token = $token;
	}

	/**
	 * Returns merged PDF file
	 * @param array $files - paths
	 * @return string|null - PDF file when success, null when failed
	 */
	public function mergePdfFiles(array $files): ?string
	{
		$client = HttpClient::create();

		try {
			$dataParts = [];

			foreach ($files as $file) {
				if (file_exists($file)) {
					$dataParts[pathinfo($file, PATHINFO_FILENAME)] = DataPart::fromPath($file, basename($file), 'application/pdf');
				}
			}

			$formDataPart = new FormDataPart($dataParts);
			$headers = $formDataPart->getPreparedHeaders()->toArray();
			$headers['X-Auth-Token'] = $this->token;

			$response = $client->request('POST', $this->url . 'api/v1/pdf/merge', [
				'headers' => $headers,
				'body' => $formDataPart->bodyToString(),
			]);

			return $response->getContent();
		} catch (
			ClientExceptionInterface |
			RedirectionExceptionInterface |
			ServerExceptionInterface |
			TransportExceptionInterface $e
		) {
			return null;
		}
	}
}
