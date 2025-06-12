let NavbarService = {
  __init: function () {
    const navbar = document.getElementById("navbar");
    navbar.innerHTML = "";
    const userToken = localStorage.getItem("user_token");

    if (userToken) {
      const decodedToken = jwt_decode(userToken);
      const isAdmin = decodedToken.user.is_admin;

      if (isAdmin === 1) {
        this.renderAdminNavbar();
      } else {
        this.renderUserNavbar();
      }
    } else {
      this.renderNavbar();
    }
  },
  renderAdminNavbar: function () {
    const navbar = document.getElementById("navbar");
    navbar.innerHTML = "";
    navbar.innerHTML = `
      <nav class="modern-navbar">
        <div class="nav-container">
          <!-- Left: Logo -->
          <div class="nav-brand">
            <a href="/SarayGo/frontend/" class="brand-link">
              <span class="brand-text">SarayGo</span>
              <span class="brand-badge admin">Admin</span>
            </a>
          </div>

          <!-- Center: Navigation Links -->
          <div class="nav-links">
            <a href="/SarayGo/frontend/" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9,22 9,12 15,12 15,22"/>
              </svg>
              <span>Home</span>
            </a>
            
            <a href="#view_shop" onclick="NavbarService.showMore()" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              <span>Shop</span>
            </a>

            <a href="#view_cart" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
              </svg>
              <span>Cart</span>
            </a>
          </div>

          <!-- Right: Admin Actions -->
          <div class="nav-actions">
            <a href="#view_admin" class="nav-action admin">
              <svg class="action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              </svg>
              <span>Admin Panel</span>
            </a>

            <button onclick="AuthService.logOut()" class="nav-action logout">
              <svg class="action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16,17 21,12 16,7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              <span>Sign out</span>
            </button>
          </div>
        </div>
      </nav>
    `;
  },
  renderUserNavbar: function () {
    const navbar = document.getElementById("navbar");
    navbar.innerHTML = "";
    navbar.innerHTML = `
      <nav class="modern-navbar">
        <div class="nav-container">
          <!-- Left: Logo -->
          <div class="nav-brand">
            <a href="/SarayGo/frontend/" class="brand-link">
              <span class="brand-text">SarayGo</span>
            </a>
          </div>

          <!-- Center: Navigation Links -->
          <div class="nav-links">
            <a href="/SarayGo/frontend/" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9,22 9,12 15,12 15,22"/>
              </svg>
              <span>Home</span>
            </a>
            
            <a href="#view_shop" onclick="NavbarService.showMore()" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              <span>Shop</span>
            </a>

            <a href="#view_cart" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
              </svg>
              <span>Cart</span>
            </a>
          </div>

          <!-- Right: User Actions -->
          <div class="nav-actions">
            <button onclick="AuthService.logOut()" class="nav-action logout">
              <svg class="action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16,17 21,12 16,7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              <span>Sign out</span>
            </button>
          </div>
        </div>
      </nav>
    `;
  },
  renderNavbar: function () {
    const navbar = document.getElementById("navbar");
    navbar.innerHTML = "";
    navbar.innerHTML = `
      <nav class="modern-navbar">
        <div class="nav-container">
          <!-- Left: Logo -->
          <div class="nav-brand">
            <a href="/SarayGo/frontend/" class="brand-link">
              <span class="brand-text">SarayGo</span>
            </a>
          </div>

          <!-- Center: Navigation Links -->
          <div class="nav-links">
            <a href="/SarayGo/frontend/" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9,22 9,12 15,12 15,22"/>
              </svg>
              <span>Home</span>
            </a>
            
            <a href="#view_shop" onclick="NavbarService.showMore()" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"/>
                <circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              <span>Shop</span>
            </a>

            <a href="#view_cart" class="nav-link">
              <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
              </svg>
              <span>Cart</span>
            </a>
          </div>

          <!-- Right: Auth Actions -->
          <div class="nav-actions">
            <a href="#view_login" class="nav-action login">
              <svg class="action-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10,17 15,12 10,7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
              </svg>
              <span>Sign in</span>
            </a>
          </div>
        </div>
      </nav>
    `;
  },

  showMore: function () {
    const shopmenu = document.getElementById("shop-flydown-menu");

    if (!shopmenu) {
      console.error("Shop menu element not found");
    }

    const shopFlyDownMenu = document.getElementById("shopFlyDownMenu");

    if (!shopFlyDownMenu) {
      console.error("shopFlyDownMenu element not found in the DOM.");
      return;
    }

    console.log("Mouse over shop menu");

    shopFlyDownMenu.classList.add("show");
    navbar.style.borderBottom = "none";
    navbar.style.boxShadow = "none";
  },
};
