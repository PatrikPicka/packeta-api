<?php

declare(strict_types=1);

use PTB\PacketaApi\Label\Enum\LabelFormatEnum;
use PTB\PacketaApi\Label\GetPacketLabelPdfRequest;
use PTB\PacketaApi\Label\GetPacketsLabelsPdfRequest;
use PTB\PacketaApi\Packet\CreatePacketRequest;
use PTB\PacketaApi\PacketaApi;

require __DIR__ . '/vendor/autoload.php';

$api = new PacketaApi('your_packeta_api_code');
