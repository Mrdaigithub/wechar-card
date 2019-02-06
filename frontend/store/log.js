export const state = () => ({
  winningLogList: null,
});

export const mutations = {
  addWinningLogList(state, winningLogList) {
    state.winningLogList = winningLogList;
  },
};

export const actions = {
  async addWinningLogList({commit}) {
    const {data} = await this.$axios.$get(`/log/winning`);
    commit('addWinningLogList', data);
  },
};
