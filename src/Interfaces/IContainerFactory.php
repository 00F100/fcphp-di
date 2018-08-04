<?php

namespace FcPhp\Di\Interfaces
{
    use FcPhp\Di\Interfaces\IContainer;
    
    interface IContainerFactory
    {
        /**
         * Method to return instance of Container
         *
         * @param mixed $instance to use inside Container
         * @param array $args Args to construct
         * @param array @setters Setters to class
         * @return FcPhp\Di\Interfaces\IContainer
         */
        public function getInstance($instance, array $args, array $setters) :IContainer;
    }
}
