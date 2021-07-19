# Ahora

Time interval calculator.

Allows interval to be summed up and provides easy access the number of seconds/minutes/hours/days included in the interval.

## Installation

Using [Composer](https://getcomposer.org/):

    composer require savvywombat/ahora

## Usage

### Creation

You can create a new interval as a new instance, optionally passing in an [ISO8601 interval specification](https://en.wikipedia.org/wiki/ISO_8601#Durations):

    use SavvyWombat\Ahora\Interval;
    
    $interval = new Interval();
    echo $interval->seconds; // outputs 0

    $interval = new Interval("PT100D"); // 100 days
    echo $interval->seconds; // outputs 8640000


Alternatively, you can create an interval from a PHP [DateInterval](http://php.net/manual/en/class.dateinterval.php),
or from an interval specification with the following static methods:
    
    use SavvyWombat\Ahora\Interval;
    
    $date1 = new \DateTime("now");
    $date2 = new \DateTime("2018-01-01");
    
    $interval = Interval::createFromDateInterval($date1->diff($date2));
    
    // or
    
    $interval = Interval::createFromIntervalSpec("P28DT6H42M12S");
    
### Interval math

Once you have an interval, you can easily add a number of seconds, minutes, hours, or days:

    $interval = new Interval();
    
    $interval->addSeconds(60); // equivalent to $interval->addMinutes(1);
    $interval->addMinutes(60); // equivalent to $interval->addHours(1);
    $interval->addHours(24); // equivalent to $interval->addDays(1);
    $interval->addDays(7);

#### Adding intervals

It is also possible to add intervals together:

    $firstInterval = new Interval();
    $firstInterval->addSeconds(45);
    
    $secondInterval = new Interval();
    $secondInterval->addSeconds(70);
    
    $firstInterval->addInterval($secondInterval);
    
    echo $firstInterval->seconds; // outputs 55
    echo $firstInterval->minutes; // outputs 1
    
    echo $firstInterval->realSeconds; // outputs 115

#### Subtracting intervals

Similarly, you can subtract an interval from another:

    $firstInterval = new Interval();
    $firstInterval->addSeconds(45);

    $secondInterval = new Interval();
    $secondInterval->addSeconds(200);
    
    $firstInterval->subInterval($secondInterval);
    
    echo $firstInterval->seconds; // outputs -35
    echo $firstInterval->minutes; // outputs -2
    
    echo $firstInterval->realSeconds; // outputs -155

### Units and factors

It is possible to inject additional units into the interval (such as weeks).

You can even modify or completely replace the units that the interval uses - 
with the limitation that one unit must be a multiple of `seconds`

    $interval = new Interval();
    $interval->addDays(10);
    
    echo $interval->days; // outputs 10
    
    $interval->setFactor('weeks', [7, 'days']);
    
    echo $interval->realHours; // outputs 240
    echo $interval->days; // outputs 3
    echo $interval->weeks; // outputs 1

    
    $interval->setFactor('days', [8, 'hours']);
    $interval->setFactor('weeks', [5, 'days']);
    
    echo $interval->realHours; // outputs 240
    echo $interval->days; // outputs 2
    echo $interval->weeks; // outputs 4

Obviously, changing these multipliers can have some drastic effects on the interval's outputs.

    $interval = new Interval();
    
    $oldFactors = $interval->getFactors(); // [ 
                                           //   'minutes' => [60, 'seconds'], 
                                           //   'hours' => [60, 'minutes'],
                                           //   'days' => [24, 'hours'],
                                           // ]

    $interval->setFactors([
        'microts' => [1, 'seconds'], // one unit must be related to seconds
        'arns' => [3600, 'microts'],
        'days' => [24, 'arns'],
        'cycles' => [360, 'days'],
    ]);

    $interval->addDays(400);
    
    echo $interval->cycles; // outputs 1
    echo $interval->days; // outputs 40
    
## Support

If you are having general issues with this repository, please contact us via
the [SavvyWombat](https://savvywombat.com.au/contact) website.

Please report issues using the [GitHub issue tracker](https://github.com/SavvyWombat/ahora/issues). You are also welcome to fork the repository and submit a pull request.

If you're using this repository, we'd love to hear your thoughts. Thanks!

## Licence

Ahora is licensed under [The MIT License (MIT)](https://github.com/SavvyWombat/ahora/blob/master/LICENSE).
