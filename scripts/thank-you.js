async function getRandomQuote() {
  const response = await fetch("../assets/data/quotes.json");
  const data = await response.json();
  const index = Math.floor(Math.random() * data.length);
  console.log(data[index])
  return data[index];
}

async function displayQuote() {
  const quote = await getRandomQuote();
  const quoteContainer = document.querySelector("[data-quote='container']");
  if (quote) {
    quoteContainer.classList.add("active");
  }

  quoteContainer.querySelector(
    "[data-quote='phrase']"
  ).textContent = `"${quote.frase}"`;
  quoteContainer.querySelector(
    "[data-quote='author']"
  ).textContent = `- ${quote.autor}`;
}

displayQuote();
