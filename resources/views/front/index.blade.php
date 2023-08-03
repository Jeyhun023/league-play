<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <title>Premier Soccer League</title>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Score Table -->
            <div class="col-md-4">
                <h4>Score Table</h4>
                <table id="scoreTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Team</th>
                            <th>PTS</th>
                            <th>P</th>
                            <th>W</th>
                            <th>D</th>
                            <th>L</th>
                            <th>GD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->scoreTable() as $row)
                            <tr>
                                <td>{{ $row['team']->name }}</td>
                                <td>{{ $row['PTS'] }}</td>
                                <td>{{ $row['P'] }}</td>
                                <td>{{ $row['W'] }}</td>
                                <td>{{ $row['D'] }}</td>
                                <td>{{ $row['L'] }}</td>
                                <td>{{ $row['GD'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button onclick="playAll()" class="btn btn-success">Play All</button>
                        <button onclick="play()" class="btn btn-primary">Play</button>
                        <a href="{{ route('front::home.reset') }}" class="btn btn-danger">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Match Results -->
            <div class="col-md-5">
                <h4>Match Results</h4>
                <ul id="matches" class="list-group">
                    @foreach($data->games() as $game)
                        <li class="result list-group-item" data-game-id="{{ $game->id }}">
                            <span class="home-name">
                                {{ $game->homeTeam->name }} 
                            </span>
                            <span class="home-score">
                                {{ $game->home_team_score }}
                            </span>
                            -
                            <span class="away-score">
                                {{ $game->away_team_score }}
                            </span>
                            <span class="away-name">
                                {{ $game->awayTeam->name }}
                            </span>
                            (Week {{ $game->week }})

                            <button onclick=editGame(this) class="ml-2 btn btn-info">Edit</button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Predictions Section -->
            <div class="col-md-3">
                <h4>Predictions</h4>
                <div id="predictions">
                    @foreach($data->probabilities() as $key => $value)
                        <p>
                            {{ $key }} 
                            :
                            {{ $value }}%
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('front/js/app.js')}}"></script>
</body>
</html>