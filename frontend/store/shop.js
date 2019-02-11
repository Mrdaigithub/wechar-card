export const state = () => ({
  shopList: null,
  activity: null,
});

export const mutations = {
  addShopList(state, shopList) {
    state.shopList = shopList;
  },
  addShopActivity(state, activity) {
    state.activity = activity;
  },
};

export const actions = {
  async addShopList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/shop`);
    commit('addShopList', data);
    cb();
  },

  /**
   * 添加指定商铺关联的活动
   *
   * @param commit
   * @param arg
   * @param cb
   * @returns {Promise<void>}
   */
  async addShopActivity({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/activity/shop/${arg}`);
    commit('addShopActivity', data);
    cb();
  },
};
