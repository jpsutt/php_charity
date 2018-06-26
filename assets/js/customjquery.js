$(document).ready(function() {

    //HOUSEKEEPING

    $("#banner").css("height", $(window).height());
    $("#banner").css("padding-top", $(window).height()/4);

    $(".popup").each(function() {
        $(this).css("top", (($(window).height() / 2) - ($(this).height() / 2)));
        $(this).css("left", (($(window).width() / 2) - ($(this).width() / 2)));
    });

    $(".popup").hide();
    $("#hud").hide();
    $("#btntomainv").hide();


    $("#btnlogin").on('click', function(event){
        $("#banner").children().fadeOut();
        $("#Login").fadeIn("slow");
        $("#Login").focus();
        event.stopPropagation();
    });

    $("#btnregister").on('click', function(event){
        $("#banner").children().fadeOut();
        $("#Register").fadeIn("slow");
        $("#Register").focus();
        event.stopPropagation()
    });

    $("#btnforgotpwrd").on('click', function(event){
        $("#Login").fadeOut("slow");
        $("#banner").children().fadeOut();
        $("#ForgotPassword").fadeIn("slow");
        $("#ForgotPassword").focus();
        event.stopPropagation();
    });

    $("#back").on('click', function(event){
        $("#banner").children().fadeOut();
        $("#Register").fadeIn("slow");
        $("#Register").focus();
        event.stopPropagation();
    });

    $(document).click(function() {
        $(".popup").fadeOut("slow");
        if ($("#hud").hasClass("faded")) {
            $("#SendResources").fadeOut("slow");
            $("#hud").children().fadeIn("slow");
            $("#hud").removeClass("faded");
        }
        else {
            $("#banner").children().fadeIn();
        }
        $('body').css('overflow', 'auto');
    });

    $(".popup").click(function(event){
        event.stopPropagation();
    });

    $("#welcomeback").on('click', function(event){
        $("#banner").fadeOut("slow");
        $("#hud").css("height", $(window).height());
        $("#hud").fadeIn("slow");
    });

    $("#btntomain").on('click', function(event){
        $("#banner").hide();
        $("#hud").css("height", $(window).height());
        $("#hud").show();
    });

    $("#btnsendresources").on('click', function(event){
        $("#hud").children().fadeOut();
        $("#hud").addClass('faded');
        $("#SendResources").fadeIn("slow");
        $("#SendResources").focus();
        event.stopPropagation();
    });

    $("#btnedituser").on('click', function(event){
        $("#hud").children().fadeOut();
        $("#hud").addClass('faded');
        $("#EditUser").each(function(){
            $(this).css("top", (($(window).height() / 2) - ($(this).height() / 2)));
            $(this).css("left", (($(window).width() / 2) - ($(this).width() / 2)));
        });
        $('body').css('overflow', 'hidden');
        $("#EditUser").fadeIn("slow");
        $("#EditUser").focus();
        event.stopPropagation();
    });

    $("#editemail").on('change keyup paste', function() {
        $('#editemaillist').empty();
        var txt = $(this).val();
        if(txt != '')
        {
            $.ajax({
                url:"Utility/database/searchusers.php",
                method:"post",
                data:{search:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#editemaillist').append(data);
                }
            });
        }
        else
        {
            $('#editemail').html('');
            $('#editemaillist').empty();
        }
    });

    $("#editisadmin").on('click', function(){
        if($("#editisadmin").is(':checked')){
            $("#editisadmin").prop('checked', false);
        } else {
            $("#editisadmin").prop('checked', true);
        }
    });

    $("#editisactive").on('click', function(){
        if($("#editisactive").is(':checked')){
            $("#editisactive").prop('checked', false);
        } else {
            $("#editisactive").prop('checked', true);
        }
    });


    $("#editemail").on('change keyup paste', function() {
        var txt = $(this).val();
        if(txt != '')
        {
            $.ajax({
                url:"Utility/database/getuser.php",
                method:"post",
                data:{edituser:txt},
                dataType:"json",
                success:function(data)
                {
                    $("#editpassword").val(data.password);
                    $("#editpoints").val(data.points);
                    $("#editresources").val(data.resources);
                    $("#editcollectionlvl").val(data.collectlvl);
                    console.log(data.active);
                    console.log(data.isAdmin);
                    if (data.active == 1) {
                        $("#editisactive").prop('checked', true);
                    } else {
                        $("#editisactive").prop('checked', false);
                    }
                    if (data.isAdmin == 1) {
                        $("#editisadmin").prop('checked', true);
                    } else {
                        $("#editisadmin").prop('checked', false);
                    }
                    if (data.adminlvl > 0) {
                        $("#editisadminlvl").show();
                    } else {
                        $("#editisadmin").hide();
                    }

                }
            });
        }
        else
        {
            $('#editemail').html('');
            $('#editemaillist').empty();
        }
    });



    $("#btnupgrades").on('click', function(event){
        $("#hud").children().fadeOut();
        $("#hud").addClass('faded');
        $("#Upgrades").fadeIn("slow");
        $("#Upgrades").focus();
        event.stopPropagation();
    });

    $("#btnbuyupgrade").on('click', function(event){
        $("#buyupgrades").submit();
    });

    $("#resourcessent").bind('keyup keydown mousedown mouseup', function() {
        var amount = $("#resourcessent").val();
        var sent = Math.floor(amount / 2);
        $("#resourcesreceived").attr('placeholder', sent);
        $("#resourcesreceivedhidden").attr('value', sent);
    });


    $("#remail").on('change keyup paste', function() {
        $('#remaillist').empty();
        var txt = $(this).val();
        if(txt != '')
        {
            $.ajax({
                url:"Utility/database/searchusers.php",
                method:"post",
                data:{search:txt},
                dataType:"text",
                success:function(data)
                {
                    $('#remaillist').append(data);
                }
            });
        }
        else
        {
            $('#remail').html('');
            $('#remaillist').empty();
        }
    });

    $('#historytable').dataTable({
        paging: false,
        scrollY: ($(window).height() / 2),
        autoWidth: true,
        fixedHeader: true,
    });

    $("#btnhistory").on('click', function(event){
        $("#hud").children().fadeOut();
        $("#hud").addClass('faded');
        $("#TransactionHistory").each(function(){
            $(this).css("top", (($(window).height() / 2) - ($(this).height() / 2)));
            $(this).css("left", (($(window).width() / 2) - ($(this).width() / 2)));
        });
        $('body').css('overflow', 'hidden');
        fixme();
        $("#TransactionHistory").fadeIn("slow");
        $("#TransactionHistory").focus();
        event.stopPropagation();
    });

    $('#leaderboardtable').dataTable({
        paging: false,
        scrollY: ($(window).height() / 2),
        autoWidth: true,
        fixedHeader: true,
        info: false,
        ordering: false
    });

    $("#btnleaderboard").on('click', function(event){
        $("#hud").children().fadeOut();
        $("#hud").addClass('faded');
        $("#Leaderboard").each(function(){
            $(this).css("top", (($(window).height() / 2) - ($(this).height() / 2)));
            $(this).css("left", (($(window).width() / 2) - ($(this).width() / 2)));
        });
        $('body').css('overflow', 'hidden');
        fixme();
        $("#Leaderboard").fadeIn("slow");
        $("#Leaderboard").focus();
        event.stopPropagation();
    });

    //I found this nice function on the internet to fix a formatting issue i was having with the datatables()
    function fixme() {
        var table = $.fn.dataTable.fnTables();
        if ( table.length > 0 ) {
            setTimeout(function () {
                for(var i=0;i<table.length;i++){
                    $(table[i]).dataTable().fnAdjustColumnSizing();
                }
            }, 200);
        }
    }

});
