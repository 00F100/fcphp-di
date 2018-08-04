<?php

namespace FcPhp\Di\Interfaces
{
    use FcPhp\Di\Interfaces\IDiFactory;
    use FcPhp\Di\Interfaces\IContainer;
    use FcPhp\Di\Interfaces\IContainerFactory;
    use FcPhp\Di\Interfaces\IInstanceFactory;

    interface IDi
    {
        /**
         * Return new instance of Di
         *
         * @param FcPhp\Di\Interfaces\IDiFactory $diFactory Instance of Di Factory
         * @param FcPhp\Di\Interfaces\IContainerFactory $containerFactory Instance of Container Factory
         * @param FcPhp\Di\Interfaces\IInstanceFactory $instanceFactory Instance of Instance (??!) Factory
         * @return FcPHP\Di\Interfaces\IDi
         */
        public static function getInstance(IDiFactory $diFactory, IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false) :IDi;

        /**
         * Method to contruct instance of Di
         *
         * @param bool $register Define if register containers to log
         */
        public function __construct(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, $register = false);

        /**
         * Method to configure new instance
         *
         * @param string $id Identify instance
         * @param string $namespace Namespace of class
         * @param array $args Args to construct class
         * @param array $setters Setters to class
         * @param bool $singleton Define this class has singleton (or not
         * @return FcPhp\Di\Interfaces\IDi
         */
        public function set(string $id, string $namespace, array $args = [], array $setters = [], bool $singleton = true) :IDi;

        /**
         * Method to configure new instance non singleton
         *
         * @param string $id Identify instance
         * @param string $namespace Namespace of class
         * @param array $args Args to construct class
         * @param array $setters Setters to class
         * @return FcPhp\Di\Interfaces\IDi
         */
        public function setNonSingleton(string $id, string $namespace, array $args = [], array $setters = []) :IDi;

        /**
         * Method to configure setters to class
         *
         * @param string $id Identify instance
         * @param array $setters Setters to class
         * @return FcPhp\Di\Interfaces\IDi
         */
        public function setter(string $id, array $setters) :IDi;

        /**
         * Method to return Container to manipulate instance
         *
         * @param string $id Identify instance
         * @param array $args Args to construct class
         * @param array $setters Setters to after construct class
         * @return FcPhp\Di\Interfaces\IContainer
         */
        public function get(string $id, array $args = [], array $setters = []) :IContainer;

        /**
         * Method to return new instance non singleton of class
         *
         * @param string $id Identify of instance
         * @param array $args Args to construct class
         * @param array $setters Setters to class
         * @return FcPhp\Di\Interfaces\IContainer
         */
        public function getNonSingleton(string $id, array $args = [], array $setters = []) :IContainer;

        /**
         * Method to verify if instance exists
         *
         * @param string $id Identify of instance
         * @return bool
         */
        public function has(string $id) :bool;

        /**
         * Method to make new instance of class
         *
         * @param string $id Identify of class
         * @param array $args Args to contruct class
         * @param array $setters Setters to class
         * @return mixed
         */
        public function make(string $id, array $args = [], array $setters = []);

        /**
         * Method to execute clousure on event
         *
         * @param string $eventName Event Name, ex: beforeSet
         * @param object $callback Function to execute
         * @return void
         */
        public function event($eventName, object $callback = null) :void;
        
        /**
         * Method to event before execute set()
         *
         * @param string $id Identify of instance
         * @param string $namespace Namespace of class
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @param bool $singleton If this class is singleton
         * @return void
         */
        public function beforeSet(string $id, string $namespace, array $args, array $setters, bool $singleton) :void;

        /**
         * Method to event after execute set()
         *
         * @param string $id Identify of instance
         * @param string $namespace Namespace of class
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @param bool $singleton If this class is singleton
         * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
         * @return void
         */
        public function afterSet(string $id, string $namespace, array $args, array $setters, bool $singleton, ?IInstance $instance);

        /**
         * Method to event before execute get()
         *
         * @param string $id Identify of instance
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @return void
         */
        public function beforeGet(string $id, array $args, array $setters) :void;

        /**
         * Method to event after execute get()
         *
         * @param string $id Identify of instance
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
         * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
         * @return void
         */
        public function afterGet(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container) :void;

        /**
         * Method to event before execute make()
         *
         * @param string $id Identify of instance
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @return void
         */
        public function beforeMake(string $id, array $args, array $setters) :void;

        /**
         * Method to event after execute make()
         *
         * @param string $id Identify of instance
         * @param array $args Args to construct instance
         * @param array $setters Setters to class
         * @param FcPhp\Di\Interfaces\IInstance $instance Instance config of class
         * @param FcPhp\Di\Interfaces\IContainer $container Instance of Container
         * @param mixed $class Instance of class
         * @return void
         */
        public function afterMake(string $id, array $args, array $setters, ?IInstance $instance, ?IContainer $container, $class) :void;
    }
}
