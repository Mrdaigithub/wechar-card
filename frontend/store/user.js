export const state = () => ({
  userList: [],
  shopEmployeeList: [],
});

export const mutations = {
  addUserList(state, userList) {
    state.userList = userList;
  },
  addShopEmployeeList(state, shopEmployeeList) {
    state.shopEmployeeList = shopEmployeeList;
  },
};

export const actions = {
  async addUserList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/user/plain_user`);
    commit('addUserList', data);
    cb();
  },
  async addShopEmployeeList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/user/shop`);
    commit('addShopEmployeeList', data);
    cb();
  },
};
