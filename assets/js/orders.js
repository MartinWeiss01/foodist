const orders = document.getElementsByClassName("orderPackage");
for(let i = 0; i < orders.length; i++) {
    let tempPackages = orders[i].querySelectorAll(".order-items .orderItem"),
        tempTotalPrice = 0;
    for(let j = 0; j < tempPackages.length; j++) tempTotalPrice += ((tempPackages[j].dataset.price) * (tempPackages[j].dataset.quantity));

    const totalPriceElement = document.createElement("div");
    totalPriceElement.className = "flex row justify-content-between orderSummary";
    totalPriceElement.innerHTML = `<span class="order-h1">Celkem</span><span class="order-h1">${tempTotalPrice.toFixed(2)} Kč</span>`;
    orders[i].appendChild(totalPriceElement);
}