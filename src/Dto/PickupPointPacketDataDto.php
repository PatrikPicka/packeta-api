<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Dto;

use JsonSerializable;

readonly class PickupPointPacketDataDto implements JsonSerializable
{
	public function __construct(
		public string $number,
		/** The Packeta pickup point ID returned from their widget */
		public int $addressId,
		/** The Packeta pickup point code returned from their widget */
		public string $carrierPickupPoint,
		public float $value,
		public ?float $cod,
		public string $currency,
		public float $weight,
		public int $width,
		public int $length,
		public int $height,
		public string $recipientFirstName,
		public string $recipientLastName,
		public string $recipientEmail,
		public string $recipientPhone,
		/** The Packeta senders indication */
		public string $eshop,
		public bool $adultContent = false,
	) {
	}

	public function jsonSerialize(): array
	{
		$data = [
			'packetAttributes' => [
				'number' => $this->number,
				'addressId' => $this->addressId,
				'carrierPickupPoint' => $this->carrierPickupPoint,
				'value' => $this->value,
				'currency' => $this->currency,
				'weight' => $this->weight,
				'size' => [
					'width' => $this->width,
					'length' => $this->length,
					'height' => $this->height,
				],
				'name' => $this->recipientFirstName,
				'surname' => $this->recipientLastName,
				'email' => $this->recipientEmail,
				'phone' => $this->recipientPhone,
				'eshop' => $this->eshop,
				'adultContent' => (int) $this->adultContent,
			],
		];

		if ($this->cod !== null) {
			$data['packetAttributes']['cod'] = $this->cod;
		}

		return $data;
	}
}