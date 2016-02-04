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
    /**
     * Configure the command options
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('new')
             ->setDescription('Generate boilerplate for a new php package')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the package')
             ->addOption('playground', null, InputOption::VALUE_NONE, 'Add a public folder with an index.php file');
    }

    /**
     * Execute the command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        $directory = getcwd() . '/' . $input->getArgument('name');

        if (is_dir($directory)) {
            $output->writeln("<error>A directory with that name already exists in this location!<error>");
            exit(1);
        }

        mkdir($directory);
        mkdir($directory . '/src/');
        mkdir($directory . '/tests/');

        $this->createComposerFile($directory);
        $this->createPHPUnitXMLFile($directory);

        if ($input->getOption('playground')) {
            mkdir($directory . '/public/');
            fopen($directory . '/public/index.php', 'w');
        }
        $output->writeln("<info>Package boilerplate generated!<info>", false, 4);
    }

    /**
     * Create the composer.json file
     *
     * @param $directory
     */
    private function createComposerFile($directory)
    {
        $composer = fopen($directory . '/composer.json', 'a');
        fwrite($composer, $this->createComposerFileBoilerplate());
    }

    /**
     * Create a composer.json template
     *
     * @return string
     */
    private function createComposerFileBoilerplate()
    {
        $template = [
            'name'        => '',
            'description' => '',
            'type'        => '',
            'license'     => '',
            'authors'     => [
                [
                    'name'  => '',
                    'email' => ''
                ]
            ],
            'require-dev' => [
                'phpunit/phpunit' => '5.*',
                'mockery/mockery' => '0.*'
            ],
            'autoload'    => [
                'psr-4' => [
                    "" => "src/"
                ]
            ]
        ];

        return json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Create the phpunit.xml file
     *
     * @param $directory
     */
    private function createPHPUnitXMLFile($directory)
    {
        $phpunitXMLFile = fopen($directory . '/phpunit.xml', 'a');
        fwrite($phpunitXMLFile, $this->createPHPUnitXMLBoilerplate());
    }

    /**
     * Create a phpunit.xml template
     *
     * @return bool
     */
    private function createPHPUnitXMLBoilerplate()
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $doc->preserveWhitespace = false;

        $newNode = $doc->createElement("phpunit");
        $phpunit = $doc->appendChild($newNode);
        $phpunit->setAttribute('bootstrap', './vendor/autoload.php');
        $phpunit->setAttribute('backupGlobals', 'false');
        $phpunit->setAttribute('colors', 'true');
        $phpunit->setAttribute('convertErrorsToExceptions', 'true');
        $phpunit->setAttribute('convertNoticesToExceptions', 'true');
        $phpunit->setAttribute('convertWarningsToExceptions', 'true');
        $phpunit->setAttribute('processIsolation', 'false');
        $phpunit->setAttribute('stopOnFailure', 'false');
        $phpunit->setAttribute('syntaxCheck', 'false');

        $newNode = $doc->createElement('testsuites');
        $testsuites = $phpunit->appendChild($newNode);

        $newNode = $doc->createElement('testsuite');
        $testsuite = $testsuites->appendChild($newNode);
        $testsuite->setAttribute('name', 'Package Test Suite');

        $newNode = $doc->createElement('directory', './tests/');
        $testsuite->appendChild($newNode);

        $doc = $doc->saveXML();

        $this->prettifyXML($doc);

        return $doc;
    }

    /**
     * Returns a formatted xml string
     *
     * @param $xmlString
     */
    private function prettifyXML(&$xmlString)
    {

        $xmlString = $this->convertTwoSpaceIndentationToFourSpaces($xmlString);

        $arrOfNodes = preg_split("/>/", $xmlString);
        $arrOfNodes[1] = $this->formatMultipleAttributes($arrOfNodes[1]);
        $xmlString = implode(">", $arrOfNodes);
    }

    /**
     * Converts two space indentation to four space indentation
     *
     * @param $xmlString
     *
     * @return string
     */
    private function convertTwoSpaceIndentationToFourSpaces($xmlString)
    {
        return preg_replace("/  /", "    ", $xmlString);
    }

    /**
     * Formats a multi-attribute xml node string
     *
     * @param $nodeString
     *
     * @return string
     */
    private function formatMultipleAttributes($nodeString)
    {
        $charsUntilFirstAttribute = $this->calcNumberOfCharsBetweenStringStartAndFirstWhitespace($nodeString);

        // We replace every whitespace that's preceded by a quotation mark
        // with a newline and the calculated indentation
        $pattern = "/(?<=\")\s/";
        $substitute = "\n" . str_repeat(" ", $charsUntilFirstAttribute);

        return preg_replace($pattern, $substitute, $nodeString);
    }

    /**
     * Calculates how many characters are between the start of a string and the first space
     *
     * @param $nodeString
     *
     * @return int
     */
    private function calcNumberOfCharsBetweenStringStartAndFirstWhitespace($nodeString)
    {
        $i = 0;
        while ($nodeString[$i] != " ") {
            $i ++;
        }

        return $i;
    }
}