<?php
namespace Cecil\Installer\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Cecil\Installer\Composer\Installer\Installer;

class CecilPlugin implements PluginInterface
{
    /**
     * Apply plugin modifications to composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}
