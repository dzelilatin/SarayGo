$(document).ready(function() {
  var app = $.spapp({
    defaultView: "home",
    templateDir: "",  // Keep it empty to prevent double tpl/
    pageNotFound: "error_40324",
  });

  app.run();
});