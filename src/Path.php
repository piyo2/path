<?php

namespace piyo2\util\path;

use InvalidArgumentException;
use Normalizer;

final class Path
{
	/**
	 * ディレクトリの区切り文字を正規化する
	 *
	 * @param string $path
	 * @return string
	 */
	public static function normalizeDirectorySeparator(string $path): string
	{
		return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
	}

	/**
	 * パスを結合する
	 *
	 * @param string[] ...$args
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public static function join(...$args): string
	{
		$DSDS = DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;

		if (count($args) === 0) {
			throw new InvalidArgumentException('At least one argument is required');
		}

		$parts = array_map(function ($part) use ($DSDS) {
			$normPart = self::normalizeDirectorySeparator($part);
			if (strpos($normPart, $DSDS) !== false) {
				throw new InvalidArgumentException('Invalid path: ' . $part);
			}
			return self::normalizeDirectorySeparator($normPart);
		}, $args);

		$startsWithSlash = mb_substr($parts[0], 0, 1) === DIRECTORY_SEPARATOR;
		$endsWithSlash = count($parts) >= 2 && mb_substr($parts[count($parts) - 1], -1) === DIRECTORY_SEPARATOR;

		$parts = array_filter(
			explode(DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, $parts)),
			function ($part) {
				return $part !== '';
			}
		);

		$components = [];
		foreach ($parts as $part) {
			if ($part === '.') {
				if (count($components) === 0 && !$startsWithSlash) {
					$components[] = $part;
				}
			} else if ($part === '..') {
				if (count($components) > 0) {
					array_pop($components);
				} else {
					$components[] = $part;
				}
			} else {
				$components[] = $part;
			}
		}

		$joined = ($startsWithSlash ? DIRECTORY_SEPARATOR : '') .
			implode(DIRECTORY_SEPARATOR, $components) .
			($endsWithSlash ? DIRECTORY_SEPARATOR : '');

		if ($joined === $DSDS) {
			return DIRECTORY_SEPARATOR;
		} else {
			return $joined;
		}
	}

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
