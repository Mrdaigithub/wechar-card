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
  async addShopEmployeeListByShopId({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/user/shop/${arg}`);
    commit('addShopEmployeeList', data);
    cb();
  },
  /**
   * 老板删除员工
   * @param commit
   * @param arg
   * @param cb
   * @returns {Promise<void>}
   */
  async addShopEmployeeListByBoss({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/user/shop/${arg}`);
    commit('addShopEmployeeList', data);
    cb();
  },
};
