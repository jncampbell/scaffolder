<?php

namespace JNCampbell\Scaffolder;

use DOMDocument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePackageCommand extends Command
{
	private $tidyParseOptions = [
		'indent'            => TRUE,
		'indent-attributes' => TRUE,
		'input-xml'         => TRUE,
		'output-xml'        => TRUE,
		'add-xml-space'     => FALSE,
		'indent-spaces'     => 4
	];

	public function configure()
	{
		$this->setName('new')
			 ->setDescription('Generate boilerplate for a new php package')
			 ->addArgument('name', InputArgument::REQUIRED, 'The name of the package')
			 ->addOption('playground', null, InputOption::VALUE_NONE, 'Add a public folder with an index.php file');
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$directory = getcwd() . '/' . $input->getArgument('name');

		if (is_dir($directory)) {
			$output->writeln("<error>A package with that name already exists in this location!<error>");
			exit(1);
		}

		mkdir($directory);
		mkdir($directory . '/src/');
		mkdir($directory . '/tests/');

		$this->createComposerFile($directory);
		$this->createPHPUnitXMLFile($directory);

		if ($input->getOption('playground')) {
			mkdir($directory.'/public/');
			fopen($directory.'/public/index.php', 'w');
		}

		$output->writeln('<info>Package boilerplate generated!<info>');
	}

	private function createComposerFile($directory) {
		$composer = fopen($directory.'/composer.json', 'a');
		fwrite($composer, $this->createComposerTemplate());
	}

	private function createPHPUnitXMLFile($directory) 
	{		
		$phpunitXMLFile = fopen($directory.'/phpunit.xml', 'a');
		fwrite($phpunitXMLFile, $this->createPHPUnitXMLTemplate());
	}

	private function createComposerTemplate()
	{
		$template = [
			'name' => '',
			'description' => '',
			'type' => '',
			'license' => '',
			'authors' =>  [
				[
					'name' => '',
					'email' => ''
				]
			],
			'require-dev' => [
				'phpunit/phpunit' => '5.*',
				'mockery/mockery' => '0.*'
			],
		];
		return json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}

	private function createPHPUnitXMLTemplate()
	{
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;
		$doc->preserveWhitespace = false;

		$phpunit = $doc->appendChild($doc->createElement( 'phpunit' ));
		$phpunit->setAttribute('bootstrap', '');
		$phpunit->setAttribute('backupGlobals', 'false');
		$phpunit->setAttribute('colors', 'true');
		$phpunit->setAttribute('convertErrorsToExceptions', 'true');
		$phpunit->setAttribute('convertNoticesToExceptions', 'true');
		$phpunit->setAttribute('convertWarningsToExceptions', 'true');
		$phpunit->setAttribute('processIsolation', 'false');
		$phpunit->setAttribute('stopOnFailure', 'false');
		$phpunit->setAttribute('syntaxCheck', 'false');
		$sxe = simplexml_import_dom( $doc );

		$testsuites = $sxe->addchild('testsuites');
		$testsuite = $testsuites->addChild('testsuite');
		$testsuite->addAttribute('name', 'Package Test Suite');

		$directory = $testsuite->addChild('directory', './tests/');

		return tidy_parse_string($doc->saveXML(), $this->tidyParseOptions);
	}
}