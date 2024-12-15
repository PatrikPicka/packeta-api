<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Packet;

use InvalidArgumentException;
use PTB\PacketaApi\Common\ResponseInterface;

readonly class PacketResponse implements ResponseInterface
{
	public function __construct(
		private int $id,
		private string $barcode,
		private string $barcodeText,
	) {
	}

	public static function fromArrayResponse(array $data): PacketResponse
	{
		if (isset($data['id']) === false ||
			isset($data['barcode']) === false ||
			isset($data['barcodeText']) === false
		) {
			throw new InvalidArgumentException('PacketaApi Error: One of required "id", "barcode", "barcodeText" is missing in response.');
		}

		return new self(
			(int) $data['id'],
			$data['barcode'],
			$data['barcodeText'],
		);
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getBarcode(): string
	{
		return $this->barcode;
	}

	public function getBarcodeText(): string
	{
		return $this->barcodeText;
	}
}