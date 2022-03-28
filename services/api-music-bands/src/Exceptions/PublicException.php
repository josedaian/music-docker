<?php

namespace MusicBands\Exceptions;

use Exception;
use MusicBands\Utils\Response;
use Throwable;

class PublicException extends Exception
{
    const HINT_DUPLICATE = 'db.duplicate';
    private $attr;

    function __construct(array $attr){
        $this->attr = array_merge([
            'text' => 'NO_TEXT',
            'infoCode' => null,
            'exCode' => 0,
            'httpCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'hintCode' => null,
            'data' => null
        ], $attr);

        parent::__construct(
            $this->attr['text'],
            $this->attr['exCode'],
            isset($attr['exception']) && is_a($attr['exception'], Throwable::class) ? $attr['exception'] : null
        );
    }

    /**
     * @param string $text
     * @param string $infoCode
     * @param int $httpCode
     * @param array $attr
     * @return PublicException
     */
    static function validationError( string $text, string $infoCode, int $httpCode = Response::HTTP_BAD_REQUEST, array $attr=[]):self{
        return new PublicException(array_merge($attr, ['infoCode' => $infoCode, 'text' => $text, 'httpCode' => $httpCode]));
    }

    /**
     * @param string $text
     * @param string $infoCode
     * @param int $httpCode
     * @param array $attr
     * @return PublicException
     */
    static function externalError( string $text, string $infoCode, int $httpCode = 400, array $attr=[]):self{
        return new PublicException(array_merge($attr,['infoCode' => 'external@'.$infoCode, 'text' => $text, 'httpCode' => $httpCode]) );
    }

    /**
     * @param string $text
     * @param string $infoCode
     * @param int $httpCode
     * @param array $attr
     * @return PublicException
     */
    static function internalError( string $text, string $infoCode, int $httpCode = 500, array $attr=[]):self{
        return new PublicException(array_merge($attr,['infoCode' => $infoCode, 'text' => $text, 'httpCode' => $httpCode]));
    }

    /**
     * @param Throwable $exception
     * @param array $attr
     * @return PublicException
     */
    static function fromException( \Throwable $exception, array $attr=[]):self{
        if ($exception instanceof PublicException) {
            return $exception;
        }

        $hintCode = null;
        $defaults = [
            'text' => \get_class($exception).': '.$exception->getMessage().' en '.$exception->getFile().'@'.$exception->getLine(),
            'httpCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'infoCode' => 'exception.'.$exception->getCode()
        ];

        $defaults['data']['type'] = \get_class($exception);
        $defaults['data']['file'] = $exception->getFile();
        $defaults['data']['line'] = $exception->getLine();
        $defaults['data']['trace'] = $exception->getTraceAsString();

        return new PublicException(array_merge($defaults,['hintCode' => $hintCode, 'exception' => $exception], $attr) );
    }

    /** @return null|string  */
    function getText():?string {
        return $this->attr['text'];
    }

    /** @return null|string  */
    function getHintCode():?string {
        return $this->attr['hintCode'];
    }

    /** @return null|string  */
    function getInfoCode():?string {
        return $this->attr['infoCode'];
    }

    /** @return int  */
    function getHttpCode():int {
        return $this->attr['httpCode'];
    }

    /** @return mixed  */
    function getExceptionCode() {
        return parent::getCode();
    }

    /** @return null|array  */
    function getData():?array {
        return $this->attr['data'];
    }
}