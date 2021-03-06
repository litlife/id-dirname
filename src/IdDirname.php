<?php /** @noinspection SpellCheckingInspection */

namespace Litlife\IdDirname;

use Litlife\IdDirname\Exceptions\InvalidArgumentException;

class IdDirname
{
	public $dictionary = '0123456789bcdfghjklmnpqrstvwxyz';
	private $id;
	private $maxNumberOfDigits = 3;

	public function __construct(int $id)
	{
		$this->id = $id;

		if (empty($id))
			throw new InvalidArgumentException('ID must be greater than zero');
	}

	public function getDirnameArray(): array
	{
		$array = $this->splitToArray();

		return array_slice($array, 0, -1);
	}

	public function getDirnameArrayEncoded(): array
	{
		$array = $this->getDirnameArray();

		if (count($array) > 0) {
			foreach ($array as $key => $value)
				$array[$key] = $this->encode($value);
		}

		return $array;
	}

	public function getSalt(): string
	{
		$array = $this->splitToArray();

        return intval(current(array_slice($array, -1)));
	}

	public function getSaltEncoded(): string
	{
		return $this->encode($this->getSalt());
	}

	public function splitToArray(): array
	{
		$id = strrev($this->id);

		$array = str_split($id, $this->getMaxNumberOfDigits());

		foreach ($array as $key => $value)
			$array[$key] = strrev($value);

		return array_reverse($array);
	}

	public function setMaxNumberOfDigits(int $maxNumberOfDigits)
	{
		$this->maxNumberOfDigits = $maxNumberOfDigits;
	}

	public function getMaxNumberOfDigits(): int
    {
		return $this->maxNumberOfDigits;
	}

	public function encode(int $integer): string // Digital number  -->>  alphabet letter code
	{
		$base = mb_strlen($this->dictionary);

		$out = "";

		for ($t = floor(log10($integer) / log10($base)); $t >= 0; $t--) {
			$a = floor($integer / bcpow($base, $t));
			$out = $out . mb_substr($this->dictionary, $a, 1);
			$integer = $integer - ($a * bcpow($base, $t));
		}

		return $this->mb_str_rev($out);
	}

	public function decode(string $code): int // Digital number  <<--  alphabet letter code
	{
		$base = mb_strlen($this->dictionary);

		$code = $this->mb_str_rev($code);
		$out = 0;
		$length = mb_strlen($code) - 1;

		for ($t = 0; $t <= $length; $t++) {
			$bcpow = bcpow($base, $length - $t);
			$out = $out + mb_strpos($this->dictionary, mb_substr($code, $t, 1)) * $bcpow;
		}

		return $out;
	}

    /** @noinspection PhpSameParameterValueInspection */
    private function mb_str_rev($str, $encoding = 'UTF-8'): string
    {
        return mb_convert_encoding(strrev(mb_convert_encoding($str, 'UTF-16BE', $encoding)), $encoding, 'UTF-16LE');
    }
}
