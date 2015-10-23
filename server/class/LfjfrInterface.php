<?php
namespace Lfjfr;

require '../header.php';
require '../vendor/autoload.php';
require '../include/config.php';

use \stdClass, \Oda\OdaLibInterface;

/**
 * Project class
 *
 * Tool
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.150221
 */
class LfjfrInterface extends OdaLibInterface {
    /**
     * sayHello
     * @return string
     */
    static function sayHello() {
        try {
            return "hello";
        } catch (Exception $ex) {
            return null;
        }
    }
}