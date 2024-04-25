const authStore = {
    namespaced: true,
    state: {
        user: JSON.parse(localStorage.getItem('user')) || null,
        isAuthenticated: localStorage.getItem('isAuthenticated') === 'true' || false,
    },
    mutations: {
        setAuthentication(state, payload) {
            state.isAuthenticated = payload.isAuthenticated;
            state.user = payload.user;
            localStorage.setItem('isAuthenticated', state.isAuthenticated);
            localStorage.setItem('user', JSON.stringify(state.user));
        },
        clearAuthentication(state) {
            state.isAuthenticated = false;
            state.user = null;
            localStorage.removeItem('isAuthenticated');
            localStorage.removeItem('user');
        },
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                
                if (response.data.success) {
                    commit('setAuthentication', {
                        isAuthenticated: true,
                        user: response.data.user,
                    });
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                console.error('Error logging in:', error);
            }
        },
        logout({ commit }) {
            commit('clearAuthentication');
        }
    },
    getters: {
        isAuthenticated(state) {
            return state.isAuthenticated;
        },
        getUser(state) {
            return state.user;
        },
    },
};

export default authStore;
