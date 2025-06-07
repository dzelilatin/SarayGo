$(document).ready(function() {
    var app = $.spapp({
      defaultView: "home",
      templateDir: "./tpl/",
      pageNotFound: "error_40324",
    });
  
    app.run();
});
