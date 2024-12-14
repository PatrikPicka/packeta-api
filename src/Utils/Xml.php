<?php

declare(strict_types=1);

namespace PTB\PacketaApi\Utils;

use Spatie\ArrayToXml\ArrayToXml;

class Xml
{
	public static function XmlToArray(string $xml): array
	{
		$simplexml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

		return json_decode(json_encode($simplexml), true, 512, JSON_THROW_ON_ERROR);
	}

	public static function ArrayToXml(string $root, array $array): string
	{
		return ArrayToXml::convert($array, $root);
	}
}