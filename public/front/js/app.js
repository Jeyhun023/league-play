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

        // Create a new list item and append it to the match results list
        const listItem = document.createElement('li');
        listItem.classList.add('result', 'list-group-item');
        listItem.setAttribute('data-game-id', game.id);
        matchResultsList.appendChild(listItem);

        const homeNameSpan = document.createElement('span');
        homeNameSpan.classList.add('home-name');
        homeNameSpan.textContent = ' ' + homeTeamName + ' ';
        listItem.appendChild(homeNameSpan);

        const homeScoreSpan = document.createElement('span');
        homeScoreSpan.classList.add('home-score');
        homeScoreSpan.textContent = ' ' + homeTeamScore + ' ';
        listItem.appendChild(homeScoreSpan);

        const separator = document.createTextNode(' - ');
        listItem.appendChild(separator);

        const awayScoreSpan = document.createElement('span');
        awayScoreSpan.classList.add('away-score');
        awayScoreSpan.textContent = ' ' + awayTeamScore + ' ';
        listItem.appendChild(awayScoreSpan);

        const awayNameSpan = document.createElement('span');
        awayNameSpan.classList.add('away-name');
        awayNameSpan.textContent = ' ' + awayTeamName + ' ';
        listItem.appendChild(awayNameSpan);

        // Create an edit button and append it to the list item
        const editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.setAttribute('onClick', 'editGame(this);');
        editButton.classList.add('ml-2', 'btn', 'btn-info');
        listItem.appendChild(editButton);
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
        $p.text(teamName + ' : ' + teamProbability + '%');
        $predictionsDiv.append($p);
    });
}

function editGame(game) {
    var matchResultDiv = $(game).parent();
    var homeScore = parseInt(matchResultDiv.find('.home-score').text());
    var awayScore = parseInt(matchResultDiv.find('.away-score').text());
    matchResultDiv.find('.home-score').replaceWith('<input type="number" class="col-2 home-score-input" min="0" value="' + homeScore + '" />');
    matchResultDiv.find('.away-score').replaceWith('<input type="number" class="col-2 away-score-input" min="0" value="' + awayScore + '" />');

    $(game).replaceWith('<button onclick="submitGame(this)" class="btn btn-success">Submit</button>');
} 

function submitGame(game) {
    var matchResultDiv = $(game).parent();
    // Get the new scores
    var homeScore = matchResultDiv.find('.home-score-input').val();
    var awayScore = matchResultDiv.find('.away-score-input').val();
    var gameId = matchResultDiv.data('game-id');
    
    $.ajax({
        url: '/api/game/' + gameId,
        method: 'PUT',
        data: { homeScore: homeScore, awayScore: awayScore },
        success: function(response) {
            matchResultDiv.find('.home-score-input').replaceWith('<span class="home-score">' + homeScore + '</span>');
            matchResultDiv.find('.away-score-input').replaceWith('<span class="away-score">' + awayScore + '</span>');
            $(game).replaceWith('<button onclick="editGame(this)" class="ml-2 btn btn-info">Edit</button>');
            updateScoreTable(response.scoreTable);
            updatePredictions(response.probability);
        }
    });
};