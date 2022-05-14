/* Global vars */
window.urlLoadPlayers = '#',
    window.urlNextSpin = '#',
    window.isValid = false,
    window.waitTime = 180000,
    window.timeoutId = -1,
    window.minBetAmountBet = 1000
;

/**
 * Method to load active players
 */
function loadPlayers() {
    $('#noPlayerAlert').hide('slow');
    $.get(window.urlLoadPlayers, function (data, status) {
        console.log(data);
        if(data.length > 0){
            drawPlayers(data);
        }else{
            $('#noPlayerAlert').show('slow');
            window.isValid = false;
        }

    });

}

/**
 * Method to draw player information on the board
 * @param array players
 */
function drawPlayers(players) {
    let playerList = $('#playerList');
    playerList.empty();
    $.each(players, function (index, item) {
        let playerView = `<div id="${item.id}" class="row item-player">
                    <div class="col-6 float-left">
                        <label>
                            <i class="icofont-user-alt-1 icofont-2x"></i>${item.username} 
                        </label>
                        <span class="float-right"><b> $ ${item.amount}</b></span>
                    </div>
                    <div class="col-6">
                        <input type="number" min="1" data-current-amount="${item.amount}" class="form-control float-right ml-2" placeholder="apuesta">

                        <label class="content-input green ">
                            <input type="radio" name="bet_color_${item.id}" id="green-player-${item.id}" value="green" checked><i></i>
                        </label>
                        <label class="ml-5 content-input red">
                            <input type="radio" name="bet_color_${item.id}" id="red-player-${item.id}" value="red"><i></i>
                        </label>
                        <label class="content-input black">
                            <input type="radio" name="bet_color_${item.id}" id="black-player-${item.id}" value="black"><i></i>
                        </label>
                    </div>
                </div>`;
        console.log(playerView);
        playerList.append(playerView);
    });

    eventToValidateRules();
    /* start auto spin timeout */
    window.timeoutId = setTimeout(autoSpin, window.waitTime);
}

/**
 * Method to valid rules of bet amount
 */
function eventToValidateRules() {
    let amount = 0, currentAmount = 0, minAmount = 0, maxAmount = 0;
    $('.item-player input[type="number"]').keyup(function () {

        amount = parseInt($(this).val());
        currentAmount = parseInt($(this).data('current-amount'));
        minAmount = currentAmount * 0.11;
        maxAmount = currentAmount * 0.19;
        console.log(minAmount, amount, maxAmount);

        if (currentAmount > window.minBetAmountBet && (amount < minAmount || amount > maxAmount)) {
            $('#alertRule').show();
            $('#btnPlay').addClass('disabled');
        } else {
            if (currentAmount <= window.minBetAmountBet) {
                $(this).val(currentAmount);
            }
            $('#alertRule').hide();
            $('#btnPlay').removeClass('disabled');
        }
    });
}

/**
 * Method to spin the roulette and make a play
 */
function spinRoulette() {
    let players = [];
    $('#btnPlay').addClass('disabled');
    window.isValid = false;
    $('.item-player').each(function () {
        if ($(this).find('input[type="number"]').val() !== '') {
            players.push({
                id: $(this).attr('id'),
                color: $(this).find('input[type="radio"]:checked').val(),
                amount: $(this).find('input[type="number"]').val()
            });
            window.isValid = true;
        } else {
            $('#alertRule').show();
            window.isValid = false;
        }
    });
    if (window.isValid) {
        /* Stop wait auto spin */
        clearTimeout(window.timeoutId);
        $('#rouletteImage').addClass('rotate');

        /* Send bets */
        $.ajax({
            type: "POST",
            url: window.urlNextSpin,
            data: {'bets': players},
            dataType: "json",
            contexttype: "application/json",
            success: function (response) {
                console.log(response);
                $('#rouletteImage').removeClass('rotate');
                $('#contentResultBet').prepend(`<i class="result-color ${response.result_color}"></i>`)
                loadPlayers();
            },
            error: function () {
                $('#rouletteImage').removeClass('rotate');
                loadPlayers();
            }
        });
    }
}

/**
 * Method that will be executed every period of time if a move has not been made
 */
function autoSpin(){
    console.log('RUN AUTO SPIN');
    let currentAmount = 0;
    $('.item-player input[type="number"]').each(function () {
        currentAmount = $(this).data('current-amount');
        let percentage = (Math.floor(Math.random() * (9)) + 11)/100;
        if(currentAmount > window.minBetAmountBet){
            $(this).val(parseInt(currentAmount * percentage));
        }else{
            $(this).val(window.minBetAmountBet);
        }

    });
    spinRoulette();
}

/* Init */
document.addEventListener("DOMContentLoaded", function (event) {
    //do work
    window.urlLoadPlayers = $('#urlsApi').data('route-load-players');
    window.urlNextSpin = $('#urlsApi').data('route-next-spin');
    loadPlayers();
});