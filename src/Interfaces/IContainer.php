<?php

namespace FcPhp\Di\Interfaces
{
    use FcPhp\Di\Interfaces\IInstance;
    
    interface IContainer
    {
        /**
         * Method to construct instance of Container
         *
         * @param FcPhp\Di\Interfaces\IInstance $instance Instance of Container
         * @param array $args Args to construct
         * @param array $setters Setters methods
         * @return void
         */
        public function __construct(IInstance $instance, array $args = [], array $setters = []);

        /**
         * Method to return instance of class inside Container
         *
         * @return mixed
         */
        public function getClass();
    }
}
