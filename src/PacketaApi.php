<?php

declare(strict_types=1);

namespace PTB\PacketaApi;

use PTB\PacketaApi\Exception\PacketaException;
use PTB\PacketaApi\Common\PdfResponse;
use PTB\PacketaApi\Common\RequestInterface;
use PTB\PacketaApi\Label\GetPacketLabelPdfRequest;
use PTB\PacketaApi\Label\GetPacketsLabelsPdfRequest;
use PTB\PacketaApi\Packet\Enum\PacketStatusEnum;
use PTB\PacketaApi\Packet\GetPacketStatusRequest;
use PTB\PacketaApi\Packet\PacketResponse;
use PTB\PacketaApi\Utils\Xml;

readonly class PacketaApi
{
	const string PACKETA_API_REST_URL = 'https://www.zasilkovna.cz/api/rest';

	public function __construct(
		private string $apiKey,
	) {
	}

	public function createPacket(RequestInterface $request): PacketResponse
	{
		return PacketResponse::fromArrayResponse($this->sendRequest($request));
	}

	public function getPacketStatus(GetPacketStatusRequest $request): PacketStatusEnum
	{
		$responseStatusCode = $this->sendRequest($request)['statusCode'];

		$packetStatus = PacketStatusEnum::tryFrom((int) $responseStatusCode);

		if ($packetStatus === null) {
			throw new PacketaException(sprintf(
				'Unsupported Packet Status: %s - Supported Packet Statuses: %s',
				$responseStatusCode,
				implode(', ', array_map(fn (
					PacketStatusEnum $packetStatus): int => $packetStatus->value,
					PacketStatusEnum::cases(),
				))
			));
		}

		return $packetStatus;
	}

	public function getPacketLabelPdf(GetPacketLabelPdfRequest $request): PdfResponse
	{
		return PdfResponse::fromArrayResponse(['pdfContent' => $this->sendRequest($request)]);
	}

	public function getPacketsLabelsPdf(GetPacketsLabelsPdfRequest $request): PdfResponse
	{
		return PdfResponse::fromArrayResponse(['pdfContent' => $this->sendRequest($request)]);
	}

	private function sendRequest(RequestInterface $request): mixed
	{
		$xmlArrayData['apiPassword'] = $this->apiKey;

		$xmlArrayData = array_merge($xmlArrayData, $request->jsonSerialize());

		$xmlData = Xml::ArrayToXml($request->getMethod(), $xmlArrayData);

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
			throw new PacketaException('PacketaApi Error: Can\'t connect to packeta API service.');
		}

		if (empty($http_response_header)) {
			throw new PacketaException('PacketaApi Error: Can\'t connect to packeta API service.');
		}

		preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $http_response_header[0], $out);
		throw new PacketaException('PacketaApi Error: Unsuccessful attempt to retrieve data from packeta API.', intval($out[1]));
	}

	private function processResult(array $result): void
	{
		if (($result['status'] ?? '') === 'fault') {
			if ($result['fault'] === 'PacketAttributesFault') {
				throw new PacketaException('PacketaApi Error: ' . $result['detail']['attributes']['fault']);
			}

			$resultFaultsInfo = [$result['fault'] . ': ' . ($result['string'] ?? 'Unknown error')];

			if (isset($result['detail']) && $result['detail']) {
				$resultFaultsInfo[] = PHP_EOL . 'Details: ' . json_encode($result['detail']);
			}

			throw new PacketaException('PacketaApi Error: ' . join('', $resultFaultsInfo));
		} elseif (isset($result['result']) === false) {
			throw new PacketaException('PacketaApi Error: There is no result in the response.');
		}
	}
}