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
            <div class="col-md-4">
                <h4>Match Results</h4>
                <ul id="matches" class="list-group">
                    @foreach($data->games() as $weeks)
                        @foreach($weeks as $game)
                            <li class="list-group-item">
                                {{ $game->homeTeam->name }} 
                                {{ $game->home_team_score }}
                                -
                                {{ $game->away_team_score }}
                                {{ $game->awayTeam->name }}
                                (Week {{ $game->week }})
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>

            <!-- Predictions Section -->
            <div class="col-md-4">
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('front/js/app.js')}}"></script>
</body>
</html>