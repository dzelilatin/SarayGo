let UserService = {
    init: function () {
        var token = localStorage.getItem("user_token");
        if (token && token !== undefined) {
            window.location.replace("index.html");
        }
        $("#login-form").validate({
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            },
        });
    },
    login: function (entity) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log(result);
                localStorage.setItem("user_token", result.data.token);
                window.location.replace("index.html");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest?.responseText ? XMLHttpRequest.responseText : 'Error');
            },
        });
    },
    logout: function () {
        localStorage.clear();
        window.location.replace("login.html");
    },
    generateMenuItems: function() {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;

        if (user && user.role) {
            let nav = "";
            let main = "";
            
            // Common menu items for all users
            nav = '<li class="nav-item mx-0 mx-lg-1">' +
                    '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#activities">Activities</a>' +
                  '</li>' +
                  '<li class="nav-item mx-0 mx-lg-1">' +
                    '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#moods">Moods</a>' +
                  '</li>';

            // Admin-specific menu items
            if (user.role === Constants.ADMIN_ROLE) {
                nav += '<li class="nav-item mx-0 mx-lg-1">' +
                         '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#users">Users</a>' +
                       '</li>' +
                       '<li class="nav-item mx-0 mx-lg-1">' +
                         '<a class="nav-link py-3 px-0 px-lg-3 rounded" href="#reviews">Reviews</a>' +
                       '</li>';
            }

            // Add logout button
            nav += '<li class="nav-item mx-0 mx-lg-1">' +
                     '<button class="btn btn-primary" onclick="UserService.logout()">Logout</button>' +
                   '</li>';

            // Main content sections
            main = '<section id="activities" data-load="activities.html"></section>' +
                   '<section id="moods" data-load="moods.html"></section>';

            if (user.role === Constants.ADMIN_ROLE) {
                main += '<section id="users" data-load="users.html"></section>' +
                        '<section id="reviews" data-load="reviews.html"></section>';
            }

            $("#tabs").html(nav);
            $("#spapp").html(main);
        } else {
            window.location.replace("login.html");
        }
    }
}; 