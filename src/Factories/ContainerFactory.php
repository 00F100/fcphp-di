<?php

namespace FcPhp\Di\Factories
{
    use FcPhp\Di\Container;
    use FcPhp\Di\Interfaces\IContainerFactory;
    use FcPhp\Di\Interfaces\IContainer;

    class ContainerFactory implements IContainerFactory
    {
        /**
         * Method to return instance of Container
         *
         * @param mixed $instance to use inside Container
         * @param array $args Args to construct
         * @param array @setters Setters to class
         * @return FcPhp\Di\Interfaces\IContainer
         */
        public function getInstance($instance, array $args, array $setters) :IContainer
        {
            return new Container($instance, $args, $setters);
        }
    }
}
