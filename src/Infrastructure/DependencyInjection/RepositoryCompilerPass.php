<?php

namespace Infrastructure\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RepositoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('repository.interface') as $id => $tags) {
            $interface = $id;

            $implementation = str_replace(
                ['Domain\\', 'RepositoryInterface'],
                ['Infrastructure\\Persistence\\Doctrine\\', 'Repository'],
                $interface
            );

//            $implementation = str_replace('Domain\\', 'Infrastructure\\Persistence\\Doctrine\\', $interface);
//
//            $implementation = str_replace('RepositoryInterface', 'Repository', $implementation);

            if ($container->has($implementation)) {
                $container
                    ->setAlias($interface, $implementation)
                    ->setPublic(true);
            }
        }
    }
}
