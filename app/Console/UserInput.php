<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserInput
{
    private QuestionHelper $helper;

    public function __construct(
        private readonly InputInterface  $input,
        private readonly OutputInterface $output,
        Command                          $command
    )
    {
        $this->helper = $command->getHelper('question');
    }

    public function ask(string $question): string|int
    {
        return $this->helper
            ->ask(
                $this->input,
                $this->output,
                new Question($question)
            );
    }
}