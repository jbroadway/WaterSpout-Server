<?php
/**
 * Request headers.
 *
 * @package Waterspout
 * @author  Scott Mattocks
 */
class HTTPHeaders extends ArrayObject
{
	/**
	 * Normalizes a header name.
	 *
	 * @static
	 * @access private
	 * @param  string
	 * @return string
	 */
	private static function _normalize_name($name)
	{
		static $names = array();

		if (!array_key_exists($name, $names))
		{
			$names[$name] = strtolower($name);
		}

		return $names[$name];
	}

	/**
	 * Determines if a header exists.
	 *
	 * @access public
	 * @param  string  $offset
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return parent::offsetExists(self::_normalize_name($offset));
	}

	/**
	 * Returns the given header.
	 *
	 * @access public
	 * @param  string $offset
	 * @return void
	 */
	public function offsetGet($offset)
	{
		if ($this->offsetExists($offset))
		{
			return parent::offsetGet(self::_normalize_name($offset));
		}

		return null;
	}

	/**
	 * Sets a header value.
	 *
	 * @access public
	 * @param  string $offset
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		return parent::offsetSet(self::_normalize_name($offset), $value);
	}

	/**
	 * Clears a header.
	 *
	 * @access public
	 * @param  string $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		return parent::offsetUnset(self::_normalize_name($offset));
	}

	/**
	 * Returns the given header.
	 *
	 * @access public
	 * @param  string $name
	 * @return void
	 */
	public function __get($name)
	{
		return $this->offsetGet($name);
	}

	/**
	 * Sets a header value.
	 *
	 * @access public
	 * @param  string $name
	 * @param  mixed  $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->offsetSet($name, $value);
	}

	/**
	 * Checks to see if a header exists.
	 *
	 * @access public
	 * @param  string $name
	 * @return void
	 */
	public function has($name)
	{
		return $this->offsetExists($name);
	}

	/**
	 * Returns the given header's value.
	 *
	 * @access public
	 * @param  string $name
	 * @return void
	 */
	public function get($name)
	{
		return $this->offsetGet($name);
	}

	/**
	 * Turns a header block into a set of headers.
	 *
	 * @access public
	 * @param  string $headers_string
	 * @return void
	 */
	public static function parse($headers_string)
	{
		$headers = new HTTPHeaders();
		foreach (explode("\r\n", $headers_string) as $line)
		{
			if (!empty($line))
			{
				if (strpos($line, ': '))
				{
					list($name, $value) = explode(': ', $line, 2);
					$headers[$name] = $value;
				}
			}
		}

		return $headers;
	}
}
?>