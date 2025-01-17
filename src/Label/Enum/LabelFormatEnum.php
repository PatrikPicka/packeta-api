<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Label\Enum;

enum LabelFormatEnum: string
{
	case A6_ON_A4 = 'A6 on A4';
	case A6_ON_A6 = 'A6 on A6';
	case A7_ON_A4 = 'A7 on A4';
	case A8_ON_A4 = 'A8 on A4';

	public function getMaxOffset(): int
	{
		return match ($this) {
			self::A6_ON_A4 => 3,
			self::A7_ON_A4 => 7,
			self::A8_ON_A4 => 15,
		};
	}
}
