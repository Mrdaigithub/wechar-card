export const state = () => ({
  winningLogList: null,
});

export const mutations = {
  addWinningLogList(state, winningLogList) {
    state.winningLogList = winningLogList;
  },
};

export const actions = {
  async addWinningLogList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/log/winning`);
    commit('addWinningLogList', data);
    cb();
  },
  async addWinningLogListByShopId({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/log/winning/shop/${arg}`);
    commit('addWinningLogList', data);
    cb();
  },
};
