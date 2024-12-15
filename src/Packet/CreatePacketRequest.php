<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Packet;

use PTB\PacketaApi\Common\RequestInterface;

readonly class CreatePacketRequest implements RequestInterface
{
	private const string METHOD = 'createPacket';

	public function __construct(
		protected string $number,
		/** The Packeta pickup point ID returned from their widget */
		protected int $addressId,
		/** The Packeta pickup point code returned from their widget */
		protected string $carrierPickupPoint,
		protected float $value,
		protected ?float $cod,
		protected string $currency,
		protected float $weight,
		protected int $width,
		protected int $length,
		protected int $height,
		protected string $recipientFirstName,
		protected string $recipientLastName,
		protected string $recipientEmail,
		protected string $recipientPhone,
		/** The Packeta senders indication */
		protected string $eshop,
		protected bool $adultContent = false,
	) {
	}

	public function getMethod(): string
	{
		return self::METHOD;
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