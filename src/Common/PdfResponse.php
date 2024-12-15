<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Common;

class PdfResponse implements ResponseInterface
{
	public function __construct(
		private string $pdfContent,
	) {
	}

	public static function fromArrayResponse(array $data): PdfResponse
	{
		return new self($data['pdfContent']);
	}

	public function getContent(): string
	{
		return base64_decode($this->pdfContent);
	}
}