<?php

namespace piyo2\util\path;

use Normalizer;

final class Path
{
	/**
	 * ファイル名として使えない文字を除去する
	 *
	 * @param string $name
	 * @param bool $allowDot . で始まるファイル名を許可するかどうか
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	public static function sanitizeFileName(string $name, bool $allowDot = false): string
	{
		$normalized = Normalizer::normalize($name);
		$normalized = preg_replace('#[\\x00-\\x1F*"\\\\/<>:|?]#', '_', $normalized);
		$normalized = preg_replace('#[.]{2,}#', '__', $normalized);
		$normalized = rtrim($normalized, '.');
		if (!$allowDot) {
			$normalized = ltrim($normalized, '.');
		}
		$normalized = trim($normalized);
		if ($normalized === '') {
			return 'untitled';
		}
		return $normalized;
	}
}
