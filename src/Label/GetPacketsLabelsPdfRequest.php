<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Label;

use PTB\PacketaApi\Exception\PacketaException;
use PTB\PacketaApi\Common\RequestInterface;
use PTB\PacketaApi\Label\Enum\LabelFormatEnum;

class GetPacketsLabelsPdfRequest implements RequestInterface
{
	private const METHOD = 'packetsLabelsPdf';

	protected int $offset;

	public function __construct(
		protected array $packetsIds,
		protected LabelFormatEnum $format,
		int $offset = 0,
	) {
		if ($offset > $this->format->getMaxOffset()) {
			throw new PacketaException(sprintf(
				'The max offset for given label format is %d',
				$this->format->getMaxOffset(),
			));
		}

		$this->offset = $offset;
	}

	public function getMethod(): string
	{
		return self::METHOD;
	}

	public function jsonSerialize(): array
	{
		return [
			'packetIds' => [
				'id' => $this->packetsIds,
			],
			'format' => $this->format->value,
			'offset' => $this->offset,
		];
	}
}