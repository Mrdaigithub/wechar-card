export const state = () => ({
  shop: null,
  shopList: null,
  activity: null,
});

export const mutations = {
  addShopList(state, shopList) {
    state.shopList = shopList;
  },
  addShop(state, shop) {
    state.shop = shop;
  },
  addShopActivity(state, activity) {
    state.activity = activity;
  },
};

export const actions = {

  /**
   * 添加商铺列表
   *
   * @param commit
   * @param cb
   * @returns {Promise<void>}
   */
  async addShopList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/shop`);
    commit('addShopList', data);
    cb();
  },

  /**
   * 添加老板所属的商铺
   *
   * @param commit
   * @param cb
   * @returns {Promise<void>}
   */
  async addShop({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/shop/boss`);
    commit('addShop', data);
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
