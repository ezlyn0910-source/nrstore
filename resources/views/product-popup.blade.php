<div id="productPopupOverlay" class="product-popup-overlay">
    <div class="product-popup-box">

        <!-- Close Button -->
        <button class="popup-close-btn" onclick="closeProductPopup()">×</button>

        <!-- LEFT SIDE: IMAGE -->
        <div class="popup-left">
            <img id="popupProductImage" src="" alt="Product Image">
        </div>

        <!-- RIGHT SIDE: DETAILS -->
        <div class="popup-right">

            <h4 id="popupBrand" class="popup-brand"></h4>

            <h2 id="popupName" class="popup-name"></h2>

            <span id="popupStock" class="popup-stock"></span>

            <p id="popupPrice" class="popup-price"></p>

            <p id="popupDescription" class="popup-description"></p>

            <!-- Variations -->
            <div id="popupVariations" class="popup-variations"></div>

            <!-- Quantity -->
            <div class="popup-quantity">
                <button onclick="changeQty(-1)">−</button>
                <input id="popupQty" type="number" value="1" min="1">
                <button onclick="changeQty(1)">+</button>
            </div>

            <!-- Action Buttons -->
            <div class="popup-actions">
                <button id="popupAddCartBtn" class="popup-cart-btn">Add to Cart</button>
                <button id="popupBuyNowBtn" class="popup-buy-btn">Buy Now</button>
            </div>

            <!-- SKU + Social -->
            <div class="popup-extra">
                <p><strong>SKU:</strong> <span id="popupSKU"></span></p>

                <div class="popup-share">
                    <span>Share:</span>
                    <a id="shareFb" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a id="shareWa" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <a id="shareTw" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.product-popup-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 99999;
}

.product-popup-box {
    display: flex;
    background: #fff;
    width: 850px;
    max-width: 95%;
    border-radius: 18px;
    overflow: hidden;
    padding: 25px;
    gap: 25px;
    animation: fadeIn 0.25s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.popup-left img {
    width: 360px;
    height: 360px;
    object-fit: cover;
    border-radius: 12px;
}

.popup-right {
    flex: 1;
}

.popup-close-btn {
    position: absolute;
    top: 18px;
    right: 18px;
    font-size: 28px;
    background: none;
    border: none;
    cursor: pointer;
}

.popup-brand {
    font-size: 15px;
    color: #777;
    margin-bottom: 8px;
}

.popup-name {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 10px;
}

.popup-stock {
    color: #2d4a35;
    font-size: 14px;
    font-weight: 700;
}

.popup-price {
    color: #2d4a35;
    font-size: 24px;
    font-weight: 700;
    margin: 10px 0;
}

.popup-description {
    margin: 12px 0;
    font-size: 15px;
}

.popup-variations button {
    margin: 5px;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background: white;
}

.popup-variations button.active {
    border-color: #2d4a35;
    background: #e8f4ee;
}

.popup-quantity {
    display: flex;
    align-items: center;
    margin: 15px 0;
}

.popup-quantity button {
    width: 38px;
    height: 38px;
    border: 1px solid #ccc;
    background: #fff;
    font-size: 20px;
}

.popup-quantity input {
    width: 55px;
    text-align: center;
    border: 1px solid #ccc;
    height: 38px;
}

.popup-actions {
    display: flex;
    gap: 12px;
    margin: 15px 0;
}

.popup-cart-btn,
.popup-buy-btn {
    padding: 14px 26px;
    background: #2d4a35;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.popup-extra {
    margin-top: 12px;
}

.popup-share a {
    margin-right: 10px;
    color: #2d4a35;
}
</style>

<script>
function openProductPopup(data) {
    document.getElementById('popupProductImage').src = data.image;
    document.getElementById('popupName').innerText = data.name;
    document.getElementById('popupBrand').innerText = data.brand;
    document.getElementById('popupDescription').innerText = data.description;
    document.getElementById('popupPrice').innerText = "RM " + data.price;
    document.getElementById('popupSKU').innerText = data.sku;

    document.getElementById('popupStock').innerText =
        data.stock > 0 ? "In Stock" : "Out of Stock";

    document.getElementById('productPopupOverlay').style.display = "flex";

    // Social share
    document.getElementById('shareFb').href = `https://facebook.com/sharer/sharer.php?u=${data.url}`;
    document.getElementById('shareWa').href = `https://wa.me/?text=${data.url}`;
    document.getElementById('shareTw').href = `https://twitter.com/intent/tweet?url=${data.url}`;

    // Add to cart
    document.getElementById('popupAddCartBtn').onclick = function () {
        window.location.href = `/cart/add/${data.id}?qty=${document.getElementById('popupQty').value}`;
    };

    // Buy now
    document.getElementById('popupBuyNowBtn').onclick = function () {
        window.location.href = `/checkout/buy/${data.id}?qty=${document.getElementById('popupQty').value}`;
    };
}

function closeProductPopup() {
    document.getElementById('productPopupOverlay').style.display = "none";
}

function changeQty(val) {
    let qty = document.getElementById('popupQty');
    qty.value = Math.max(1, parseInt(qty.value) + val);
}
</script>
