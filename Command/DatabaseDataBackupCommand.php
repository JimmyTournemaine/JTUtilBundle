<?php
namespace JT\UtilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseDataBackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jt_util:database:backup')
            ->setDescription('Generate a database data backup.')
            ->setHelp('This command allows you to generate a SQL file as a backup for your database.')
            ->addArgument('path', InputArgument::OPTIONAL, "The generated file will be put in this directory or will have this filepath depending of the given argument.", "./");
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$dir = rtrim($input->getArgument('path'), '/');
    	if(file_exists($dir) === false) {
    		$output->writeln("An error occured : $dir doest not exists.");
    	}
    	
    	$progress = new ProgressBar($output, 2);
    	$progress->start();
    	$progress->setMessage("Prepare the query");

    	$container = $this->getContainer();
    	if(is_dir($dir)){
    		$backupFile = $dir.'/'.$container->getParameter('database_name').'_'.date("Y-m-d-H-i-s").'.sql';
    	} else {
    		$backupFile = $dir;
    	}
        $dbhost = $container->getParameter('database_host');
        $dbuser = $container->getParameter('database_user');
        $dbpass = $container->getParameter('database_password');
        
        $progress->advance();
    	$progress->setMessage("Running the query");
        
        $process = new Process("mysqldump --opt -h $dbhost -u $dbuser -p$dbpass > $backupFile");
        $process->run();
        
        $progress->finish();
        
        if($process->isSuccessful() === false) {
        	throw new ProcessFailedException($process);
        }
        
        $output->writeln("\n$backupFile has been generated.");
    }
}

