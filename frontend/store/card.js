export const state = () => ({
  cardModelList: null,
});

export const mutations = {
  addCardModelList(state, cardModelList) {
    state.cardModelList = cardModelList;
  },
};

export const actions = {
  async addCardModelList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/card`);
    commit('addCardModelList', data);
    cb();
  },
  async addCardModelListByShopId({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/card/shop/${arg}`);
    commit('addCardModelList', data);
    cb();
  },
};
