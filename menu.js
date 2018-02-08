$(function () {
    var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);

    if (pgurl == "charts.php" || pgurl == "informes.php") {
        $("#aInformes").addClass("active");
    } else {
        $(".aMenu").each(function () {
            //console.log($(this).attr("href"));
            if ($(this).attr("href") == pgurl) {
                $(this).addClass("active");
            }
        });
    }


});