$(document).ready(function() {

    var fnameField = $("input[name='fname']");
    var lnameField = $("input[name='lname']");
    var winField = $("input[name='wins']");
    var restField = $("input[name='rests']");
       var playedField = $("input[name='played_games']");
    var changeButton = $("input[name='change']");
    var all = $("select[name='all']");
    var getPlayerButton = $("input[name='getPlayer']");
    var selectSex = $("select[name='sex']");
    var purgePlayerButton = $("input[name='purge']");
    var currentPlayer;
    var selectMember = $("select[name='member']");
    var addButton = $("input[type='submit']");
    var sexRadio = $("input[name='insex']");
    var infnameField = $("input[name='infname']");
    var inlnameField = $("input[name='inlname']");
       var inselectMember = $("select[name='inmember']");
    var unsetSessionA = $("#unsetSession");
    
    unsetSessionA.click(function(e){
e.preventDefault();
 $.get('unsetSession.php');


});
    addButton.click(function(e) {
        if (!(infnameField.val() == "" || inlnameField.val() == "")) {
            e.preventDefault();
            var fname = infnameField.val();
            var lname = inlnameField.val();
            var sex = $("input[name='insex']:checked").val();
            var member = inselectMember.val();

            //Skicka till php-databas, h�mta id
            $.get('addplayer.php', {fname: fname, lname: lname, sex: sex, member: member}, function(data) {

                if (data == "fail") {
                    alert("Spelarnamn upptaget")
                } else {
                    all.append('<option value="' + data + '">' + fname + " " + lname + '</option>');

                    infnameField.val("");
                    inlnameField.val("");
                    infnameField.focus();
                }
            }); 
        } else {
            alert("ange namn och efternamn");
            e.preventDefault();
        }


    });


    purgePlayerButton.click(function(e) {
        var idt = all.val();

        $.ajax({
            url: 'purge_player.php',
            data: {id: idt[0]},
            dataType: 'json',
            success: function(data)
            {
                console.log(data);
                $("select[name='all'] option[value='" + idt[0] + "']").remove();
                currentPlayer = data[0];
                fnameField.val("");
                lnameField.val("");


                $("#selectSex").val("M").attr('selected', true);

                winField.val("");
                restField.val("");

            }
        });

    });



    getPlayerButton.click(function(e) {
        var idt = all.val();

        $.ajax({
            url: 'get_player.php',
            data: {id: idt[0]},
            dataType: 'json',
            success: function(data)
            {
                console.log(data);
                currentPlayer = data[0];
                fnameField.val(data[1]);
                lnameField.val(data[2]);

                if (data[3] == "F") {
                    $("#selectSex").val("F").attr('selected', true);
                }
                if (data[3] == "M") {
                    $("#selectSex").val("M").attr('selected', true);
                }
                winField.val(data[4]);
                restField.val(data[7]);
                playedField.val(data[6]);
                if (data[5] == "Y") {
                    $("#selectMember").val("Y").attr('selected', true);
                }
                if (data[5] == "N") {
                    $("#selectMember").val("N").attr('selected', true);
                }
                
            }
        });

    });

    changeButton.click(function() {
        if (currentPlayer === null) {
            alert("Hämta först in en spelare från listan!");
        } else {

            var fname = fnameField.val();
            var lname = lnameField.val();
            var sex = selectSex.val();
            var wins = winField.val();
            var rests = restField.val();
            var member = selectMember.val();
            var played_games = playedField.val();
            //     $('#all[value="'+currentPlayer+'"]').text('dfgdfg');
            $.ajax({
                url: 'changePlayer.php',
                data: {id: currentPlayer, fname: fname, lname: lname, sex: sex, wins: wins, rests: rests, member: member, played_games: played_games},
                dataType: 'text',
                success: function(data) {
                    alert("Ändringar genomförda!");
                    $('#all option[value="' + currentPlayer + '"]').text(fname + '  ' + lname);

                }
            });
        }
    });


});