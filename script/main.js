$(document).ready(function() {

    var toAllButton = $("input[name='toAll']");
    var toDayButton = $("input[name='toDay']");
    var fnameField = $("input[name='fname']");
    var lnameField = $("input[name='lname']");
    var all = $("select[name='all']");
    var day = $("select[name='day']");
    var id;
    var matchKnapp = $("input[name='matchning']");
    var matchDiv = $("#matchResults");
    var resting = $("#resting");
    var reset = $("input[name='reset']");
    var resultButtonDiv = $("#resultButtonDiv");
    var resultButton;
    var selectMember = $("select[name='member']");
    var toRest = $("input[name='toRest']");
    var fromRest = $("input[name='fromRest']");
    var restBox = $("select[name='rest']");
    var sortByNr = $("#sortByNum");
    var sortByFname = $("#sortByFname");
    var winnerArrayCss = Array();
    var expResult = $("#expResult")






    $.ajax({
        url: "loadMatches.php",
        dataType: 'json',
        data: {},
        success: function(data1) {
            $.post('okToMatch.php', {}, function(data) {
                if (data == "true") {
                    writeMatches(data1);
                } else if (data == "false") {
                    writeMatches(data1, "false");
                }
            });
        }
    });

 expResult.click(function(e) {
        e.preventDefault();
          alert("Resultat exporterade!");
       $.get('export_today.php', {}, function(data) {

        });
   
    });




    sortByNr.click(function(e) {
        e.preventDefault();
        var my_options = $("select[name='day'] option");

        my_options.sort(function(a, b) {
            var atext = $(a).text();
            var btext = $(b).text();
            var aSplitText = atext.split(" ");
            var bSplitText = btext.split(" ");
            return parseInt(aSplitText[0]) == parseInt(bSplitText[0]) ? 0 : parseInt(aSplitText[0]) < parseInt(bSplitText[0]) ? -1 : 1;
        });
        $("select[name='day']").empty().append(my_options);
    });
    sortByFname.click(function(e) {
        e.preventDefault();
        var my_options = $("select[name='day'] option");
        my_options.sort(function(a, b) {
            var atext = $(a).text();
            var btext = $(b).text();
            var aSplitText = atext.split(" ");
            var bSplitText = btext.split(" ");
            return aSplitText[1] == bSplitText[1] ? 0 : aSplitText[1] < bSplitText[1] ? -1 : 1;
        });
        $("select[name='day']").empty().append(my_options);
    });
    toRest.click(function(e) {
        var idt = day.val();

        $.each(idt, function(index, playerId) {
            restBox.append('<option value="' + playerId + '">'
                    + ' ' + $("#day option[value='" + playerId + "']").text() + '</option>');
        });
        $.get('saveState.php', {ids: idt, type: "rest"}, function(data) {
        });
        $.get('saveStateRemove.php', {ids: idt, type: "day"}, function(data) {
        });
        $("#day option:selected").remove();
    });

    fromRest.click(function(e) {
        var idt = restBox.val();

        $.each(idt, function(index, playerId) {
            day.append('<option value="' + playerId + '">'
                    + ' ' + $("#rest option[value='" + playerId + "']").text() + '</option>');
        });
        $.get('saveState.php', {ids: idt, type: "day"}, function(data) {
        });
        $.get('saveStateRemove.php', {ids: idt, type: "rest"}, function(data) {
        });
        $("#rest option:selected").remove();
    });

    reset.click(function(e) {
        matchDiv.text('');
        resting.text('');
        $('#resultButtonDiv input').css("display", "none");
        $.ajax({
            url: "resetround.php",
            data: {},
            dataType: 'json',
            success: function(data) {
                $("#day option").remove();
                $.each(data, function(z, player) {
                    if (!$('#rest option[value="' + player.id + '"]').length)
                        day.append('<option value="' + player.id + '">' + player.boardNumber + ' ' + player.firstname + " " + player.lastname + '</option>');
                });
            }
        });
    });
    toAllButton.click(function(e) {
        if (confirm('Detta raderar dagens statistik för valda spelare, är du säker?')) {
            var idt = day.val();
            $("#day option:selected").remove();
            $.get('saveStateRemove.php', {ids: idt, type: "day", removeBoard: "true"}, function(data) {
            });
        }
    });
    toDayButton.click(function(e) {
        var idt = all.val();
        var playerIds = '';
        var onlyNewPlayers = new Array();

        $.each(idt, function(index, val) {
            if (!$('#rest option[value="' + val + '"]').length
                    && !$('#day option[value="' + val + '"]').length) {
                onlyNewPlayers.push(val);
                playerIds += ',' + val;
            }
        });
        playerIds = playerIds.substr(1);
        $.get('assignBoardNumber.php', {ids: playerIds}, function(data) {
            $.each(onlyNewPlayers, function(index, val) {
                day.append('<option value="' + val + '">'
                        + data[index] + ' ' + $("#all option[value='" + val + "']").text() + '</option>');
            });
            $.get('saveState.php', {ids: idt, type: "day"}, function(data) {
            });
        })
    });
    matchKnapp.click(function() {
        var playerIds = '';
        var Ids = 0;
        var prioRest = $("#prioRest").val();
        $.each($("#day option"), function(index, val) {
            playerIds += ',' + val.value;
            Ids++;
        });
        playerIds = playerIds.substr(1);
        var courts = $("input[name='courts']").val();
        $.post('okToMatch.php', {}, function(data) {
            if (data == "true") {
                alert("Rapportera först resultaten från förra omgången!");
            } else if (data == "false") {
                if (Ids > 3 && courts > 0) {
                    $.ajax({
                        url: "matchning.php",
                        data: {ids: playerIds, courts: courts, prioRest: prioRest},
                        dataType: 'json',
                        success: function(data) {
                            writeMatches(data);
                            $.post('saveMatches.php', {saveMatches: data}, function(data) {
                            });
                            var restingIds = new Array();
                            $("#rest option").each(function()
                            {
                                restingIds.push($(this).val());
                            });
                            $.get('chosenRest.php', {ids: restingIds}, function(data) {

                            });
                        }
                    });
                }
                else {
                    alert("Välj minst 4 spelare och fyll i antal banor");
                }
            }
        });
    });
    function writeMatches(data, reported) {

        matchDiv.text("");
        resting.text("");
        resultButtonDiv.text("");
        resultButtonDiv.append("<input type='button' value='Spara resultat' name='resultButton'>");
        resultButton = $("input[name='resultButton']");
        if (reported === "false") {
            console.log('hej');
            $('#resultButtonDiv input').css("display", "none");
            $(".winnerRadio").css("display", "none");
            $(".winText").css("display", "none");

        }
        $.each(data[0], function(key, val) {
            $.each(val, function(x, matchArray) {
                $.each(matchArray, function(z, match) {
                    var matchnummer = key + 1;

                    matchDiv.append("<form><fieldset class='fieldSet'>" +
                            "<legend>Match " + matchnummer + "</legend>" +
                            "<div class='par1'>"
                            + match.par1.player1.boardNumber + "   "
                            + match.par1.player1.firstname + "   " +
                            match.par1.player1.lastname + "  -  "
                            + match.par1.player2.boardNumber + "    "
                            + match.par1.player2.firstname + " " +
                            match.par1.player2.lastname
                            + "</div><div class='radioDiv'>"
                            + "<input type='radio' class='winnerRadio' name='" + key + "' value='par1'><br>"
                            + "<input type='radio' class='winnerRadio' name='" + key + "' value='par2'>"
                            + "</div><div class='winText'>Vinst</div>"
                            + "<br>vs<br><br><div class='par2'>"
                            + match.par2.player1.boardNumber + "   "
                            + match.par2.player1.firstname + "   "
                            + match.par2.player1.lastname + "  -  "
                            + match.par2.player2.boardNumber + "    "
                            + match.par2.player2.firstname + "   " +
                            match.par2.player2.lastname +
                            "</div></fieldset></form>");
                    if (reported == "false") {
                        console.log('hej');
                        $('#resultButtonDiv input').css("display", "none");
                        $(".winnerRadio").css("display", "none");
                        $(".winText").css("display", "none");

                    }
                });

            });
        });

        resultButton.click(function() {
            var winnerRadios = $('.winnerRadio:checked');
            if (winnerRadios.length < data[0].length) {
                alert('Klicka i det vinnande laget för varje match');
            } else {

                var winnerArray = Array();
                var loserArray = Array();
                winnerRadios.each(function(index) {
                    var winnerTeam = $(this).val();
                    var loserTeam;
                    if (winnerTeam == "par1") {
                        loserTeam = "par2";
                    } else {
                        loserTeam = "par1";
                    }
                    var wonMatch = $(this).attr('name');

                    winnerArray.push(data[0][wonMatch]['match']['0'][winnerTeam]['player1']['id']);
                    winnerArray.push(data[0][wonMatch]['match']['0'][winnerTeam]['player2']['id']);
                    loserArray.push(data[0][wonMatch]['match']['0'][loserTeam]['player1']['id']);
                    loserArray.push(data[0][wonMatch]['match']['0'][loserTeam]['player2']['id']);
                    winnerArrayCss[index] = winnerTeam;
                });
                $.ajax({
                    url: "reportResult.php",
                    data: {winnerIds: winnerArray, loserIDs: loserArray},
                    dataType: 'text',
                    success: function(data) {
                        console.log(data);
                        if (data == 'NO') {
                            alert('Resultatet är redan rapporterat');
                        } else if (data == 'YES') {

                            $('#resultButtonDiv input').css("display", "none");
                            $(".winnerRadio").css("display", "none");
                            $(".winText").css("display", "none");
                            $.each(winnerArrayCss, function(key, val) {
                                key = key + 1;
                                $("fieldset:contains('Match " + key + "') ." + val + "").css("background", "yellow");
                            });
                        }
                    }
                });
            }
        });
        if (data[1] != null) {
            $("#rest option").each(function()
            {
                resting.append($(this).text() + '<br>');
            });
            $.each(data[1], function(key, val) {
                resting.append(val.boardNumber + ' ' + val.firstname + ' ' + val.lastname + '<br>');
            });
        }
    }
});