// Placeholder for CategoryService.js
const CategoryService = {
  init: function () {
    console.log('CategoryService initialized');
  },

  getAllSubcategories: function (categoryID) {
    RestClient.get(
      "/subcategory/" + categoryID,
      function (data) {
        const productSubcategoryDiv = document.getElementById(
          "product-subcategory-div"
        );

        if (productSubcategoryDiv) {
          productSubcategoryDiv.innerHTML = "";

          data.forEach(function (subcategory) {
            productSubcategoryDiv.innerHTML += `
            <div class="subcategory-item" data-value="${subcategory.name}" onclick="CategoryService.getProductsBySubcategoryName('${subcategory.name}')">
             
            <span>

            ${subcategory.name}
            </span>
            </div>
          `;
          });
        }
      },
      function (error) {
        console.error("Error fetching subcategories:", error);
      }
    );
  },

  getProductsBySubcategoryName: function (subcategory_name) {
    RestClient.get("/subcategory-items/" + subcategory_name, function (data) {
      const productItemsDiv = document.getElementById("product-items-div");

      const whichSubcategory = document.getElementById("subcategory");

      if (whichSubcategory) {
        whichSubcategory.innerHTML = "";
        whichSubcategory.style.display = "inline";
        whichSubcategory.innerHTML = ` &nbsp;&nbsp;> ${subcategory_name}`;
      }

      productItemsDiv.innerHTML = "";
      if (data.length === 0) {
        productItemsDiv.innerHTML = `
          <h3> Ooops, there seem to be no ${subcategory_name} right now. Try again later!</h3>
        `;
      }

      data.forEach(function (product) {
        console.log("PRODUCT DATA:", product);

        productItemsDiv.innerHTML += `
        
          <a href = "#view_product" style="text-decoration: none; color:black; outline: none;" onclick = "ProductService.getProductById(${product.product_id})">
          <div class="product-item" data-id=${product.product_id}>
          <div class="product-item-image">
          <img src="/assets/${product.image_url}" ... />
          </div>
          <div class="product-item-info text-start d-flex flex-column">
          <span>
          <h5>${product.name}</h5>
          <span>/ ${product.subcategory_name}</span>
          </span>
          <span>$${product.price}</span>
          </div>
          </div>
          
          </a>
        `;
      });
    });
  },

  loadSubcategoriesInMainView: function () {
    RestClient.get("/subcategories/all", function (data) {
      const allSubcats = document.getElementById("all");
      const allProducts = document.getElementById("all-products");

      data.forEach((element) => {
        console.log(element);

        allSubcats.innerHTML += `

  <div style="padding-bottom:40px;padding-top:16px;">
    <div  style="display:block;scroll-snap-align:start;">
        <div >
            <div >
                <div style="border-radius:18px;box-sizing:border-box;min-width:193px;padding:18px 8px 16px;">
                    <div style="padding-bottom:16px;"><img loading="lazy" width="200" height="130" alt="" src="/assets/images/subcategories/${element.name}-image.png" style="display:block;margin: 0px auto;max-height:78px;width: auto;" /></div>
                    <div class="rf-productnav-card-info">
                        <div><a href="#" onclick="CategoryService.getProductsBySubcategoryName(${element.name})" data-relatedlink=":r8:_link" data-slot-name="Shelf-0" data-display-name="AOS: home/shop_mac" data-autom="AOS: home/shop_mac" data-index="1" data-trigger-stoppropagation="true" style="color:rgb(29, 29, 31);display:block;font-family:'SF Pro Text', 'SF Pro Icons', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size:14px;font-weight:600;letter-spacing:-0.224px;line-height:20.0003px;text-align:center;text-decoration:none solid rgb(29, 29, 31);white-space:nowrap;">${element.name}</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        `;
      });

      const scrollAmount = 10;
      let isDragging = false;
      const backwards = document.getElementById("backwards");

      backwards.addEventListener("click", function () {
        console.log("123");

        allSubcats.scrollBy({
          left: -scrollAmount,
          behavior: "smooth",
        });
      });

      const forwards = document.getElementById("forwards");

      forwards.addEventListener("click", function () {
        console.log("Scrolling forwards");

        allSubcats.scrollBy({
          left: scrollAmount,
          behavior: "smooth",
        });
      });

      allProducts.addEventListener("mouseenter", function () {
        forwards.classList.remove("fade-out");
        backwards.classList.remove("fade-out");

        forwards.style.display = "block";
        backwards.style.display = "block";

        forwards.classList.add("fade-in");
        backwards.classList.add("fade-in");
      });

      allProducts.addEventListener("mouseleave", function () {
        forwards.classList.remove("fade-in");
        backwards.classList.remove("fade-in");

        forwards.classList.add("fade-out");
        backwards.classList.add("fade-out");

        setTimeout(() => {
          backwards.style.display = "none";
          forwards.style.display = "none";
        }, 500);
      });

      allSubcats.addEventListener("mouseup", () => {
        isDragging = false;
        allSubcats.classList.remove("dragging");
      });

      allProducts.addEventListener("mousedown", function (e) {
        isDragging = true;
        allSubcats.classList.add("dragging");
        startX = e.pageX - allSubcats.offsetLeft;
        scrollLeft = allSubcats.scrollLeft;
      });

      allSubcats.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - allSubcats.offsetLeft;
        const walk = (x - startX) * 1.0004;
        allSubcats.scrollLeft = scrollLeft - walk;
      });
    });
  },
};

CategoryService.init();
