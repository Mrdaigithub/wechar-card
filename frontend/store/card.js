export const state = () => ({
  cardModelList: null,
});

export const mutations = {
  addCardModelList(state, cardModelList) {
    state.cardModelList = cardModelList;
  },
};

export const actions = {
  async addCardModelList({commit}) {
    const {data} = await this.$axios.$get(`/card`);
    commit('addCardModelList', data);
  },
  async addCardModelListByShopId({commit}, shopId) {
    const {data} = await this.$axios.$get(`/card/shop/${shopId}`);
    commit('addCardModelList', data);
  },
};
