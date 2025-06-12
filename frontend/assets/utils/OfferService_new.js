let OfferService = {
  getOffers: function () {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "offers",
      type: "GET",
      contentType: "application/json",
      success: function (res) {
        console.log("Offers fetched successfully:", res);

        const bookshelf = document.getElementById("bookshelf");
        bookshelf.innerHTML = ""; // Clear previous content

        res.forEach((offer) => {
          bookshelf.innerHTML += `
            <div class="offer-card">
              <div class="offer-image">
                <img src="${offer.offer_image}" alt="${offer.offer_name}" />
                <div class="offer-badge">${offer.offer_type}</div>
              </div>
              <div class="offer-content">
                <div class="offer-header">
                  <h3 class="offer-title">${offer.offer_name}</h3>
                  <div class="offer-price">$${offer.offer_price}</div>
                </div>
                <p class="offer-description">${offer.offer_description}</p>
                <div class="offer-footer">
                  <span class="offer-location">📍 ${offer.offer_location}</span>
                  <a href="#view_product" class="offer-btn" onclick="OfferService.getOfferById(${offer.offer_id})">
                    View Details
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          `;
        });
      },
      error: function (err) {
        console.error("Error fetching offers:", err);
        toastr.error("Failed to fetch offers: " + err.responseText);
      },
    });
  },

  getOfferById: function (id) {
    console.log("Fetching offer by ID:", id);

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}offer/id/${id}`,
      type: "GET",
      contentType: "application/json",
      success: function (res) {
        const productViewDiv = document.getElementById("product-view");
        productViewDiv.innerHTML = ""; // Clear previous content

        productViewDiv.innerHTML = `
          <div class="product-view-container">
            <div class="product-image-section">
              <img src="${res.offer_image}" alt="${res.offer_name}" class="product-main-image"/>
            </div>
            <div class="product-info-section">
              <div class="product-header">
                <h1 class="product-title">${res.offer_name}</h1>
                <div class="product-price">$${res.offer_price}</div>
              </div>
              <div class="product-description">
                <p>${res.offer_description}</p>
              </div>
              <div class="product-meta">
                <span class="product-type badge badge-primary">${res.offer_type}</span>
                <span class="product-location">📍 ${res.offer_location}</span>
              </div>
              <button class="btn btn-primary btn-lg" onclick="CartService.bookOffer(${res.offer_id})">
                Book Now
              </button>
            </div>
          </div>
        `;
      },
      error: function (err) {
        console.error("Error fetching offer by ID:", err);
        toastr.error("Failed to fetch offer: " + err.responseText);
      },
    });
  },

  getByCategory: function (categoryName) {
    console.log("Fetching offers by category:", categoryName);

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}offers/category/${categoryName}`,
      type: "GET",
      contentType: "application/json",
      success: function (res) {
        console.log(
          "Offers fetched successfully for category:",
          categoryName,
          res
        );

        const bookshelf = document.getElementById("bookshelf");
        bookshelf.innerHTML = ""; // Clear previous results

        res.forEach((offer) => {
          bookshelf.innerHTML += `
            <div class="offer-card">
              <div class="offer-image">
                <img src="${offer.offer_image}" alt="${offer.offer_name}" />
                <div class="offer-badge">${offer.offer_type}</div>
              </div>
              <div class="offer-content">
                <div class="offer-header">
                  <h3 class="offer-title">${offer.offer_name}</h3>
                  <div class="offer-price">$${offer.offer_price}</div>
                </div>
                <p class="offer-description">${offer.offer_description}</p>
                <div class="offer-footer">
                  <span class="offer-location">📍 ${offer.offer_location}</span>
                  <a href="#view_product" class="offer-btn" onclick="OfferService.getOfferById(${offer.offer_id})">
                    View Details
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          `;
        });
      },
      error: function (err) {
        console.error("Error fetching offers by category:", err);
        toastr.error("Failed to fetch offers by category: " + err.responseText);
      },
    });
  },

  getByName: function (name) {
    console.log("Fetching offers by name:", name);

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}offers/name/${name}`,
      type: "GET",
      contentType: "application/json",
      success: function (res) {
        const bookshelf = document.getElementById("bookshelf");
        bookshelf.innerHTML = "";

        res.forEach((offer) => {
          bookshelf.innerHTML += `
            <div class="offer-card">
              <div class="offer-image">
                <img src="${offer.offer_image}" alt="${offer.offer_name}" />
                <div class="offer-badge">${offer.offer_type}</div>
              </div>
              <div class="offer-content">
                <div class="offer-header">
                  <h3 class="offer-title">${offer.offer_name}</h3>
                  <div class="offer-price">$${offer.offer_price}</div>
                </div>
                <p class="offer-description">${offer.offer_description}</p>
                <div class="offer-footer">
                  <span class="offer-location">📍 ${offer.offer_location}</span>
                  <a href="#view_product" class="offer-btn" onclick="OfferService.getOfferById(${offer.offer_id})">
                    View Details
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          `;
        });
      },
    });
  },

  updateOffers: function () {
    $("#bookCategoryTabs button").click(function () {
      $("#bookCategoryTabs button").removeClass("active");
      $(this).addClass("active");
    });
  },
};
