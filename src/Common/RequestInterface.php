<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Common;

use JsonSerializable;

interface RequestInterface extends JsonSerializable
{
	public function getMethod(): string;
}