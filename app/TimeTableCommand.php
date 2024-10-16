<?php

namespace App;

use DateMalformedStringException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[
    AsCommand(
        name: 'app:timetable',
        description: 'Generate new timetable.',
        hidden: false
    )
]
class TimeTableCommand extends Command
{
    const array STATION_NAMES = [
        'A',
        'B',
    ];

    /**
     * @throws DateMalformedStringException
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $helper = $this->getHelper('question');

        $numberOfCases = $helper->ask(
            $input,
            $output,
            new Question('Please enter the number of cases: ')
        );

        for ($i = 0; $i < $numberOfCases; $i++) {
            $this->generateCase(
                $input,
                $output,
                $helper
            );
        }

        return Command::SUCCESS;
    }

    private function generateCase(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper
    ): void {
        $turnAroundTime = $helper->ask(
            $input,
            $output,
            new Question('Please enter the turnaround time in minutes: ')
        );

        $numberOfTrips = $helper->ask(
            $input,
            $output,
            new Question('Please enter the number of trips for stations separated by space: ')
        );

        $numberOfTripsPerStation = explode(
            ' ',
            $numberOfTrips
        );

        $timetable = [];
        foreach ($numberOfTripsPerStation as $stationIndex => $numberOfTripsForStation) {
            for ($departureIndex = 1; $departureIndex <= $numberOfTripsForStation; $departureIndex++) {
                $stationName = self::STATION_NAMES[$stationIndex];

                $times = $helper->ask(
                    $input,
                    $output,
                    new Question("Please enter the departure and arrival time for departure $departureIndex for station $stationName : ")
                );

                $times = explode(
                    ' ',
                    $times
                );

                $output->writeln(json_encode($times));

                $departure = $times[0];
                $arrival = $times[1];

                $timetable[$stationName][] = [
                    'departure' => date(
                        'H:i',
                        strtotime($departure)
                    ),
                    'arrival' => date(
                        'H:i',
                        strtotime($arrival)
                    ),
                ];
            }
        }

        $result = [];
        foreach ($timetable[self::STATION_NAMES[0]] as $firstTimetableData) {
            foreach ($timetable[self::STATION_NAMES[1]] as $secondTimetableData) {
                $hasTrainInStation = false;
                if ($firstTimetableData['departure'] == $secondTimetableData['departure']) {

                }
            }

            $result[$stationName] = $value;
        }

        $output->writeln(
            implode(
                ' ',
                $result
            )
        );
    }
}