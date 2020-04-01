<?php
namespace Pressmind\Error;

use Pressmind\Log\Writer;

class Handler
{
    static function error($errno, $errstr, $errfile, $errline, $errcontext) {
        $text = $errno . "\n" .
        $errstr . "\n" .
        $errfile . "\n" .
        $errline . "\n" .
        print_r($errcontext, true);
        Writer::write($text, Writer::OUTPUT_FILE, 'errors.log');
    }

    static function exception($ex) {
        Writer::write(print_r($ex, true), Writer::OUTPUT_FILE, 'errors.log');
    }

    static function shutdown()
{
        Writer::write('Shutdown Handler', Writer::OUTPUT_FILE, 'errors.log');
    }

    private static function sendMail()
{

    }
}
