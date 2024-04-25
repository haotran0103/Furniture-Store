import { createRouter, createWebHistory } from "vue-router";
import admin from "./admin.js";
import auth from "./auth.js";
import user from "./user.js";
import store from "../modules/index.js";
const routes = [...admin, ...auth, ...user];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const isAuthenticated = store.getters["authStore/isAuthenticated"]; 
    console.log(isAuthenticated);
    if (requiresAuth && !isAuthenticated) {
        next({ path: "/auth/login" });
    } else {
        next();
    }
});

export default router;
