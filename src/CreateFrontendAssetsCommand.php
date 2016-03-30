<?php


namespace JNCampbell\Scaffolder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateFrontendAssetsCommand extends Command
{
    public function configure()
    {
        $this->setName("create-frontend-assets")
             ->setDescription("Generates boilerplate for a common front-end workflow");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

    }
}