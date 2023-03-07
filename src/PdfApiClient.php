<?php declare(strict_types=1);

namespace Owly\PdfApiClient;

use Symfony\Component\HttpClient\HttpClient;
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
			$multipart = [];

			foreach ($files as $file) {
				if (file_exists($file)) {
					$multipart[] = [
						'name' => pathinfo($file, PATHINFO_FILENAME),
						'filename' => pathinfo($file, PATHINFO_FILENAME),
						'contents' => file_get_contents($file)
					];
				}
			}

			$response = $client->request('POST', $this->url, [
				'multipart' => $multipart,
				'headers' => [
					'X-Auth-Token' => $this->token
				]
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
