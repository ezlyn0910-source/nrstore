import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/scss/app.scss",
                "resources/css/home.css",
                "resources/css/productpage.css",
                "resources/css/homepage.css",
                "resources/css/orders.css",
                "resources/css/cart/index.css",
                "resources/js/app.js",
                "resources/css/authlayout.css",
                "resources/css/checkout.css"
            ],
            refresh: true,
        }),
    ],
});
