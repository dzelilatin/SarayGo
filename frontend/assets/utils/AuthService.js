let AuthService = {
  register: function () {
    console.log("Registering user...");

    const registerForm = document.getElementById("registerForm");

    if (registerForm) {
      console.log("Register form found");
    }

    registerForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const firstName = document.getElementById("first_name").value;
      const lastName = document.getElementById("last_name").value;
      const phone = document.getElementById("phone").value;
      const addressLine1 = document.getElementById("address_line1").value;
      const addressLine2 = document.getElementById("address_line2").value;
      const city = document.getElementById("city").value;
      const country = document.getElementById("country").value;
      const postalCode = document.getElementById("postal_code").value;

      let data = {
        email: email,
        password: password,
        first_name: firstName,
        last_name: lastName,
        phone: phone,
        address_line1: addressLine1,
        address_line2: addressLine2 || "",
        city: city,
        country: country,
        postal_code: postalCode,
      };

      if (AuthService.validateEmail(email) == null) {
        toastr.error("Invalid Email!");
      } else {
        $.ajax({
          url: "http://saraygo.local/api/auth/register",
          type: "POST",
          data: JSON.stringify(data),
          contentType: "application/json",
          success: function (res) {
            console.log(res);
            console.log(data);

            toastr.success("Registration successful");
            window.location.href = "#view_login";
          },
          error: function (err) {
            console.log(err);
            toastr.error("Registration failed: " + err.responseText);
          },
        });
      }
    });
  },

  validateEmail: function (email) {
    return String(email)
      .toLowerCase()
      .match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
  },

  login: function () {
    console.log("Logging in user...");

    const LoginForm = document.getElementById("loginForm");

    LoginForm.addEventListener("submit", function (event) {
      event.preventDefault();

      console.log("Login form submitted.");

      const email = document.getElementById("email-login").value;
      const password = document.getElementById("password-login").value;

      const loginData = {
        email: email,
        password: password,
      };

      $.ajax({
        url: "http://saraygo.local/api/auth/login",
        type: "POST",
        data: JSON.stringify(loginData),
        contentType: "application/json",
        success: function (res) {
          console.log(res);
          localStorage.setItem("user_token", res.data.user_token);
          toastr.success("Login successful");
          window.location.href = "#view_main";
        },
        error: function (err) {
          console.log(err);
          toastr.error("Login failed: " + err.responseText);
        },
      });
    });
  },

  logOut: function () {
    localStorage.removeItem("user_token");

    NavbarService.renderNavbar();
  },
};
