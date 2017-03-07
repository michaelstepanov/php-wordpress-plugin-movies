(function () {
    var $ = jQuery;

    $(document).ready(function () {
        $(".star").click(function () {
            onStarClick(this);
        });
    });

    function onStarClick(star) {
        var rating = $(star).data("rating");
        var movieId = $(star).closest("article").data("id");

        $.ajax({
            url: window.location.origin + "/wp-admin/admin-ajax.php",
            type: "post",
            data: {
                action: "set_movie_rating",
                id: movieId,
                rating: rating
            },
            success: function (response) {
                if (response === 'false') {
                    console.log("Movie rating can't be set twice");
                    alert("Movie rating can't be set twice");
                    return;
                }

                $(star).closest(".stars-list").find("li.star:lt(" + rating + ")").map(function () {
                    // Update stars' styles
                    $(this).removeClass("grey");
                    $(this).addClass("gold");
                });
            },
            error: function (response) {
                console.log("An error occured");
            }
        });
    }
}());