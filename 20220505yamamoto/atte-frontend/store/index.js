const cookieparser = require('cookieparser');

export const actions = {
  async nuxtClientInit({ commit }, { app }) {
    cookieparser.parse(document.cookie)

    await app.$axios
      .$get('/api/user')
      .then((authUser) => {
        commit('register/setByUserInfo', authUser);
        commit('auth/setAuthUser', authUser);
      })
      .catch((err) => {
        console.log(err);
        commit('auth/setAuthUser', null);
      })
  },
}