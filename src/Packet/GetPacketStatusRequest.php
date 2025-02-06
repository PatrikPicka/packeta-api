<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Packet;

use PTB\PacketaApi\Common\RequestInterface;

readonly class GetPacketStatusRequest implements RequestInterface
{
	private const string METHOD = 'packetStatus';

	public function __construct(
		protected int $packetId,
	) {
	}

	public function getMethod(): string
	{
		return self::METHOD;
	}

	public function jsonSerialize(): array
	{
		return [
			'packet_id' => $this->packetId,
		];
	}
}