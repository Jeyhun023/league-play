# Premier League Simulation

"Premier League Predictor (PLP) is a unique web application that simulates football league matches, allowing users to predict match outcomes and follow real-time updates. Utilizing factors such as Team Power Indicator, Home Advantage, Weather Conditions, Injuries, Tactics, and Strategy, PLP offers an engaging and dynamic experience. Users can edit match results, track team standings, and view probability statistics to gauge the likely champions. Built with Laravel, Jquery and Bootstrap.

list of features:

| Feature                                  | Status        |
| ---------------------------------------- | ------------- |
| Random weekly match                      | &#9745;       |
| Simulate each week separately            | &#9745;       |
| Simulate all weeks at once               | &#9745;       |
| Reset all played match                   | &#9745;       |
| Predict champion                         | &#9745;       |
| Different win chance for teams           | &#9745;       |
| edit played match result                 | &#9745;       |

## Getting Started

Clone the project:

```
> git clone https://github.com/Jeyhun023/league-play.git
```

### Prerequisites

for running the project you need the minimum requirement of running laravel 5.8 and there is no other third party packages


### Installing

for installing just do below steps after cloning:

```
> navigate to premier-league-simulation
> composer install
> php -r "file_exists('.env') || copy('.env.example', '.env');"
> create a mysql database and add your database access in .env
> php artisan key:generate
> php artisan migrate --seed
```


## Running the tests

this project includes unit tests for services such as prediction, simulator and fixtureDrawer

```
> vendor/bin/phpunit
```
now your project is ready to serve:

```
> php artisan serve
```


## Live Demo

* [click to see the deployed project](https://league.birimtahan.com/)


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details