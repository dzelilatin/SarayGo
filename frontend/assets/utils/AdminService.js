let AdminService = {
  init: function () {
    AdminService.initializeCharts();
    AdminService.loadSampleOffers();
    AdminService.setupEventListeners();
  },

  initializeCharts: function () {
    // Revenue Chart
    const revenueCtx = document.getElementById("revenueChart");
    if (revenueCtx) {
      new Chart(revenueCtx, {
        type: "line",
        data: {
          labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
          datasets: [
            {
              label: "Revenue",
              data: [4200, 3800, 5100, 4600, 6200, 5800, 7100],
              borderColor: "#3b82f6",
              backgroundColor: "rgba(59, 130, 246, 0.1)",
              borderWidth: 3,
              fill: true,
              tension: 0.4,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
          },
          scales: {
            y: { display: false },
            x: {
              display: true,
              grid: { display: false },
              ticks: { color: "#6b7280", font: { size: 12 } },
            },
          },
          elements: {
            point: { radius: 0, hoverRadius: 6 },
          },
        },
      });
    }    // Category Chart
    const categoryCtx = document.getElementById("categoryChart");
    if (categoryCtx) {
      new Chart(categoryCtx, {
        type: "bar",
        data: {
          labels: ["Hotels", "Restaurants", "Hostels"],
          datasets: [
            {
              data: [45, 32, 23],
              backgroundColor: ["#3b82f6", "#10b981", "#f59e0b"],
              borderWidth: 0,
              borderRadius: 8,
              borderSkipped: false
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
          },
          scales: {
            y: {
              display: false,
              beginAtZero: true
            },            x: {
              display: true,
              grid: { display: false },
              ticks: { color: "#6b7280", font: { size: 12 } }
            }
          }
        }
      });
    }
  },

  loadSampleOffers: function () {
    const sampleOffers = [
      {
        offer_id: 1,
        offer_name: "Hotel Europe",
        offer_type: "Hotel",
        offer_price: "180.00",
        offer_location: "Sarajevo",
        offer_image: "assets/images/offers/3+Hotel-Hills-Sarajevo.png",
        status: "active",
      },
      {
        offer_id: 2,
        offer_name: "Ćevabdžinica Petica",
        offer_type: "Restaurant",
        offer_price: "25.00",
        offer_location: "Sarajevo",
        offer_image: "assets/images/offers/3+Hotel-Hills-Sarajevo.png",
        status: "active",
      },
      {
        offer_id: 3,
        offer_name: "Hostel Franz Ferdinand",
        offer_type: "Hostel",
        offer_price: "45.00",
        offer_location: "Sarajevo",
        offer_image: "assets/images/offers/3+Hotel-Hills-Sarajevo.png",
        status: "inactive",
      },
    ];

    const grid = document.getElementById("offer-management-grid");
    if (grid) {
      grid.innerHTML = sampleOffers
        .map(
          (offer) => `
        <div class="offer-card ${offer.status}">
          <div class="offer-image">
            <img src="${offer.offer_image}" alt="${offer.offer_name}" />
            <div class="offer-status ${offer.status}">
              ${offer.status === "active" ? "●" : "○"} ${offer.status}
            </div>
          </div>
          <div class="offer-details">
            <div class="offer-header">
              <h4>${offer.offer_name}</h4>
              <span class="offer-type">${offer.offer_type}</span>
            </div>
            <div class="offer-meta">
              <span class="offer-price">$${offer.offer_price}</span>
              <span class="offer-location">📍 ${offer.offer_location}</span>
            </div>
            <div class="offer-actions">
              <button class="action-btn small" onclick="AdminService.editOffer(${
                offer.offer_id
              })">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
              <button class="action-btn small danger" onclick="AdminService.deleteOffer(${
                offer.offer_id
              })">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3,6 5,6 21,6"/>
                  <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"/>
                </svg>
                Delete
              </button>
            </div>
          </div>
        </div>
      `
        )
        .join("");
    }
  },

  setupEventListeners: function () {
    // Period selector buttons
    document.querySelectorAll(".period-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        document
          .querySelectorAll(".period-btn")
          .forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
      });
    });
  },

  getUsersByName: function (name) {
    if (!name) {
      const cardBody = document.getElementById("user-card-body");
      cardBody.innerHTML = `
        <div class="empty-state">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <p>Search for customers by name</p>
        </div>
      `;
      return;
    }

    const cardBody = document.getElementById("user-card-body");
    cardBody.innerHTML = '<div class="loading-spinner"></div>';

    RestClient.get(`/admin/user/name/${name}`, function (data) {
      cardBody.innerHTML = data
        .map(
          (user) => `
        <div class="customer-item">
          <div class="customer-header" onclick="AdminService.toggleCustomerDetails(${
            user.user_id
          })">
            <div class="customer-info">
              <div class="customer-avatar">
                <img src="${
                  user.user_image_url || "/assets/images/default-avatar.png"
                }" alt="${user.first_name}" />
              </div>
              <div class="customer-details">
                <h4>${user.first_name} ${user.last_name}</h4>
                <span class="customer-email">${user.email}</span>
              </div>
            </div>
            <div class="expand-icon" id="expand-${user.user_id}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="6,9 12,15 18,9"/>
              </svg>
            </div>
          </div>
          <div class="customer-actions collapsed" id="actions-${user.user_id}">
            <button class="action-btn small" onclick="AdminService.viewCustomerDetails(${
              user.user_id
            })">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              View Details
            </button>
            <button class="action-btn small" onclick="AdminService.getUserCart(${
              user.user_id
            })">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
              </svg>
              View Cart
            </button>
            <button class="action-btn small" onclick="AdminService.getUserOrderHistory(${
              user.user_id
            })">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 3h18v18H3zM9 9h6m-6 4h6"/>
              </svg>
              Order History
            </button>
          </div>
        </div>
      `
        )
        .join("");
    });
  },

  toggleCustomerDetails: function (userId) {
    const actions = document.getElementById(`actions-${userId}`);
    const icon = document.getElementById(`expand-${userId}`);

    if (actions.classList.contains("collapsed")) {
      actions.classList.remove("collapsed");
      actions.classList.add("expanded");
      icon.style.transform = "rotate(180deg)";
    } else {
      actions.classList.remove("expanded");
      actions.classList.add("collapsed");
      icon.style.transform = "rotate(0deg)";
    }
  },

  viewCustomerDetails: function (userId) {
    // Sample customer data
    const sampleUser = {
      user_id: userId,
      name: "John Doe",
      email: "john.doe@example.com",
      address: "123 Main St, Sarajevo, Bosnia and Herzegovina",
      user_image_url: "/assets/images/default-avatar.png",
    };

    const modalHTML = `
      <div class="modern-modal" id="customerModal">
        <div class="modal-backdrop" onclick="AdminService.closeModal('customerModal')"></div>
        <div class="modal-content">
          <div class="modal-header">
            <h3>Customer Details</h3>
            <button class="close-btn" onclick="AdminService.closeModal('customerModal')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="customer-profile">
              <div class="profile-image">
                <img src="${sampleUser.user_image_url}" alt="Customer" />
              </div>
              <div class="profile-info">
                <h4>${sampleUser.name}</h4>
                <div class="info-grid">
                  <div class="info-item">
                    <label>Email</label>
                    <span>${sampleUser.email}</span>
                  </div>
                  <div class="info-item">
                    <label>Address</label>
                    <span>${sampleUser.address}</span>
                  </div>
                  <div class="info-item">
                    <label>Customer ID</label>
                    <span>#${sampleUser.user_id}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    document.getElementById("userDetailsModal").innerHTML = modalHTML;
  },

  getUserCart: function (user_id) {
    const sampleCart = {
      cart: {
        cart_ID: 123,
        status: "active",
        created_at: "2025-06-12 10:30:00",
        updated_at: "2025-06-12 14:15:00",
        price_total: "285.50",
      },
    };

    const modalHTML = `
      <div class="modern-modal" id="cartModal">
        <div class="modal-backdrop" onclick="AdminService.closeModal('cartModal')"></div>
        <div class="modal-content">
          <div class="modal-header">
            <h3>Customer Cart</h3>
            <button class="close-btn" onclick="AdminService.closeModal('cartModal')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="cart-details">
              <div class="detail-grid">
                <div class="detail-item">
                  <label>Cart ID</label>
                  <span>#${sampleCart.cart.cart_ID}</span>
                </div>
                <div class="detail-item">
                  <label>Status</label>
                  <span class="status-badge ${sampleCart.cart.status}">${
      sampleCart.cart.status
    }</span>
                </div>
                <div class="detail-item">
                  <label>Created</label>
                  <span>${new Date(
                    sampleCart.cart.created_at
                  ).toLocaleDateString()}</span>
                </div>
                <div class="detail-item">
                  <label>Last Updated</label>
                  <span>${new Date(
                    sampleCart.cart.updated_at
                  ).toLocaleDateString()}</span>
                </div>
                <div class="detail-item total">
                  <label>Total Amount</label>
                  <span class="amount">$${sampleCart.cart.price_total}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    document.getElementById("userCartModal").innerHTML = modalHTML;
  },

  getUserOrderHistory: function (user_id) {
    const sampleOrders = [
      {
        order_ID: 1001,
        order_date: "2025-06-10",
        status: "Completed",
        total_amount: "189.50",
      },
      {
        order_ID: 1002,
        order_date: "2025-06-05",
        status: "Completed",
        total_amount: "75.00",
      },
      {
        order_ID: 1003,
        order_date: "2025-05-28",
        status: "Cancelled",
        total_amount: "225.00",
      },
    ];

    const modalHTML = `
      <div class="modern-modal" id="orderHistoryModal">
        <div class="modal-backdrop" onclick="AdminService.closeModal('orderHistoryModal')"></div>
        <div class="modal-content large">
          <div class="modal-header">
            <h3>Order History</h3>
            <button class="close-btn" onclick="AdminService.closeModal('orderHistoryModal')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <div class="orders-table">
              <div class="table-header">
                <div>Order ID</div>
                <div>Date</div>
                <div>Status</div>
                <div>Amount</div>
              </div>
              ${sampleOrders
                .map(
                  (order) => `
                <div class="table-row">
                  <div class="order-id">#${order.order_ID}</div>
                  <div>${new Date(order.order_date).toLocaleDateString()}</div>
                  <div><span class="status-badge ${order.status.toLowerCase()}">${
                    order.status
                  }</span></div>
                  <div class="amount">$${order.total_amount}</div>
                </div>
              `
                )
                .join("")}
            </div>
          </div>
        </div>
      </div>
    `;

    document.getElementById("userOrderHistory").innerHTML = modalHTML;
  },

  addOfferModal: function () {
    const modalHTML = `
      <div class="modern-modal" id="addOfferModalInstance">
        <div class="modal-backdrop" onclick="AdminService.closeModal('addOfferModalInstance')"></div>
        <div class="modal-content large">
          <div class="modal-header">
            <h3>Add New Offer</h3>
            <button class="close-btn" onclick="AdminService.closeModal('addOfferModalInstance')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>
          <div class="modal-body">
            <form class="offer-form" id="addOfferForm">
              <div class="form-section">
                <div class="form-group">
                  <label>Offer Name</label>
                  <input type="text" name="offer_name" placeholder="Enter offer name" required />
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label>Type</label>
                    <select name="offer_type" required>
                      <option value="">Select type</option>
                      <option value="Hotel">Hotel</option>
                      <option value="Restaurant">Restaurant</option>
                      <option value="Hostel">Hostel</option>
                      <option value="Coffee Shop">Coffee Shop</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="offer_price" placeholder="0.00" step="0.01" required />
                  </div>
                </div>
                <div class="form-group">
                  <label>Location</label>
                  <input type="text" name="offer_location" placeholder="Enter location" required />
                </div>
                <div class="form-group">
                  <label>Description</label>
                  <textarea name="offer_description" placeholder="Enter description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                  <label>Image</label>
                  <div class="file-upload">
                    <input type="file" name="offer_image" accept="image/*" />
                    <div class="file-upload-text">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21,15 16,10 5,21"/>
                      </svg>
                      <span>Choose image or drag here</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-actions">
                <button type="button" class="action-btn secondary" onclick="AdminService.closeModal('addOfferModalInstance')">Cancel</button>
                <button type="submit" class="action-btn primary">Add Offer</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;

    document.getElementById("addOfferModal").innerHTML = modalHTML;
    document
      .getElementById("addOfferForm")
      .addEventListener("submit", AdminService.handleAddOffer);
  },

  handleAddOffer: function (e) {
    e.preventDefault();
    // Simulate success
    AdminService.showNotification("Offer added successfully!", "success");
    AdminService.closeModal("addOfferModalInstance");
    AdminService.loadSampleOffers(); // Refresh the offers
  },

  editOffer: function (offerId) {
    toastr.error("Error editing offer");
  },

  deleteOffer: function (offerId) {
    if (confirm("Are you sure you want to delete this offer?")) {
      AdminService.showNotification("Offer deleted successfully!", "success");
      AdminService.loadSampleOffers(); // Refresh the offers
    }
  },

  getOfferByName: function (name) {
    // Filter existing offers based on name
    AdminService.loadSampleOffers();
  },

  closeModal: function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.remove();
    }
  },

  showNotification: function (message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.innerHTML = `
      <div class="notification-content">
        <span>${message}</span>
        <button onclick="this.parentElement.parentElement.remove()">×</button>
      </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.remove();
    }, 5000);
  },

  // Legacy methods for compatibility
  renderAdminDiv: function () {
    console.log("Rendering Admin Div");
  },

  toggleOnOff: function (data) {
    AdminService.toggleCustomerDetails(data);
  },

  toggleOnOffproduct: function (data) {
    console.log("Toggle product:", data);
  },

  getUserByID: function (id) {
    RestClient.get(`admin/user/id/${id}`, function (data) {
      return data;
    });
  },

  showMoreUserInfo: function (user_id) {
    AdminService.viewCustomerDetails(user_id);
  },

  addProductModal: function () {
    AdminService.addOfferModal();
  },

  getProductByName: function (name) {
    AdminService.getOfferByName(name);
  },
};
