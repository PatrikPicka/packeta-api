<?php

declare(strict_types=1);

namespace PTB\PacketaApi;

use Exception;
use InvalidArgumentException;
use JsonSerializable;
use PTB\PacketaApi\Utils\Xml;

readonly class PacketaApi
{
	const string PACKETA_API_REST_URL = 'https://www.zasilkovna.cz/api/rest';

	public function __construct(
		private string $apiKey,
	) {
	}

	public function callApi(string $method, JsonSerializable|array $data)
	{
		$xmlArrayData['apiPassword'] = $this->apiKey;

		if ($data instanceof JsonSerializable) {
			$xmlArrayData = array_merge($xmlArrayData, $data->jsonSerialize());
		} elseif (is_array($data)) {
			$xmlArrayData = array_merge($xmlArrayData, $data);
		} else {
			throw new InvalidArgumentException('PacketaApi Error: Expected json serializable object or array.');
		}

		$xmlData = Xml::ArrayToXml($method, $xmlArrayData);

		$xmlResponse = $this->post($xmlData);
		$arrayResult = Xml::XmlToArray($xmlResponse);
		$this->processResult($arrayResult);

		return $arrayResult['result'];
	}

	private function post(string $xml): string
	{
		$context = stream_context_create([
			'http' => [
				'method' => 'POST',
				'header' => 'Content-type: text/xml',
				'content' => $xml,
			],
		]);

		$response = @file_get_contents(self::PACKETA_API_REST_URL, false, $context);

		if ($response !== false) {
			return $response;
		}

		if (!isset($http_response_header)) {
			throw new Exception('PacketaApi Error: Can\'t connect to packeta API service.');
		}

		if (empty($http_response_header)) {
			throw new Exception('PacketaApi Error: Can\'t connect to packeta API service.');
		}

		preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $http_response_header[0], $out);
		throw new Exception('PacketaApi Error: Unsuccessful attempt to retrieve data from packeta API.', intval($out[1]));
	}

	private function processResult(array $result): void
	{
		if (($result['status'] ?? '') === 'fault') {
			if ($result['fault'] === 'PacketAttributesFault') {
				throw new Exception('PacketaApi Error: ' . $result['detail']['attributes']['fault']);
			}

			$resultFaultsInfo = [$result['fault'] . ': ' . ($result['string'] ?? 'Unknown error')];

			if (isset($result['detail']) && $result['detail']) {
				$resultFaultsInfo[] = PHP_EOL . 'Details: ' . json_encode($result['detail']);
			}

			throw new Exception('PacketaApi Error: ' . join('', $resultFaultsInfo));
		} elseif (isset($result['result']) === false) {
			throw new Exception('PacketaApi Error: There is no result in the response.');
		}
	}
}