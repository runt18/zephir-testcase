<?php

namespace ZephirTestCase;

use Zephir\Commands\CommandBuild;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\Commands\CommandFullClean;
use Zephir\BaseBackend;

class ZephirExtensionBuilder
{
    /**
     * @var ZephirClassInfo
     */
    private $zephirClassInfo;
    /**
     * @var FileWorker
     */
    private $fileWorker;
    /**
     * @var CommandBuild
     */
    private $commandBuild;
    /**
     * @var CommandFullClean
     */
    private $commandFullClean;

    /**
     * @param ZephirClassInfo $zephirClassInfo
     * @param FileWorker $fileWorker
     * @param CommandBuild $commandBuild
     * @param CommandFullClean $commandFullClean
     */
    public function __construct(
        ZephirClassInfo $zephirClassInfo,
        FileWorker $fileWorker,
        CommandBuild $commandBuild,
        CommandFullClean $commandFullClean
    ) {
        $this->zephirClassInfo = $zephirClassInfo;
        $this->fileWorker = $fileWorker;
        $this->commandBuild = $commandBuild;
        $this->commandFullClean = $commandFullClean;
    }

    /**
     * @param string $zephir
     * @param boolean $silent
     * @throws \Exception
     * @return string
     */
    public function build($zephir, $silent)
    {
        $dto = $this->zephirClassInfo->getZephirCodeInfo($zephir);
        $this->fileWorker->writeZephirFile($dto, $zephir);

        if (!defined('ZEPHIRPATH')) {
            define('ZEPHIRPATH', realpath(__DIR__.'/../../vendor/phalcon/zephir').'/');
        }

        try {
            $config = new Config();
            $config->set('namespace', $dto->getExtensionName());
            $config->set('silent', $silent);

            if (is_dir('ext')) {
                $this->commandFullClean->execute($config, new ZephirLogger($config));
            }
            $this->commandBuild->execute($config, new ZephirLogger($config));
        } catch (Exception $e) {
            $this->fileWorker->rmdirRecursive($dto->getBaseDir());
            throw new \Exception(sprintf('Error on %s', $e->getMessage()));
        }

        $this->fileWorker->rmdirRecursive($dto->getBaseDir());

        return ini_get('extension_dir') . '/' . $dto->getExtensionName() .'.so';
    }
}