<?php
namespace JT\UtilBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class NewLineAtEOFCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('jt_util:nl-at-eof')
            ->setDescription('Check that files are ending by a \n')
            ->setHelp('This command allows you to check that all your sources files are ending by a newline character')
            ->addArgument('path', InputArgument::OPTIONAL, 'The path to check in.')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process($this->getCommandLine($input));
        $process->run();
        
        if($process->isSuccessful() == false) {
        	throw new ProcessFailedException($process);
        }
        
        $output->write($process->getOutput());
    }
    
    private function getCommandLine(InputInterface $input) {
    	$arg = $input->getArgument('path') ?: '`pwd`';
    	return 'DIR='.$arg.'
    		i=0
    		find DIR -type f -name "*.php" | while read FILE;
    		do
    			CAR=`sed -n \'$p\' $FILE`
    			if [ -z "$CAR" ]; then
    				continue
    			else
    				let i++
    			fi
    		done
    		echo "Modified files : $i"
	    	exit 0';
    }
}

