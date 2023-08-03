function play() {
    fetch('/api/play').then((response) => response.json()).then((matches) => {
        updateMatches(matches.games);
        updateScoreTable(matches.scoreTable);
        updatePredictions(matches.probability);
    })
    .catch((error) => {
        console.error('Error fetching match results:', error);
    });
}

function playAll() {
    fetch('/api/play-all').then((response) => response.json()).then((matches) => {
        updateMatches(matches.games);
        updateScoreTable(matches.scoreTable);
        updatePredictions(matches.probability);
    })
    .catch((error) => {
        console.error('Error fetching match results:', error);
    });
}

function updateMatches(games) {
    const matchResultsList = document.querySelector('#matches');
    games.forEach((game) => {
        const homeTeamName = game.home_team.name;
        const awayTeamName = game.away_team.name;
        const homeTeamScore = game.home_team_score;
        const awayTeamScore = game.away_team_score;
        const week = game.week;
        
        // Create a match result string
        const matchResultString = `${homeTeamName} ${homeTeamScore} - ${awayTeamScore} ${awayTeamName} (Week ${week})`;
        
        // Create a new list item and append it to the match results list
        const listItem = document.createElement('li');
        listItem.classList.add('list-group-item');
        listItem.textContent = matchResultString;
        matchResultsList.appendChild(listItem);
    });
}

function updateScoreTable(scoreTable) {
    var $tbody = $('#scoreTable tbody');
    $tbody.empty();

    $.each(scoreTable, function(index, row) {
        var $tr = $('<tr>');
        $tr.append(createCell(row.team.name));
        $tr.append(createCell(row.PTS));
        $tr.append(createCell(row.P));
        $tr.append(createCell(row.W));
        $tr.append(createCell(row.D));
        $tr.append(createCell(row.L));
        $tr.append(createCell(row.GD));
        $tbody.append($tr);
    });
}

function createCell(value) {
    return $('<td>').text(value);
}

function updatePredictions(probability) {
    // Get the predictions container
    var $predictionsDiv = $('#predictions');

    // Clear existing predictions
    $predictionsDiv.empty();

    // Iterate through the probability object and create elements for each team's probability
    $.each(probability, function(teamName, teamProbability) {
        var $p = $('<p>');
        $p.text(teamName + ': ' + teamProbability + '%');
        $predictionsDiv.append($p);
    });
}