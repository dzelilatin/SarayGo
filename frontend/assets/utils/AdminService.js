let AdminService = {
  init: function () {
    AdminService.renderAdminDiv();
  },

  renderAdminDiv: function () {
    console.log("Rendering Admin Div");
    // Add logic to render the admin div here
  },

  getUsersByName: function (name) {
    console.log("CURRENT NAME:: ", name);

    if (!name) {
      console.log("CURRENT NAME:: ", name);
      const cardBody = document.getElementById("user-card-body");
      cardBody.innerHTML = "";
      cardBody.innerHTML =
        '<h5 class="text-center mt-5">Please enter a name to search for users.</h5>';
      return;
    }

    const cardBody = document.getElementById("user-card-body");

    cardBody.innerHTML = "";
    RestClient.get(`/admin/user/name/${name}`, function (data) {
      console.log(data);

      data.forEach((user) => {
        cardBody.innerHTML += ` 
        <div class="d-flex flex-column justify-content-between align-items-center  p-1">
            <div class="d-flex flex-row justify-content-between align-items-center w-100">
              <h5 class="mb-0">${user.first_name} ${user.last_name}</h5>
              <div onclick="AdminService.toggleOnOff(${user.user_id})" title="Show Details">
                <img src="/assets/images/icons/down-icon.png" id="down-icon${user.user_id}" style="width: 22px; height: 22px;" alt="Show Details">
              </div>
            </div>

            <div class="px-1 mt-3 in-active w-100" id="user-card-body-${user.user_id}">
              <div class="d-flex flex-row justify-content-around align-items-center w-100">
                <div class="mt-1 p-1 text-center">
                  <div onclick="AdminService.getUserOrderHistory(${user.user_id})" class="text-center">
                    <img class="align-self-center" src="/assets/images/icons/historyIcon.png" style="width: 28px; height: 28px;" alt="View Order History">
                    <p class="border-bottom">Order History</p>
                  </div>
                </div>

                <div class="mb-0 p-1 d-flex justify-content-center align-items-center flex-column" onclick="AdminService.getUserCart(${user.user_id})">
                  <img class="align-self-center" src="/assets/images/icons/cart.png" style="width: 28px; height: 28px; " alt="View Carts">
                  <p  class = "border-bottom text-end">Active Carts</p>
                </div>


                <div  class="mb-0 p-1 d-flex justify-content-center align-items-center flex-column" onclick="AdminService.showMoreUserInfo(${user.user_id})">
                  
                <img class="align-self-center" src="/assets/images/icons/infoIcon.png" style="width: 28px; height: 28px; " alt="View More Info">
                <p  class = "border-bottom">More Info</p>
                  </div>


              </div>
            </div>
          </div>
        
        
        `;
      });
    });
  },

  toggleOnOff: function (data) {
    console.log(data);

    const cardBody = document.getElementById(`user-card-body-${data}`);
    const downIcon = document.getElementById(`down-icon${data}`);

    if (cardBody.classList.contains("in-active")) {
      downIcon.src = "/assets/images/icons/up-icon.png";

      cardBody.classList.remove("in-active");
      cardBody.classList.add("is-active");
    } else {
      downIcon.src = "/assets/images/icons/down-icon.png";
      cardBody.classList.remove("is-active");
      cardBody.classList.add("in-active");
    }
  },

  toggleOnOffproduct: function (data) {
    console.log(data);

    const cardBody = document.getElementById(`product-card-body-${data}`);
    const downIcon = document.getElementById(`down-icon-product-${data}`);

    if (cardBody.classList.contains("in-active")) {
      downIcon.src = "images/icons/up-icon.png";

      cardBody.classList.remove("in-active");
      cardBody.classList.add("is-active");
    } else {
      downIcon.src = "images/icons/down-icon.png";
      cardBody.classList.remove("is-active");
      cardBody.classList.add("in-active");
    }
  },

  getUserByID: function (id) {
    RestClient.get(`admin/user/id/${id}`, function (data) {
      return data;
    });
  },

  showMoreUserInfo: function (user_id) {
    console.log("Fetching user info for ID:", user_id);

    RestClient.get(`/admin/user/id/${user_id}`, function (user) {
      const existingModal = document.getElementById("moreUserInfo");
      if (existingModal) existingModal.innerHTML = "";

      let modalHTML = `
          <div class="modal fade" id="moreUserInfoModal" tabindex="-1" aria-labelledby="moreUserInfoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="moreUserInfoLabel">User Information</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <dvv class="col-md-4 d-flex justify-content-center align-items-center text-center">
                      <img src="${
                        user.user_image_url || "/assets/images/default.png"
                      }" alt="User Image" class="img-fluid rounded-circle mb-3" style="object-fit:cover; max-width:150px; max-height=150px; height=150px; width=150px;" />
                    </dvv>
                    <div class="col-md-8">
                      <p><strong>ID:</strong> ${user.user_id || ""}</p>
                      <p><strong>Full Name:</strong> ${user.name || ""}</p>
                      <p><strong>Email:</strong> ${user.email || ""}</p>
                      <p><strong>Address:</strong> ${user.address || ""}</p>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        `;
      document.getElementById("moreUserInfo").innerHTML = modalHTML;
      const modal = new bootstrap.Modal(
        document.getElementById("moreUserInfoModal")
      );
      modal.show();
    });
  },

  getUserCart: function (user_id) {
    console.log("CLICKED");

    RestClient.get(`/admin/user/cart/${user_id}`, function (cart) {
      console.log(cart);

      const existingModal = document.getElementById("userCartModal");
      if (existingModal) existingModal.innerHTML = "";

      let modalHTML = `

      <div class = "col-md-12">
          <div class="modal fade" id="userCartModalInner" tabindex="-1" aria-labelledby="userCartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg text-center modal-dialog-centered">
              <div class="modal-content">
                <div class="text-center">
                  <h3 class="modal-title p-2 text-center" id="userCartModalLabel">User's Active Cart</h3>
                </div>

                <table class="mt-3 text-center gap-5">
                  <thead>
                      <tr>
                          <th>Cart ID</th>
                          <th>Cart Status</th>
                          <th>Created At</th>
                          <th>Updated At</th>
                          <th>Cart Total</th>
                      </tr>
                  </thead>

                  <tbody class="mb-5 pb-5 "> 
                      <tr>
                        <td>
                        ${cart.cart.cart_ID}
                        </td>

                        <td>
                        ${cart.cart.status}
                        </td>

                         <td>
                        ${cart.cart.created_at}
                        </td>

                         <td>
                        ${cart.cart.updated_at}
                        </td>

                          <td>
                          $${cart.cart.price_total}
                        </td>
                      </tr>
                  </tbody>
                </table>


                
         
                <div class="modal-footer">
                  <button type="button" class="button-line" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          </div>
        `;
      document.getElementById("userCartModal").innerHTML = modalHTML;
      const modal = new bootstrap.Modal(
        document.getElementById("userCartModalInner")
      );
      modal.show();
    });
  },

  addProductModal: function () {
    console.log("Adding product modal");

    // Remove any existing modal
    const existingModal = document.getElementById("addProductModal");
    if (existingModal) existingModal.remove();

    // Category options
    const categories = [
      { id: 1, name: "Pets" },
      { id: 2, name: "Accessories" },
      { id: 3, name: "Food" },
      { id: 4, name: "Toys" },
    ];

    const categoryOptions = categories
      .map((cat) => `<option value="${cat.id}">${cat.name}</option>`)
      .join("");

    const modalHTML = `
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <form id="addProductForm" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="addProductModalLabel">Add New product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5 text-center">
                      <img id="add-product-image-preview" src="images/default.png" alt="product Cover Preview" class="img-fluid rounded mb-3" style="max-height:200px;" />
                      <input type="file" class="form-control mt-2" id="add-product-image" name="image" accept="image/*" required />
                    </div>
                    <div class="col-md-7">
                      <div class="mb-3">
                        <label for="add-product-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="add-product-title" name="title" required>
                      </div>
                      <div class="mb-3">
                        <label for="add-product-author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="add-product-author" name="author" required>
                      </div>
                      <div class="mb-3">
                        <label for="add-product-price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="add-product-price" name="price" min="0" step="0.01" required>
                      </div>
                      <div class="mb-3">
                        <label for="add-product-description" class="form-label">Description</label>
                        <textarea class="form-control" id="add-product-description" name="description" rows="4" required></textarea>
                      </div>
                      <div class="mb-3">
                        <label for="add-product-category" class="form-label">Category</label>
                        <select class="form-select" id="add-product-category" name="category_id" required>
                          <option value="" disabled selected>Select a category</option>
                          ${categoryOptions}
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add product</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      `;

    document.body.insertAdjacentHTML("beforeend", modalHTML);

    // Image preview
    document
      .getElementById("add-product-image")
      .addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (evt) {
            document.getElementById("add-product-image-preview").src =
              evt.target.result;
          };
          reader.readAsDataURL(file);
        }
      });

    document
      .getElementById("addProductForm")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userToken = localStorage.getItem("user_token");

        $.ajax({
          url: Constants.PROJECT_BASE_URL + "admin/product/addproduct",
          type: "POST",
          headers: {
            Authentication: userToken,
          },
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            toastr.success("product added successfully!");
          },
          error: function (xhr) {
            toastr.error("Failed to add product.");
          },
        });

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("addProductModal")
        );
        modal.hide();
        document.getElementById("addProductModal").remove();
      });

    // Show modal
    const modal = new bootstrap.Modal(
      document.getElementById("addProductModal")
    );
    modal.show();
  },

  getProductByName: function (name) {
    RestClient.get(
      "/admin/products/" + title,
      function (result) {
        console.log(result);
      },
      function (error) {
        console.log(error);
      }
    );
  },

  getUserOrderHistory: function (user_id) {
    RestClient.get(`/admin/user/orders/${user_id}`, function (orders) {
      const existingModal = document.getElementById("userOrderHistory");
      if (existingModal) existingModal.innerHTML = "";

      let modalHTML = `
          <div class="modal fade" id="userOrderHistoryModal" tabindex="-1" aria-labelledby="userOrderHistoryLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="userOrderHistoryLabel">User Order History</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Order ID</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        ${
                          orders && orders.length > 0
                            ? orders
                                .map(
                                  (order) => `
                          <tr>
                            <td>${order.order_ID}</td>
                            <td>${order.order_date || ""}</td>
                            <td>${order.status || "Completed"}</td>
                            <td>$${order.total_amount || ""}</td>
                          </tr>
                        `
                                )
                                .join("")
                            : '<tr><td colspan="4" class="text-center">No orders found.</td></tr>'
                        }
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        `;
      document.getElementById("userOrderHistory").innerHTML = modalHTML;
      const modal = new bootstrap.Modal(
        document.getElementById("userOrderHistoryModal")
      );
      modal.show();
    });
  },
};

AdminService.init();
