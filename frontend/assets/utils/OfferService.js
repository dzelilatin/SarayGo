let OfferService = {
  getOffers: function () {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "offers",
      type: "GET",
      contentType: "application/json",
      success: function (res) {
        console.log("Offers fetched successfully:", res);

        const bookshelf = document.getElementById("bookshelf");

        res.forEach((book) => {
          bookshelf.innerHTML += `

            
            <div class="col-md-3 text-center">
            <div class="product-item w-100 h-100">
            <figure class="product-style">
            <img
            src="${book.offer_image}"
            alt="Books"
            class="product-item"
            />
          
            </figure>
            <figcaption>
            <h3>${book.offer_name}</h3>
            <div class="item-price">$${book.offer_price}</div>
            </figcaption>
              <a href= "#view_product" onclick="OfferService.getOfferById(${book.offer_id})">
            View More
            </a> 
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

        productViewDiv.inerHTML = ""; // Clear previous content

        productViewDiv.innerHTML = `

          <div class="product-view row mt-5">
    <div class="col-md-7  mt-5 product-image-view-thing">
     <img src="${res.offer_image}" alt="" srcset="" style="object-fit:cover; height:100%; width:100%;"/>
    </div>
    <div class="col-md-4 rounded p-lg-5 d-flex flex-column justify-content-center text-center">
      <h1>${res.offer_name}</h1>
      <h4
        style="
          border-bottom: 1px solid #333;
          width: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
          margin-top: 1rem;
          padding-bottom: 1rem;
        "

     
      >
        $${res.offer_price}
      </h4>
      <div
        class="row row-cols-auto text-center mt-5 pl-2 d-flex justify-content-between"
      >
        ${res.offer_description}
      </div>
      <button class="w-50" onclick= "CartService.bookNow(${res.offer_id})">Book Now</button>
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
        console.log("Offers fetched successfully for category:", categoryName, res);

        const bookshelf = document.getElementById("bookshelf");
        bookshelf.innerHTML = ""; // Clear previous results

        res.forEach((offer) => {
          bookshelf.innerHTML += `
            <div class="col-md-3 text-center">
              <div class="product-item w-100 h-100">
                <figure class="product-style">
                  <img
                    src="${offer.offer_image}"
                    alt="${offer.offer_name}"
                    class="product-item"
                  />
                </figure>
                <figcaption>
                  <h3>${offer.offer_name}</h3>
                  <div class="item-price">$${offer.offer_price}</div>
                </figcaption>
                <a href="#view_product" onclick="OfferService.getOfferById(${offer.offer_id})">
                  View More
                </a>
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

        console.log(res);

        bookshelf.innerHTML = ""; // Clear previous results

        res.forEach((book) => {
          bookshelf.innerHTML += `

            <a href="#view_product" onclick="OfferService.getBookById(${book.offer_id})">


            
                        <div class="col-md-3 text-center">
                        <div class="product-item">
                        <figure class="product-style">
                        <img
                        src="${book.offer_image}"
                        alt="Books"
                        class="product-item"
                        />
                      
                        </figure>
                        <figcaption>
                        <h3>${book.offer_name}</h3>
                        <div class="item-price">$${book.offer_price}</div>
                        </figcaption>
                          <a href= "#view_book" onclick="BookService.getBookById(${book.offer_id})">
                        View More
                        </a> 
                        </div>
                        </div>

,           </a>
            
            `;
        });
      },
    });
  },

  updateOffers: function () {
    $("#bookCategoryTabs li").click(function () {
      $("#bookCategoryTabs li").removeClass("active");
      $(this).addClass("active");
    });
  },
};
