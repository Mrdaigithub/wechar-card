export const state = () => ({
  userList: []
});

export const mutations = {
  addUserList(state, user) {
    state.userList = user;
  },
};

export const actions = {
  async addUserList({commit}) {
    const {data} = await this.$axios.$get(`/user/plain_user`);
    commit('addUserList', data);
  },
};
