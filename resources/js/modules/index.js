import { createStore } from 'vuex';
import storeCart from './store/index.js';
import coupons from './coupons/index.js';
import authStore from './admin/store.js';

const store = createStore({
  modules: {
    cart: storeCart,
    coupons: coupons,
    authStore: authStore
  },
});

export default store;
