<?php
/**
 * LIBPROJECT Librairy - main class
 *
 * Tool
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.YYMMDD
 */

if (!defined('__CLASS_LIBPROJECT__')) {

    define('__CLASS_LIBPROJECT__', '0.YYMMDD');

    class LIBODA {
        public $var = "var1";
        
        protected $_encoding         = '';          // charset encoding
        
        /**
         * class _functionProtected
         *
         * @access protected
         */
        protected function _functionProtected(){
            $this->$var = "var3";
        }
        
        /**
         * class constructor
         *
         * @param  String   $encoding    charset encoding; default is UTF-8
         * @return LIBODA $this
         */
        public function __construct($encoding='UTF-8'){
            // save the parameters
            $this->_encoding     = $encoding;

            return $this;
        }

        /**
         * Destructor
         *
         * @access public
         * @return null
         */
        public function __destruct(){

        }
        
        /**
         * class getVar
         *
         * @access public
         * @return $this->_encoding
         */
        public function getVar(){
            return $this->_encoding;
        }
    }
}