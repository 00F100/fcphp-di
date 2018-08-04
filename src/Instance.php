<?php

namespace FcPhp\Di
{
    use FcPhp\Di\Interfaces\IInstance;

    class Instance implements IInstance
    {
        /**
         * @var string $namespace
         */
        private $namespace;

        /**
         * @var array $args
         */
        private $args = [];

        /**
         * @var bool $singleton
         */
        private $singleton = true;

        /**
         * @var array $setters
         */
        private $setters = [];

        /**
         * Method to construct new instance
         *
         * @param string $namespace Namespace of class
         * @param array $args Args to construct new class
         * @param bool $singlegon Define if this class is singleton (or not)
         * @return void
         */
        public function __construct(string $namespace, array $args, array $setters, bool $singleton)
        {
            $this->namespace = $namespace;
            $this->args = $args;
            $this->singleton = $singleton;
            $this->setters = $setters;
        }

        /**
         * Method to return namespace of class
         *
         * @return string
         */
        public function getNamespace() :string
        {
            return $this->namespace;
        }

        /**
         * Method to return args to construct class
         *
         * @return array
         */
        public function getArgs() :array
        {
            return $this->args;
        }

        /**
         * Method to return if this class is singleton
         *
         * @return bool
         */
        public function getIsSingleton() :bool
        {
            return $this->singleton;
        }

        /**
         * Method to return list of setters to class
         *
         * @return array
         */
        public function getSetters() :array
        {
            return $this->setters;
        }

        /**
         * Method to configure setters
         *
         * @return FcPhp\Di\Interfaces\IInstance
         */
        public function setSetters(array $setters) :IInstance
        {
            $this->setters = $setters;
            return $this;
        }
    }
}
