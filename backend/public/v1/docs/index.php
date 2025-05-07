<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>SarayGo API Documentation</title>
    <!-- Modify the links to the correct paths -->
    <link rel="stylesheet" type="text/css" href="/SarayGo/backend/public/v1/docs/swagger-ui.css" >
    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
    <style>
      html {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
      }
      *,
      *:before,
      *:after {
        box-sizing: inherit;
      }
      body {
        margin: 0;
        background: #fafafa;
      }
    </style>
  </head>

  <body>
    <div id="swagger-ui"></div>

    <!-- Modify the script sources to the correct paths -->
    <script src="/SarayGo/backend/public/v1/docs/swagger-ui-bundle.js"></script>
    <script src="/SarayGo/backend/public/v1/docs/swagger-ui-standalone-preset.js"></script>

    <script>
      window.onload = function() {
        // Begin Swagger UI call region
        const ui = SwaggerUIBundle({
          // Modify the URL path to reflect the correct location of swagger.php
          url: "/SarayGo/backend/public/v1/docs/swagger.php", 
          dom_id: '#swagger-ui',
          deepLinking: true,
          presets: [
            SwaggerUIBundle.presets.apis,
            SwaggerUIStandalonePreset
          ],
          plugins: [
            SwaggerUIBundle.plugins.DownloadUrl
          ],
          layout: "StandaloneLayout"
        })
        // End Swagger UI call region
        window.ui = ui
      }
    </script>
  </body>
</html>