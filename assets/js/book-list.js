document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector('input[name="query"]');
  const bookCards = document.querySelectorAll(".book-card");

  const handleSearch = debounce(() => {
    const query = searchInput.value.toLowerCase();

    bookCards.forEach((card) => {
      const { title, author } = card.dataset;
      const isVisible =
        title.toLowerCase().includes(query) ||
        author.toLowerCase().includes(query);
      card.style.display = isVisible ? "block" : "none";
    });

    if (query.length >= 2) {
      fetchBooks(query);
    }
  }, 300);

  if (searchInput) {
    searchInput.addEventListener("input", handleSearch);
  }
});
