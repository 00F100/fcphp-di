<?php

namespace FcPhp\Di\Interfaces
{
    use FcPhp\Di\Interfaces\IInstance;
    
    interface IInstance
    {
        /**
         * Method to construct new instance
         *
         * @param string $namespace Namespace of class
         * @param array $args Args to construct new class
         * @param bool $singlegon Define if this class is singleton (or not)
         * @return void
         */
        public function __construct(string $namespace, array $args, array $setters, bool $singleton);

        /**
         * Method to return namespace of class
         *
         * @return string
         */
        public function getNamespace() :string;

        /**
         * Method to return args to construct class
         *
         * @return array
         */
        public function getArgs() :array;

        /**
         * Method to return if this class is singleton
         *
         * @return bool
         */
        public function getIsSingleton() :bool;

        /**
         * Method to return list of setters to class
         *
         * @return array
         */
        public function getSetters() :array;

        /**
         * Method to configure setters
         *
         * @return FcPhp\Di\Interfaces\IInstance
         */
        public function setSetters(array $setters) :IInstance;
    }
}
