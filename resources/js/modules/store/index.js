const storeCart = {
  namespaced: true,
  state: {
    cartItems: JSON.parse(localStorage.getItem('cartItems')) || [],
  },
  mutations: {
    addToCart(state, product) {
      const existingProductIndex = state.cartItems.findIndex(item => item.id === product.id);
      if (existingProductIndex !== -1) {
        state.cartItems[existingProductIndex].quantity++;
      } else {
        state.cartItems.push({ ...product, quantity: 1 });
      }
      localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
    },
    removeFromCart(state, productId) {
      const productIndex = state.cartItems.findIndex(item => item.id === productId);
      if (productIndex !== -1) {
        state.cartItems.splice(productIndex, 1);
        localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
      }
    },
    updateCartItemQuantity(state, { productId, quantity }) {
      const product = state.cartItems.find(item => item.id === productId);
      if (product) {
        product.quantity = quantity;
        localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
      }
    },
    clearCart(state) {
      state.cartItems = [];
      localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
    },
  },
  actions: {
    addToCart({ commit }, product) {
      commit('addToCart', product);
    },
    removeFromCart({ commit }, productId) {
      commit('removeFromCart', productId);
    },
    updateCartItemQuantity({ commit }, { productId, quantity }) {
      commit('updateCartItemQuantity', { productId, quantity });
    },
    clearCart({ commit }) {
      commit('clearCart');
    },
  },
  getters: {
    cartItems: state => state.cartItems,
    totalPrice: state => state.cartItems.reduce((total, item) => total + item.price * item.quantity, 0),
  },
};

export default storeCart;
