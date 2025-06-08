let CartService = {
  initializeCart: function () {
    const userToken = localStorage.getItem("user_token");
    const decodedToken = jwt_decode(userToken);
    const userID = decodedToken.user.user_id;

    console.log(userID);

    const user_ID = {
      user_id: userID,
    };

    RestClient.post(
      `/user/create-cart/${userID}`,
      user_ID,
      function (response) {
        console.log(response);

        console.log("Cart Initalized.");
      },
      function (error) {
        console.error("Response: " + error.responseText);

        console.log("Cart Initialized ( DO NOT WORRY ABOUT THE ERROR )");
      }
    );
  },

  getCartID: function () {
    return new Promise((resolve, reject) => {
      const userToken = localStorage.getItem("user_token");
      if (!userToken) {
        toastr.error("You must be logged in to add to cart!");
        reject("User token not found");
        return;
      }
      const decodedToken = jwt_decode(userToken);
      const userID = decodedToken.user.user_id;
      console.log("getCartID - USER ID:" + userID);

      RestClient.get(
        `/cart/${userID}`,
        function (data) {
          // Accept both cart_id and cart_ID
          const cartId = data.cart_id || data.cart_ID;
          if (cartId) {
            console.log("HOLDUP", cartId);
            console.log("CartService::getCartId() -> " + cartId);
            resolve(cartId);
          } else {
            console.error(
              "getCartID: cart_id not found in response or data is malformed.",
              data
            );
            reject("Cart ID not found in API response");
          }
        },
        function (error) {
          console.log(error);
          reject(error);
        }
      );
    });
  },

  bookNow: async function (product_id) {
    const cart_ID = await CartService.getCartID();

    const data = {
      offer_id: product_id,
    };

    RestClient.put(
      `/cart/${cart_ID}/offer`,
      data,
      function (response) {
        toastr.success("Cart offer updated!");
        console.log(response);
      },
      function (error) {
        toastr.error("Error updating cart offer.");
        console.error(error);
      }
    );
  },

  __init: function () {
    console.log("CartService initial123123132ized");

    const userToken = localStorage.getItem("user_token");
    const decodedToken = jwt_decode(userToken);
    const userID = decodedToken.user.user_id;

    RestClient.get(`/user/cart/${userID}`, function (data) {
      const cartDiv = document.getElementById("cart-div");

      console.log(cartDiv);

      if (data.cart === false || !data.cart) {
        cartDiv.innerHTML = `
          <div class="container text-center d-flex flex-column align-items-center  mt-5">
            <h1 style="font-size: 64px;">Your cart's empty!</h1>
            <p style="color: #1d1d1f !important">Browse our store and add some items to your cart.</p>
          </div>
          `;
        return;
      }

      console.log(data);

      const NumbersInCart = CartService.countItems(data.orders);

      cartDiv.innerHTML = `
        <div class="container mt-5" id="cart-header">
          <div class="row d-flex justify-content-between align-items-center">
            <!-- Cart Items Section -->
            <div class="col-md-8" id="cart-items-section">
              <div class="mb-4">
                <h1>You booked: ${data.cart.cart_ID}</h1>
                <span class="order-text mt-2">
                 ${data}
                </span>
              </div>
              <div id="cart-items" class="row text-center mt-5 justify-content-center g-3">
              </div>
            </div>

            <!-- Cart Total Section -->
            <div class="col-md-3" id="cart-total-section">
              <div class="p-3 d-flex flex-column align-items-center justify-content-between">
              <div class="d-flex flex-row w-100 justify-content-between">
              <p>Subtotal</p> 
              <p> $${data.cart.price_total}</p>
              </div>

              <div class="d-flex flex-row w-100 justify-content-between">
              <p>Shipping</p> 
              <p>$${CartService.calculateShipping(data.cart.price_total)}</p>
              </div>

              <div class="d-flex flex-row w-100 justify-content-between align-items-center" style="border-top: 1px solid black">

              <span class="mt-5 summary" >
                Total 
              </span>

              <span class="mt-5 summary">
                $${CartService.calculateTotalPrice(data.cart.price_total)}
              </span>

              </div>
               
                <button id="purchaseNowButton" onclick="OrderService.purchaseItems()">Purchase Now</button>
              </div>
            </div>
          </div>
        </div>
      `;

      const itemsDiv = document.getElementById("cart-items");
      let itemsHTML = "";

      for (const order of data.orders) {
        itemsHTML += `
          <div class="cart-item w-100 border-bottom pb-3 mb-3">
      <div class="row align-items-center">
        <!-- Product Image -->
        <div class="col-md-3 d-flex justify-content-start">
          <img src="assets/${
            order.image_url
          }" class="img-fluid" style="max-height: 150px; max-width: 150px; object-fit: contain;">
        </div>

        <!-- Product Details -->
        <div class="col-md-3 d-flex flex-column text-start more-info">
          <h3 class="product-title mb-2">${order.product_name}</h3>
          <a style="text-decoration: none; color:#0070d1;" >Show product details</a>
        </div>

        <!-- Actions and Price -->
        <div class="product-price col-md-6 p-5 text-end">
          <p class=" mb-2">$${order.price} or <br> $ ${CartService.calculateApr(
          order.price
        )}/month for <br> 12 months  at 0% apr</p>
          <button class="btn btn-link text-danger p-0" onclick="CartService.deleteOrder(${
            order.cart_item_ID
          })">Remove</button>
        </div>
      </div>
    </div>
  `;
      }

      itemsDiv.innerHTML = itemsHTML;
    });
  },

  calculateTotalPrice: function (price) {
    console.log(price);

    const num = parseFloat(price);

    // Use `this` to call the calculateShipping method
    let totalPrice = num + parseFloat(this.calculateShipping(price));

    return totalPrice.toFixed(2); // Return the total price rounded to 2 decimal places
  },

  calculateShipping: function (price) {
    if (price > 500) {
      return 0;
    }

    const tax = price * 0.071322;

    return tax.toFixed(2);
  },

  calculateApr: function (price) {
    const num = parseFloat(price);
    const apr = num / 12;
    return apr.toFixed(2);
  },

  countItems: function (iterable) {
    if (iterable == null) return 0;

    if (typeof iterable.length === "number") {
      return iterable.length;
    }

    if (typeof iterable.size === "number") {
      return iterable.size;
    }

    if (typeof iterable[Symbol.iterator] === "function") {
      let count = 0;
      for (const _ of iterable) {
        count++;
      }
      return count;
    }

    return 0;
  },

  deleteOrder: function (data) {
    console.log("deleting item with id:", data);
    const userToken = localStorage.getItem("user_token");
    const userID = UserService.getUserId();

    $.ajax({
      url: `http://saraygo.local/api/cart/item/${data}/${userID}`,
      type: "DELETE",
      headers: {
        Authentication: `${userToken}`,
      },
      success: function (data) {
        console.log(data);
        toastr.success("Deleted product from cart.");
      },
      error: function (error) {
        console.log(error);
        toastr.success("Deleted product from cart.");
      },
    });
  },
};
