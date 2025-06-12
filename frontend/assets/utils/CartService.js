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
  __init: function () {
    const userToken = localStorage.getItem("user_token");
    const decodedToken = jwt_decode(userToken);
    const userID = decodedToken.user.user_id;

    console.log(userID);

    RestClient.get(`user/cart/${userID}`, function (data) {
      const cartDiv = document.getElementById("cart-div");

      console.log(data);

      console.log("CartService::getCart() -> " + data);
      if (data === false || !data) {
        cartDiv.innerHTML = `
           <div class="container text-center d-flex flex-column align-items-center  mt-5">
          <h1 style="font-size: 64px;">Your cart's empty!</h1>
     
            <p style="color: #1d1d1f !important">Browse our store and add some items to your cart.</p>

            <a href="#view_shop" class="mt-4"><button style="border-radius:16px;  background-color:transparent" >Shop Page</button> </a>
            </div>
        `;
        return;
      }

      console.log(data);

      // Handle single cart item with new data structure
      cartDiv.innerHTML = `
        <div class="cart-container">
          <!-- Header Section -->
          <div class="cart-header">
            <div class="breadcrumb">
              <span class="breadcrumb-home">Home</span>
              <span class="breadcrumb-separator">›</span>
              <span class="breadcrumb-current">Cart</span>
            </div>
            <h1 class="cart-title">Your Booking</h1>
            <p class="cart-subtitle">Review your selection before proceeding</p>
          </div>

          <!-- Cart Item Card -->
          <div class="cart-item-card">
            <div class="cart-item-image">
              <img src="${data.offer_image}" alt="${data.offer_name}" />
              <div class="offer-type-badge">${data.offer_type}</div>
            </div>
            
            <div class="cart-item-content">
              <div class="cart-item-header">
                <h2 class="offer-name">${data.offer_name}</h2>
                <button class="remove-btn" onclick="CartService.deleteOrder(${
                  data.cart_ID
                })" title="Remove from cart">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                  </svg>
                </button>
              </div>
              
              <p class="offer-description">${data.offer_description}</p>
              
              <div class="offer-details">
                <div class="detail-item">
                  <span class="detail-label">Location</span>
                  <span class="detail-value">${data.offer_location}</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Status</span>
                  <span class="detail-value status-${data.status}">${
        data.status
      }</span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Added</span>
                  <span class="detail-value">${new Date(
                    data.created_at
                  ).toLocaleDateString("en-US", {
                    year: "numeric",
                    month: "short",
                    day: "numeric",
                  })}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div class="cart-summary">
            <div class="summary-content">
              <div class="price-breakdown">
                <div class="price-item">
                  <span class="price-label">Subtotal</span>
                  <span class="price-value">$${data.offer_price}</span>
                </div>
                <div class="price-item">
                  <span class="price-label">Service Fee</span>
                  <span class="price-value">$${CartService.calculateShipping(
                    data.offer_price
                  )}</span>
                </div>
                <div class="price-item total">
                  <span class="price-label">Total</span>
                  <span class="price-value">$${CartService.calculateTotalPrice(
                    data.offer_price
                  )}</span>
                </div>
              </div>
              
              <button class="checkout-button" onclick="CartService.deleteOrder(${
                data.cart_ID
              })" >
                <span >Complete Booking</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
              </button>
              
              <p class="checkout-note">
                You'll be redirected to secure payment
              </p>
            </div>
          </div>
        </div>
      `;

      // Add CSS styles if not already present
      CartService.injectCartStyles();
    });
  },

  bookNow: async function (product_id) {
    const cart_ID = await CartService.getCartID();

    const data = {
      cart_ID: cart_ID,
      offer_id: product_id,
    };

    RestClient.post(
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
      url: `${Constants.PROJECT_BASE_URL}user/cart/deletecart/${userID}`,
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
        toastr.error("Error deleting product from cart.");
      },
    });
  },

  bookOffer: function (offer_id) {
    const user_ID = UserService.getUserId();

    const data = {
      user_ID: user_ID,
      offer_id: offer_id,
    };

    RestClient.post(
      `/cart/item/new-item`,
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

  injectCartStyles: function () {
    // Check if styles are already injected
    if (document.getElementById("cart-custom-styles")) {
      return;
    }

    const style = document.createElement("style");
    style.id = "cart-custom-styles";
    style.textContent = `
      .cart-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
      }      .cart-empty-state {
        position: relative;
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #fafbfc 0%, #f4f6f8 50%, #e8ecef 100%);
        border-radius: 24px;
        margin: 2rem 0;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      .empty-state-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
        z-index: 0;
      }

      .empty-state-circle {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(147, 51, 234, 0.05) 100%);
        top: -200px;
        right: -200px;
        animation: float 6s ease-in-out infinite;
      }

      .empty-state-circle-small {
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.06) 0%, rgba(251, 146, 60, 0.04) 100%);
        bottom: -100px;
        left: -100px;
        animation: float 4s ease-in-out infinite reverse;
      }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
      }

      .empty-state-content {
        position: relative;
        z-index: 1;
        max-width: 480px;
      }

      .empty-cart-icon {
        margin-bottom: 2rem;
        opacity: 0.7;
        color: #64748b;
        background: white;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
      }

      .empty-state-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 1rem 0;
        letter-spacing: -0.03em;
        line-height: 1.2;
      }

      .empty-state-description {
        font-size: 1.125rem;
        color: #64748b;
        margin: 0 0 2.5rem 0;
        line-height: 1.6;
        font-weight: 400;
        max-width: 420px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2.5rem;
      }

      .empty-state-cta {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 3rem;
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3), 0 2px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
      }

      .empty-state-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
      }

      .empty-state-cta:hover::before {
        left: 100%;
      }

      .empty-state-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4), 0 4px 16px rgba(0, 0, 0, 0.15);
      }

      .empty-state-cta:active {
        transform: translateY(0);
      }

      .empty-state-features {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
      }

      .feature-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        min-width: 120px;
      }

      .feature-item:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
      }

      .feature-icon {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
      }

      .feature-item span {
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        text-align: center;
      }

      .cart-header {
        margin-bottom: 2rem;
      }

      .breadcrumb {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
      }

      .breadcrumb-separator {
        margin: 0 0.5rem;
      }

      .breadcrumb-current {
        color: #1e293b;
        font-weight: 500;
      }

      .cart-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.025em;
      }

      .cart-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
      }

      .cart-item-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.2s ease;
      }

      .cart-item-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 25px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
      }

      .cart-item-image {
        position: relative;
        height: 240px;
        overflow: hidden;
      }

      .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
      }

      .cart-item-card:hover .cart-item-image img {
        transform: scale(1.02);
      }

      .offer-type-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
      }

      .cart-item-content {
        padding: 1.5rem;
      }

      .cart-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
      }

      .offer-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.3;
        flex: 1;
        padding-right: 1rem;
      }

      .remove-btn {
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        padding: 0.5rem;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
      }

      .remove-btn:hover {
        background: #fee2e2;
        color: #dc2626;
        transform: scale(1.05);
      }

      .offer-description {
        color: #64748b;
        font-size: 0.975rem;
        line-height: 1.6;
        margin: 0 0 1.5rem 0;
      }

      .offer-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
      }

      .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
      }

      .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }

      .detail-value {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
      }

      .status-open {
        color: #059669;
        background: #d1fae5;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        width: fit-content;
      }

      .cart-summary {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        overflow: hidden;
      }

      .summary-content {
        padding: 1.5rem;
      }

      .price-breakdown {
        margin-bottom: 1.5rem;
      }

      .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
      }

      .price-item:last-child {
        border-bottom: none;
      }

      .price-item.total {
        border-top: 2px solid #e2e8f0;
        margin-top: 0.5rem;
        padding-top: 1rem;
        font-weight: 700;
        font-size: 1.125rem;
      }

      .price-label {
        color: #64748b;
        font-weight: 500;
      }

      .price-value {
        color: #1e293b;
        font-weight: 600;
      }

      .checkout-button {
        width: 100%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
      }

      .checkout-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
      }

      .checkout-button:active {
        transform: translateY(0);
      }

      .checkout-note {
        text-align: center;
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
      }

      .cta-button {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.875rem 1.5rem;
        font-size: 0.975rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
      }

      .cta-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
      }      @media (max-width: 640px) {
        .cart-container {
          padding: 1rem;
        }
        
        .cart-item-content {
          padding: 1rem;
        }
        
        .offer-details {
          grid-template-columns: 1fr;
        }
        
        .cart-item-header {
          flex-direction: column;
          align-items: flex-start;
          gap: 1rem;
        }
        
        .offer-name {
          padding-right: 0;
        }
        
        .remove-btn {
          align-self: flex-end;
        }

        .cart-empty-state {
          padding: 3rem 1.5rem;
          min-height: 400px;
        }

        .empty-state-title {
          font-size: 1.875rem;
        }

        .empty-state-description {
          font-size: 1rem;
          margin-bottom: 2rem;
        }

        .empty-cart-icon {
          width: 100px;
          height: 100px;
          margin-bottom: 1.5rem;
        }

        .empty-cart-icon svg {
          width: 64px;
          height: 64px;
        }

        .empty-state-features {
          gap: 1rem;
        }

        .feature-item {
          min-width: 100px;
          padding: 0.75rem;
        }

        .empty-state-circle {
          width: 300px;
          height: 300px;
          top: -150px;
          right: -150px;
        }

        .empty-state-circle-small {
          width: 150px;
          height: 150px;
          bottom: -75px;
          left: -75px;
        }
      }
    `;

    document.head.appendChild(style);
  },
};
