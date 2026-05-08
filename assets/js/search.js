document.addEventListener("DOMContentLoaded", async () => {

  const modal = document.getElementById("searchModal");
  const openBtn = document.getElementById("searchToggle");
  const closeBtn = document.getElementById("searchClose");
  const input = document.getElementById("searchInput");
  const results = document.getElementById("searchResults");
  const quick = document.getElementById("quickLinks");

  let products = [];

  // load products
  const res = await fetch("data/products.json");
  products = await res.json();

  // open
  openBtn.addEventListener("click", () => {
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
    setTimeout(() => input.focus(), 200);
  });

  // close
  closeBtn.addEventListener("click", closeSearch);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeSearch();
  });

  function closeSearch(){
    modal.classList.remove("active");
    document.body.style.overflow = "";
    input.value = "";
    results.innerHTML = "";
    quick.style.display = "block";
  }

  // search logic
  input.addEventListener("input", (e) => {

    const q = e.target.value.toLowerCase().trim();

    if (q.length < 2) {
      results.innerHTML = "";
      quick.style.display = "block";
      return;
    }

    quick.style.display = "none";

    const filtered = products
      .filter(p =>
        (p.title + " " + p.slug + " " + p.brand + " " + p.category)
        .toLowerCase()
        .includes(q)
      )
      .slice(0, 5);

    if (!filtered.length) {
      results.innerHTML = `<p>No results found</p>`;
      return;
    }

    results.innerHTML = filtered.map(p => `
      <a class="search-item" href="product-details.php?slug=${p.slug}">
        <img src="${p.image}" />
        <div>
          <h4>${p.title}</h4>
          <span>${p.brand}</span>
        </div>
      </a>
    `).join("");
  });

});