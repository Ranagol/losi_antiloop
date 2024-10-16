<?php

namespace App\Commands;

use App\Console\UserInput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[
    AsCommand(
        name: 'app:multiple-timetables',
        description: 'Generate multiple new timetables.',
        hidden: false
    )
]
class MultipleTimeTablesCommand extends Command
{
    public const string NAME = 'app:multiple-timetable';

    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    ): int
    {
        $userInput = new UserInput(
            $input,
            $output,
            $this
        );

        $numberOfCases = $userInput->ask('Please enter the number of cases: ');

        $timeTableCommand = $this->getApplication()
            ->find(TimeTableCommand::NAME);

        for ($i = 0; $i < $numberOfCases; $i++) {
            try {
                $timeTableCommand->run($input, $output);
            } catch (ExceptionInterface $e) {
                $output->writeln('<error>' . $e->getMessage() . '</error>');

                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }
}