<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Packet\Enum;

enum PacketStatusEnum: int
{
	case RECEIVED_DATA = 1;
	case ARRIVED = 2;
	case PREPARED_FOR_DEPARTURE = 3;
	case DEPARTED = 4;
	case READY_FOR_PICKUP = 5;
	case HANDED_TO_CARRIER = 6;
	case DELIVERED = 7;
	case POSTED_BACK = 9;
	case RETURNED = 10;
	case CANCELLED = 11;
	case COLLECTED = 12;
	case CUSTOMS = 14;
	case REVERSE_PACKET_ARRIVED = 15;
	case DELIVERY_ATTEMPT = 16;
	case REJECTED_BY_RECIPIENT = 17;
	case UNKNOWN = 999;
}
