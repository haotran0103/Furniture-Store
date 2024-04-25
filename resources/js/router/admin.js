const admin = [{
    path: '/admin',
    name: 'admin',
    component: () => import('../layouts/admin.vue'),
    children: [
        {
            path: 'users',
            name: 'admin-users',
            component: () => import('../pages/admin/users/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'dashboard',
            name: 'admin-dashboard',
            component: () => import('../pages/admin/dashboard/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'products',
            name: 'admin-products',
            component: () => import('../pages/admin/products/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'categories',
            name: 'admin-categories',
            component: () => import('../pages/admin/category/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'orderHistory',
            name: 'orderHistory',
            component: () => import('../pages/admin/orderHistory/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'orders',
            name: 'admin-orders',
            component: () => import('../pages/admin/orders/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'coupon',
            name: 'admin-coupon',
            component: () => import('../pages/admin/coupon/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'tags',
            name: 'admin-tags',
            component: () => import('../pages/admin/tags/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'manage-setting',
            name: 'admin-setting',
            component: () => import('../pages/admin/manage/setting/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'manage-slider',
            name: 'admin-slider',
            component: () => import('../pages/admin/manage/slider/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'manage-menu',
            name: 'admin-menu',
            component: () => import('../pages/admin/manage/menu/index.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: 'manage-account',
            name: 'admin-account',
            component: () => import('../pages/admin/account/index.vue'),
            meta: {
                requiresAuth: true
            }
        }
    ],
    meta: {
        requiresAuth: true
    }
}]
export default admin;