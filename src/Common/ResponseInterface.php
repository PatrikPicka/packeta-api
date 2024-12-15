<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Common;

interface ResponseInterface
{
	public static function fromArrayResponse(array $data): ResponseInterface;
}