export const state = () => ({
  shopList: null,
});

export const mutations = {
  addShopList(state, shopList) {
    state.shopList = shopList;
  },
};

export const actions = {
  async addShopList({commit}) {
    const {data} = await this.$axios.$get(`/shop`);
    commit('addShopList', data);
  },
};
