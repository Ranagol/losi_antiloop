<?php

namespace App\Commands;

use App\Console\UserInput;
use App\Enums\StationEnum;
use DateMalformedStringException;
use DateTimeImmutable;
use InvalidArgumentException;
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

        for ($i = 0; $i < $numberOfCases; $i++) {
            $this->generateCase(
                $userInput,
                $output
            );
        }

        return Command::SUCCESS;
    }

    private function generateCase(
        UserInput $userInput,
        OutputInterface $output
    ): void {
        $turnAroundTime = $userInput->ask('Please enter the turnaround time in minutes: ');

        $numberOfTrips = $userInput->ask('Please enter the number of trips for stations separated by space: ');

        $numberOfTripsPerStation = explode(
            ' ',
            $numberOfTrips
        );

        $departures = [
            StationEnum::A->getName() => [],
            StationEnum::B->getName() => [],
        ];
        $trainsInStations = [
            StationEnum::A->getName() => [],
            StationEnum::B->getName() => [],
        ];
        foreach ($numberOfTripsPerStation as $stationIndex => $numberOfTripsForStation) {
            for ($departureIndex = 1; $departureIndex <= $numberOfTripsForStation; $departureIndex++) {
                $station = StationEnum::from($stationIndex);
                $stationName = $station->getName();

                $times = $userInput->ask("Please enter the departure and arrival time for departure $departureIndex for station $stationName: ");

                $times = explode(
                    ' ',
                    $times
                );

                $departure = DatetimeImmutable::createFromFormat(
                    'H:i',
                    $times[0]
                );

                $arrival = DateTimeImmutable::createFromFormat(
                    'H:i',
                    $times[1]
                );

                $departures[$stationName][] = $departure;

                $canDepart = $arrival->modify("$turnAroundTime minutes");

                $trainsInStations[$station->getOppositeStation()->getName()][] = $canDepart;
            }
        }

        ksort($departures[StationEnum::A->getName()]);
        ksort($departures[StationEnum::B->getName()]);
        ksort($trainsInStations[StationEnum::A->getName()]);
        ksort($trainsInStations[StationEnum::B->getName()]);

        $result = [
            StationEnum::A->getName() => 0,
            StationEnum::B->getName() => 0,
        ];
        foreach (StationEnum::cases() as $stationEnum) {
            foreach ($departures[$stationEnum->getName()] as $departureTime) {
                foreach ($trainsInStations[$stationEnum->getName()] as $trainInStationIndex => $canDepartTime) {
                    if ($canDepartTime <= $departureTime) {
                        unset($trainsInStations[$stationEnum->getName()][$trainInStationIndex]);

                        break 2;
                    }
                }

                $result[$stationEnum->getName()]++;
            }
        }

        $output->writeln(
            implode(
                ' ',
                $result
            )
        );
    }

    private function getOppositeStationName(string $stationName): string
    {
        if (empty($stationName) === true) {
            throw new InvalidArgumentException('Station name cannot be empty.');
        }

        if ($stationName === self::STATION_NAMES[0]) {
            return self::STATION_NAMES[1];
        }

        return self::STATION_NAMES[0];
    }

    private function getTurnaroundTime(): int
    {

    }

    private function askQuestion(string $question)
    {

    }
}