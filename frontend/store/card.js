export const state = () => ({
  cardModelList: null,
});

export const mutations = {
  addCardModelList(state, cardModelList) {
    state.cardModelList = cardModelList;
  },
};

export const actions = {
  async addCardModelList({commit}, shopId) {
    const {data} = await this.$axios.$get(`/card/shop/${shopId}`);
    commit('addCardModelList', data);
  },
};
