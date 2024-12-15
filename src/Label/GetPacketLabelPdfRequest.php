<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Label;

use InvalidArgumentException;
use PTB\PacketaApi\Common\RequestInterface;
use PTB\PacketaApi\Label\Enum\LabelFormatEnum;

class GetPacketLabelPdfRequest implements RequestInterface
{
	private const METHOD = 'packetLabelPdf';

	protected int $offset;

	public function __construct(
		protected int $packetId,
		protected LabelFormatEnum $format,
		int $offset = 0,
	) {
		if ($offset > $this->format->getMaxOffset()) {
			throw new InvalidArgumentException(sprintf(
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
			'packetId' => $this->packetId,
			'format' => $this->format->value,
			'offset' => $this->offset,
		];
	}
}