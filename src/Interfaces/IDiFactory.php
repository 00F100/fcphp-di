<?php

namespace FcPhp\Di\Interfaces
{
    interface IDiFactory
    {
        /**
         * Method to return new instance of Di
         *
         * @param FcPhp\Di\Interfaces\IContainerFactory $containerFactory Instance of Container Factory
         * @param FcPhp\Di\Interfaces\IInstanceFactory $instanceFactory Instance of Instance (!?!?!) Factory
         * @param bool $register If register class inside log
         * @return FcPhp\Di\Interfaces\IDi
         */
        public function getInstance(IContainerFactory $containerFactory, IInstanceFactory $instanceFactory, bool $register = false) :IDi;
    }
}
