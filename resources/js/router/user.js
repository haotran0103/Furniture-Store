const user = [
    {
        path: "/",
        name: "home",
        component: () => import("../layouts/user.vue"),
        children: [
            {
                path: "/",
                name: "home",
                component: () => import("../pages/user/home/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/products-detail/:productId",
                name: "products-detail",
                component: () => import("../pages/user/detail/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/products-all",
                name: "products-all",
                component: () => import("../pages/user/category/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/products/:category",
                name: "ProductCategory",
                component: () => import("../pages/user/product/index.vue"),
            },
            {
                path: "/checkout",
                name: "checkout",
                component: () =>
                    import("../pages/user/account/checkout/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/orders",
                name: "orders",
                component: () =>
                    import("../pages/user/account/order/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/OrderConfirmation",
                name: "OrderConfirmation",
                component: () =>
                    import("../pages/user/account/OrderConfirmation/index.vue"),
                meta: {
                    auth: true,
                },
            },
            {
                path: "/my-account",
                name: "my-account",
                component: () =>
                    import("../pages/user/account/profile/index.vue"),
                meta: {
                    auth: true,
                },
            },
        ],
    },
];
export default user;
