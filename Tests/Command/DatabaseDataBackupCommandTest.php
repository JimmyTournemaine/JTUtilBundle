<?php
namespace JT\UtilBundle\Tests\Command;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use JT\UtilBundle\Command\DatabaseDataBackupCommand;

class DatabaseDataBackupCommandTest extends KernelTestCase
{
	public function testExecute()
	{
		self::bootKernel();
		$application = new Application(self::$kernel);
		$application->add(new DatabaseDataBackupCommand());
		
		$command = $application->find('jt_util:database:backup');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array('command' => $command->getName()));
		$output = $commandTester->getDisplay();
		$this->assertContains("has been generated.", $output);
	}
	
	public function testExecuteWithParameter()
	{
	    self::bootKernel();
		$application = new Application(self::$kernel);
		$application->add(new DatabaseDataBackupCommand());
		
		$command = $application->find('jt_util:database:backup');
		$commandTester = new CommandTester($command);
		$commandTester->execute(array('command' => $command->getName(), 'path' => 'app/backup'));
		$output = $commandTester->getDisplay();
		$this->assertContains("has been generated.", $output);
	}
}

