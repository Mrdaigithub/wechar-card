export const state = () => ({
  cardList: null,
});

export const mutations = {
  add(state, cardList) {
    state.cardList = cardList;
  },
};

export const actions = {
  async add({commit}, shopId) {
    const {data} = await this.$axios.$get(`/card/shop/${shopId}`);
    commit('add', data);
  },
};
