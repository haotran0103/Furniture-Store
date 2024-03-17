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
        }
    ],
    meta: {
        requiresAuth: true
    }
}]
export default admin;