<?php
namespace JT\UtilBundle\Tests\Command;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use JT\UtilBundle\Command\NewLineAtEOFCommand;

class NewLineAtEOFCommandTest extends KernelTestCase
{
	public function testExecute()
	{
		self::bootKernel();
		$application = new Application(self::$kernel);
		$application->add(new NewLineAtEOFCommand());
		
		$command = $application->find('jt_util:nl-at-eof');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array('command' => $command->getName()));
		$output = $commandTester->getDisplay();
		$this->assertContains('Modified files :', $output);
	}
}

