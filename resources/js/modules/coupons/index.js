const state = {
    namespaced: true,
    coupons: [
        {
            id: 1,
            code: "DISCOUNT10",
            type: "percent",
            value: 10,
            maxUsage: 100,
            startDate: "2024-04-10",
            endDate: "2024-04-30",
            minOrderAmount: 100,
        }, // Ví dụ coupon giảm giá 10%
        {
            id: 2,
            code: "FREESHIP",
            type: "free_shipping",
            maxUsage: 50,
            startDate: "2024-04-15",
            endDate: "2024-04-25",
            minOrderAmount: 200,
        }, // Ví dụ coupon miễn phí vận chuyển
        {
            id: 3,
            code: "SALE20",
            type: "fixed_amount",
            value: 20,
            maxUsage: 200,
            startDate: "2024-04-05",
            endDate: "2024-04-20",
            minOrderAmount: 150,
        }, // Ví dụ coupon giảm cố định 20 đơn vị tiền tệ
    ],
    appliedCoupon: null,
};

const mutations = {
    applyCoupon(state, coupon) {
        state.appliedCoupon = coupon;
    },
    removeCoupon(state) {
        state.appliedCoupon = null;
    },
};

const actions = {
    applyCoupon({ commit, state }, couponCode) {
        const coupon = state.coupons.find(
            (coupon) => coupon.code === couponCode
        );
        if (coupon) {
            if (isValidCoupon(coupon)) {
                commit("applyCoupon", coupon);
                return true;
            } else {
                console.error("Coupon is not valid for current order.");
                return false;
            }
        } else {
            console.error("Coupon not found.");
            return false;
        }
    },
    removeCoupon({ commit }) {
        commit("removeCoupon");
    },
};

const getters = {
    availableCoupons(state) {
        const currentDate = new Date();
        return state.coupons.filter(
            (coupon) =>
                currentDate >= new Date(coupon.startDate) &&
                currentDate <= new Date(coupon.endDate)
        );
    },
};

function isValidCoupon(coupon, orderTotal) {
    const currentDate = new Date();
    return (
        currentDate >= new Date(coupon.startDate) &&
        currentDate <= new Date(coupon.endDate) &&
        (coupon.minOrderAmount ? orderTotal >= coupon.minOrderAmount : true) &&
        coupon.maxUsage > 0
    );
}

export default {
    state,
    mutations,
    actions,
    getters,
};
