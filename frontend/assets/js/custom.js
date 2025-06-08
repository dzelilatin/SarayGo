$(document).ready(function () {
  $("main#spapp > section").height($(document).height());

  var app = $.spapp({
    defaultView: "#view_main",
    templateDir: "./tpl/",
    pageNotFound: "view_error.html",
  });

  const userToken = localStorage.getItem("user_token");

  if (!userToken) {
    app.route({
      view: "view_book",
      load: "view_book.html",
    });

    app.route({
      view: "view_shop",
      load: "view_shop.html",
    });

    app.route({
      view: "view_login",
      load: "view_login.html",
    });

    app.route({
      view: "view_register",
      load: "view_register.html",
    });

    app.route({
      view: "view_product",
      load: "view_product.html",
    });

    app.route({
      view: "view_cart",
      load: "view_cart.html",
    });
  }

  if (userToken) {
    app.route({
      view: "view_main",
      load: "view_main.html",
    });

    app.route({
      view: "view_book",
      load: "view_book.html",
    });

    app.route({
      view: "view_cart",
      load: "view_cart.html",
    });

    app.route({
      view: "view_login",
      load: "view_login.html",
    });

    app.route({
      view: "view_shop",
      load: "view_shop.html",
    });

    app.route({
      view: "view_product",
      load: "view_product.html",
    });

    app.route({
      view: "view_login",
      load: "view_login.html",
    });

    const token = jwt_decode(userToken);
    const userID = token.user.is_admin;

    if (userToken && userID == 1) {
      app.route({
        view: "view_admin",
        load: "view_admin.html",
      });
    }
  }

  app.run();
});
